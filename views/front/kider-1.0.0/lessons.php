<?php
require_once __DIR__ . '/../../../init.php';

// Get lessons directly from model
$lessonModel = new LessonModel();
$lessons = $lessonModel->getAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Lessons - Kider Preschool</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

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
        .lesson-card {
            background: var(--card);
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 8px 25px rgba(13,38,76,0.08);
            margin-bottom: 25px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid rgba(43,124,255,0.1);
        }
        
        .lesson-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 35px rgba(13,38,76,0.12);
        }
        
        .lesson-header {
            display: flex;
            gap: 20px;
            align-items: flex-start;
            margin-bottom: 20px;
        }
        
        .lesson-thumbnail {
            width: 120px;
            height: 90px;
            object-fit: cover;
            border-radius: 10px;
            flex-shrink: 0;
        }
        
        .lesson-title {
            font-size: 22px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 8px;
        }
        
        .lesson-meta {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 15px;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 14px;
            color: #6c757d;
        }
        
        .meta-item i {
            color: #4e73df;
        }
        
        .lesson-description {
            color: #495057;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        
        .lesson-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }
        
        .btn-lesson {
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-primary-lesson {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            color: white;
            border: none;
        }
        
        .btn-primary-lesson:hover {
            background: linear-gradient(135deg, #224abe 0%, #1a3a8a 100%);
            color: white;
            transform: translateY(-2px);
        }
        
        .btn-success-lesson {
            background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
            color: white;
            border: none;
        }
        
        .btn-success-lesson:hover {
            background: linear-gradient(135deg, #13855c 0%, #0e5b4a 100%);
            color: white;
            transform: translateY(-2px);
        }
        
        .btn-outline-lesson {
            background: transparent;
            color: #4e73df;
            border: 2px solid #4e73df;
        }
        
        .btn-outline-lesson:hover {
            background: #4e73df;
            color: white;
        }
        
        .badge-age {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            color: #1565c0;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .badge-duration {
            background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);
            color: #e65100;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: #6c757d;
        }
        
        .empty-state i {
            font-size: 64px;
            color: #dee2e6;
            margin-bottom: 20px;
        }
        
        /* Ensure consistent image sizes for lessons */
        .classes-item .bg-light.rounded-circle img {
            width: 180px !important;
            height: 180px !important;
            object-fit: cover !important;
            border-radius: 50% !important;
        }
        
        .classes-item .bg-light.rounded-circle {
            width: 200px !important;
            height: 200px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        
        @media (max-width:768px) {
            .lesson-header {
                flex-direction: column;
            }
            
            .lesson-thumbnail {
                width: 100%;
                height: 200px;
            }
            
            .lesson-actions {
                flex-direction: column;
            }
            
            .btn-lesson {
                width: 100%;
                justify-content: center;
            }
            
            /* Responsive image sizes for mobile */
            .classes-item .bg-light.rounded-circle img {
                width: 140px !important;
                height: 140px !important;
            }
            
            .classes-item .bg-light.rounded-circle {
                width: 160px !important;
                height: 160px !important;
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
        <div class="container-xxl py-5 bg-primary hero-header">
            <div class="container my-5 py-5 px-lg-5">
                <div class="row g-5 py-5">
                    <div class="col-lg-12 text-center">
                        <h1 class="display-2 text-white animated slideInDown mb-4">Our Lessons</h1>
                        <p class="fs-5 fw-medium text-white mb-4">Explore our educational lessons and take interactive quizzes</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page Header End -->

        <!-- Lessons Start -->
        <div class="container-xxl py-5">
            <div class="container">
                <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                    <h1 class="mb-3">Our Lessons</h1>
                    <p>Explore our educational lessons and take interactive quizzes to enhance your learning experience.</p>
                </div>
                <div class="row g-4">
                    <?php if (!empty($lessons)): ?>
                        <?php foreach ($lessons as $index => $lesson): ?>
                            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="<?= ($index * 0.2) ?>s">
                                <div class="classes-item">
                                    <div class="bg-light rounded-circle w-75 mx-auto p-3">
                                        <?php 
                                        $imagePath = '';
                                        if (!empty($lesson['thumbnail_url'])) {
                                            // If thumbnail_url exists, ensure it has the correct base path
                                            $thumbnailUrl = $lesson['thumbnail_url'];
                                            // Remove leading slash if present and add correct base path
                                            $thumbnailUrl = ltrim($thumbnailUrl, '/');
                                            $imagePath = '/lessons_project/' . $thumbnailUrl;
                                        } else {
                                            // Use consistent default images based on lesson ID
                                            $defaultImages = ['classes-1.jpg', 'classes-2.jpg', 'classes-3.jpg', 'classes-4.jpg', 'classes-5.jpg', 'classes-6.jpg'];
                                            $imageIndex = ($lesson['lessonId'] - 1) % 6;
                                            $imagePath = 'img/' . $defaultImages[$imageIndex];
                                        }
                                        
                                        // Debug output (remove in production)
                                        error_log("Lesson ID: " . $lesson['lessonId'] . ", Thumbnail URL: " . ($lesson['thumbnail_url'] ?? 'NULL') . ", Final Path: " . $imagePath);
                                        ?>
                                        <img class="img-fluid rounded-circle" src="<?= $imagePath ?>" alt="<?= htmlspecialchars($lesson['title']) ?>" onerror="this.src='img/classes-1.jpg';">
                                    </div>
                                    <div class="bg-light rounded p-4 pt-5 mt-n5">
                                        <a class="d-block text-center h3 mt-3 mb-4" href="lesson-details.php?id=<?= $lesson['lessonId'] ?>"><?= htmlspecialchars($lesson['title']) ?></a>
                                        <div class="d-flex align-items-center justify-content-between mb-4">
                                            <div class="d-flex align-items-center">
                                                <img class="rounded-circle flex-shrink-0" src="img/user.jpg" alt="" style="width: 45px; height: 45px;">
                                                <div class="ms-3">
                                                    <h6 class="text-primary mb-1">Educator</h6>
                                                    <small><?= htmlspecialchars(ucfirst($lesson['difficulty'] ?? 'Beginner')) ?></small>
                                                </div>
                                            </div>
                                            <span class="bg-primary text-white rounded-pill py-2 px-3"><?= htmlspecialchars($lesson['duration'] ?? 30) ?> min</span>
                                        </div>
                                        <div class="row g-1">
                                            <div class="col-4">
                                                <div class="border-top border-3 border-primary pt-2">
                                                    <h6 class="text-primary mb-1">Age:</h6>
                                                    <small><?= htmlspecialchars($lesson['ageRange'] ?? 'All') ?></small>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="border-top border-3 border-success pt-2">
                                                    <h6 class="text-success mb-1">Type:</h6>
                                                    <small>Interactive</small>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="border-top border-3 border-warning pt-2">
                                                    <h6 class="text-warning mb-1">Quiz:</h6>
                                                    <small>
                                                        <?php 
                                                        $questionModel = new QuestionModel();
                                                        $questions = $questionModel->getByLesson($lesson['lessonId']);
                                                        echo !empty($questions) ? 'Available' : 'None';
                                                        ?>
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex gap-2 mt-3">
                                            <a href="lesson-details.php?id=<?= $lesson['lessonId'] ?>" class="btn btn-primary py-2 px-3 flex-fill">View Details</a>
                                            <?php if (!empty($questions)): ?>
                                                <a href="quiz.php?lessonId=<?= $lesson['lessonId'] ?>" class="btn btn-success py-2 px-3 flex-fill">Take Quiz</a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="text-center py-5">
                                <div class="wow fadeInUp" data-wow-delay="0.1s">
                                    <h3 class="mb-3">No Lessons Available</h3>
                                    <p class="text-muted">Check back soon for new educational content!</p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <!-- Lessons End -->

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
