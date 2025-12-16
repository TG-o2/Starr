<?php
require_once __DIR__ . '/../../../init.php';

$lessonId = (int)($_GET['id'] ?? 0);
if ($lessonId <= 0) {
    echo "Invalid lesson ID";
    exit;
}

// Get lesson data directly from model
$lessonModel = new LessonModel();
$lesson = $lessonModel->getById($lessonId);

if (!$lesson) {
    echo "Lesson not found";
    exit;
}

// Get quiz questions for this lesson
$questionModel = new QuestionModel();
$questions = $questionModel->getByLesson($lessonId);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($lesson['title']) ?> - Lesson Details</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="<?= htmlspecialchars(substr($lesson['description'], 0, 150)) ?>" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@600&family=Lobster+Two:wght@700&display=swap" rel="stylesheet">
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    
    <style>
        .lesson-detail-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-bottom: 30px;
        }
        
        .lesson-header-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
        }
        
        .lesson-content {
            padding: 40px;
        }
        
        .lesson-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 20px;
        }
        
        .lesson-meta-info {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f8f9fa;
        }
        
        .meta-info-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .meta-info-item i {
            font-size: 1.2rem;
            color: #4e73df;
        }
        
        .meta-info-content h6 {
            margin: 0;
            font-size: 0.9rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .meta-info-content p {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 600;
            color: #2c3e50;
        }
        
        .lesson-description {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #495057;
            margin-bottom: 40px;
        }
        
        .questions-section {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
        }
        
        .section-title {
            font-size: 1.8rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .section-title i {
            color: #4e73df;
        }
        
        .question-item {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            border-left: 4px solid #4e73df;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .question-text {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .question-points {
            background: #e3f2fd;
            color: #1565c0;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .correct-answers {
            background: #e8f5e8;
            color: #2e7d32;
            padding: 10px 15px;
            border-radius: 8px;
            margin-top: 10px;
            font-size: 0.95rem;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            margin-top: 30px;
        }
        
        .btn-action {
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        
        .btn-primary-action {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            color: white;
        }
        
        .btn-primary-action:hover {
            background: linear-gradient(135deg, #224abe 0%, #1a3a8a 100%);
            color: white;
            transform: translateY(-2px);
        }
        
        .btn-success-action {
            background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
            color: white;
        }
        
        .btn-success-action:hover {
            background: linear-gradient(135deg, #13855c 0%, #0e5b4a 100%);
            color: white;
            transform: translateY(-2px);
        }
        
        .empty-questions {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }
        
        .empty-questions i {
            font-size: 3rem;
            color: #dee2e6;
            margin-bottom: 15px;
        }
        
        @media (max-width:768px) {
            .lesson-content {
                padding: 20px;
            }
            
            .lesson-title {
                font-size: 1.8rem;
            }
            
            .lesson-meta-info {
                gap: 15px;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .btn-action {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <div class="container-xxl bg-white p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->

        <!-- Navbar Start -->
        <nav class="navbar navbar-expand-lg bg-white navbar-light sticky-top px-4 px-lg-5 py-lg-0">
            <a href="index.php" class="navbar-brand">
                <h1 class="m-0 text-primary"><i class="fa fa-book-reader me-3"></i>Kider</h1>
            </a>
            <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav mx-auto">
                    <a href="index.php" class="nav-item nav-link">Home</a>
                    <a href="about.html" class="nav-item nav-link">About Us</a>
                    <a href="classes.html" class="nav-item nav-link">Classes</a>
                    <a href="lessons.php" class="nav-item nav-link active">Lessons</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
                        <div class="dropdown-menu rounded-0 rounded-bottom border-0 shadow-sm m-0">
                            <a href="facility.html" class="dropdown-item">School Facilities</a>
                            <a href="team.html" class="dropdown-item">Popular Teachers</a>
                            <a href="call-to-action.html" class="dropdown-item">Become A Teachers</a>
                            <a href="appointment.html" class="dropdown-item">Make Appointment</a>
                            <a href="testimonial.html" class="dropdown-item">Testimonial</a>
                            <a href="404.html" class="dropdown-item">404 Error</a>
                        </div>
                    </div>
                    <a href="contact.html" class="nav-item nav-link">Contact Us</a>
                </div>
                <a href="../back/lessonList_direct.php" class="btn btn-primary rounded-pill px-3 d-none d-lg-block">Admin<i class="fa fa-arrow-right ms-3"></i></a>
            </div>
        </nav>
        <!-- Navbar End -->

        <!-- Page Header Start -->
        <div class="container-xxl py-5 bg-primary hero-header mb-5">
            <div class="container my-5 py-5 px-lg-5">
                <div class="row g-5 py-5">
                    <div class="col-lg-12 text-center">
                        <h1 class="display-2 text-white animated slideInDown mb-4">Lesson Details</h1>
                        <nav aria-label="breadcrumb animated slideInDown">
                            <ol class="breadcrumb justify-content-center">
                                <li class="breadcrumb-item"><a href="index.php" class="text-white">Home</a></li>
                                <li class="breadcrumb-item"><a href="lessons.php" class="text-white">Lessons</a></li>
                                <li class="breadcrumb-item text-white active" aria-current="page"><?= htmlspecialchars($lesson['title']) ?></li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page Header End -->

        <!-- Lesson Detail Start -->
        <div class="container-xxl py-5">
            <div class="container px-lg-5">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="lesson-detail-card">
                            <?php 
                            $headerImagePath = '';
                            if (!empty($lesson['thumbnail_url'])) {
                                // Ensure correct base path for thumbnail
                                $thumbnailUrl = $lesson['thumbnail_url'];
                                $thumbnailUrl = ltrim($thumbnailUrl, '/');
                                $headerImagePath = '/lessons_project/' . $thumbnailUrl;
                            } else {
                                // Use default image based on lesson ID
                                $defaultImages = ['classes-1.jpg', 'classes-2.jpg', 'classes-3.jpg', 'classes-4.jpg', 'classes-5.jpg', 'classes-6.jpg'];
                                $imageIndex = ($lesson['lessonId'] - 1) % 6;
                                $headerImagePath = 'img/' . $defaultImages[$imageIndex];
                            }
                            ?>
                            <img src="<?= $headerImagePath ?>" alt="<?= htmlspecialchars($lesson['title']) ?>" class="lesson-header-image" onerror="this.src='img/classes-1.jpg';">
                            
                            <div class="lesson-content">
                                <h1 class="lesson-title"><?= htmlspecialchars($lesson['title']) ?></h1>
                                
                                <div class="lesson-meta-info">
                                    <div class="meta-info-item">
                                        <i class="fas fa-users"></i>
                                        <div class="meta-info-content">
                                            <h6>Age Range</h6>
                                            <p><?= htmlspecialchars($lesson['ageRange'] ?? 'All ages') ?></p>
                                        </div>
                                    </div>
                                    
                                    <div class="meta-info-item">
                                        <i class="fas fa-clock"></i>
                                        <div class="meta-info-content">
                                            <h6>Duration</h6>
                                            <p><?= htmlspecialchars($lesson['duration'] ?? 30) ?> minutes</p>
                                        </div>
                                    </div>
                                    
                                    <div class="meta-info-item">
                                        <i class="fas fa-signal"></i>
                                        <div class="meta-info-content">
                                            <h6>Difficulty</h6>
                                            <p><?= htmlspecialchars(ucfirst($lesson['difficulty'] ?? 'Beginner')) ?></p>
                                        </div>
                                    </div>
                                    
                                    <div class="meta-info-item">
                                        <i class="fas fa-tag"></i>
                                        <div class="meta-info-content">
                                            <h6>Category</h6>
                                            <p><?= htmlspecialchars(ucfirst($lesson['category'] ?? 'General')) ?></p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="lesson-description">
                                    <?= nl2br(htmlspecialchars($lesson['description'])) ?>
                                </div>
                                
                                <div class="action-buttons">
                                    <a href="lessons.php" class="btn-action btn-primary-action">
                                        <i class="fas fa-arrow-left"></i> Back to Lessons
                                    </a>
                                    <?php if (!empty($questions)): ?>
                                        <a href="quiz.php?lessonId=<?= $lessonId ?>" class="btn-action btn-success-action">
                                            <i class="fas fa-brain"></i> Take Quiz
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <?php if (!empty($questions)): ?>
                            <div class="questions-section">
                                <h3 class="section-title">
                                    <i class="fas fa-question-circle"></i>
                                    Quiz Questions (<?= count($questions) ?>)
                                </h3>
                                
                                <?php foreach ($questions as $index => $question): ?>
                                    <div class="question-item">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div class="question-text">
                                                <?= ($index + 1) ?>. <?= htmlspecialchars($question['questionText']) ?>
                                            </div>
                                            <span class="question-points"><?= $question['points'] ?> pts</span>
                                        </div>
                                        
                                        <div class="correct-answers">
                                            <strong>Correct Answer(s):</strong> 
                                            <?= htmlspecialchars(implode(', ', (array)($question['correctOptions'] ?? []))) ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="questions-section">
                                <h3 class="section-title">
                                    <i class="fas fa-question-circle"></i>
                                    Quiz Questions
                                </h3>
                                <div class="empty-questions">
                                    <i class="fas fa-inbox"></i>
                                    <h5>No Quiz Available</h5>
                                    <p>This lesson doesn't have a quiz yet. Check back later!</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- Lesson Detail End -->

        <!-- Footer Start -->
        <div class="container-fluid bg-dark text-white-50 py-4 px-lg-5">
            <div class="container px-lg-5">
                <div class="row gx-5">
                    <div class="col-md-6 text-center text-md-start">
                        <p class="mb-0">&copy; <a href="#" class="text-white">Kider</a>. All Rights Reserved.</p>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <p class="mb-0">Designed with <i class="fas fa-heart text-danger"></i> by <a href="https://htmlcodex.com" class="text-white">HTML Codex</a></p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>
</html>
