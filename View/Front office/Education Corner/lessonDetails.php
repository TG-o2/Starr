<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo isset($lesson) && is_array($lesson) ? htmlspecialchars($lesson['title']) : 'Lesson Details'; ?> | Starr Education Corner</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="../logo.jpeg" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Handlee&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="../assets/lib/animate/animate.min.css" rel="stylesheet">
    <link href="../assets/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Template Stylesheet -->
    <link href="../assets/css/style.css" rel="stylesheet">

    <style>
        .lesson-header-card {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        .lesson-title {
            color: #2c3e50;
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 20px;
        }
        .lesson-meta {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }
        .badge-custom {
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 1rem;
        }
        .badge-age {
            background: #e3f2fd;
            color: #1976d2;
        }
        .badge-duration {
            background: #fff3e0;
            color: #f57c00;
        }
        .lesson-image-container {
            margin-top: 25px;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .lesson-image-container img {
            width: 100%;
            height: auto;
            max-height: 400px;
            object-fit: cover;
        }
        .lesson-description-card {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        .lesson-description-card h2 {
            color: #FE6B8B;
            font-size: 2rem;
            margin-bottom: 20px;
        }
        .lesson-description-card p {
            color: #555;
            font-size: 1.1rem;
            line-height: 1.8;
            white-space: pre-line;
        }
        .questions-card {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }
        .questions-card h2 {
            color: #FE6B8B;
            font-size: 2rem;
            margin-bottom: 25px;
        }
        .question-item {
            background: #f8f9fa;
            border-left: 4px solid #FE6B8B;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 20px;
        }
        .question-number {
            color: #FE6B8B;
            font-weight: 700;
            font-size: 1.2rem;
            margin-bottom: 10px;
        }
        .question-text {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 15px;
        }
        .option-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .option-list li {
            padding: 10px 15px;
            background: white;
            margin-bottom: 8px;
            border-radius: 8px;
            border: 2px solid #e9ecef;
        }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }
        .empty-state i {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 20px;
        }
        .empty-state p {
            font-size: 1.1rem;
            color: #666;
        }
        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        .btn-quiz {
            background: linear-gradient(135deg, #FE6B8B 0%, #FF8E53 100%);
            color: white;
            padding: 15px 35px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            border: none;
        }
        .btn-quiz:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(254, 107, 139, 0.3);
            color: white;
        }
        @media (max-width: 768px) {
            .lesson-title {
                font-size: 1.8rem;
            }
            .action-buttons {
                flex-direction: column;
            }
            .action-buttons a, .action-buttons button {
                width: 100%;
                text-align: center;
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
            <a class="navbar-brand">
                <h1 class="m-0 text-primary"> <img src="../logo.jpeg" alt="Starr Logo" style="height: 60px; vertical-align: middle; margin-right: 8px;">
            Starr</h1>
            </a>
            <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav mx-auto">
                    <a href="../index.html" class="nav-item nav-link">Home</a>
                    
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Posts</a>
                        <div class="dropdown-menu rounded-0 rounded-bottom border-0 m-0">
                            <a href="../posts and comments/addpost.php" class="dropdown-item">Create a post</a>
                            <a href="../posts and comments/posts.html" class="dropdown-item">View threads</a>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Report</a>
                        <div class="dropdown-menu rounded-0 rounded-bottom border-0 m-0">
                            <a href="../Reports/Make-report.html" class="dropdown-item">Make a report</a>
                            <a href="../Reports/Messages.php" class="dropdown-item">Check response</a>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Starrs</a>
                        <div class="dropdown-menu rounded-0 rounded-bottom border-0 m-0">
                            <a href="../point system/my-points.php" class="dropdown-item">My Starrs</a>
                            <a href="../point system/badges.php" class="dropdown-item">Badges</a>
                        </div>
                    </div>
                    <a href="../News/gestionnews.php" class="nav-item nav-link">Articles</a>
                    <a href="lessonDetails.php" class="nav-item nav-link active">Education Corner</a>
                    <a href="../User-signup/viewProfile.php" class="nav-item nav-link">View Profile</a>
                </div>
            </div>
        </nav>
        <!-- Navbar End -->

        <!-- Page Header Start -->
        <div class="container-xxl py-5 page-header position-relative mb-5">
            <div class="container py-5">
                <h1 class="display-2 text-white animated slideInDown mb-4">Lesson Details</h1>
                <nav aria-label="breadcrumb animated slideInDown">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="../index.html">Home</a></li>
                        <li class="breadcrumb-item"><a href="lessonDisplay_direct.php">Lessons</a></li>
                        <li class="breadcrumb-item text-white active" aria-current="page"><?php echo isset($lesson) && is_array($lesson) ? htmlspecialchars($lesson['title']) : 'Lesson Details'; ?></li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header End -->

        <!-- Lesson Details Start -->
        <div class="container-xxl py-5">
            <div class="container">
                <?php if (isset($lesson) && is_array($lesson)): ?>
                <div class="lesson-header-card">
                    <a href="lessonDisplay_direct.php" class="btn btn-outline-secondary mb-3">
                        <i class="fas fa-arrow-left"></i> Back to All Lessons
                    </a>
                    <h1 class="lesson-title"><?php echo htmlspecialchars($lesson['title']); ?></h1>

                    <div class="lesson-meta">
                        <span class="badge-custom badge-age">
                            <i class="fas fa-child"></i> <?php echo htmlspecialchars($lesson['ageRange']); ?>
                        </span>
                        <span class="badge-custom badge-duration">
                            <i class="fas fa-clock"></i> <?php echo htmlspecialchars($lesson['duration']); ?> minutes
                        </span>
                    </div>

                    <?php if (!empty($lesson['image'])): ?>
                        <div class="lesson-image-container">
                            <img src="<?php echo htmlspecialchars($lesson['image']); ?>" alt="<?php echo htmlspecialchars($lesson['title']); ?>">
                        </div>
                    <?php endif; ?>
                </div>

                <div class="lesson-description-card">
                    <h2><i class="fas fa-book-open"></i> Lesson Description</h2>
                    <p><?php echo htmlspecialchars($lesson['description']); ?></p>
                </div>

                <div class="questions-card">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2><i class="fas fa-question-circle"></i> Quiz Questions</h2>
                        <?php if (!empty($questions)): ?>
                            <span class="badge bg-primary" style="font-size: 1rem; padding: 10px 20px;"><?php echo count($questions); ?> Questions</span>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($questions)): ?>
                        <?php foreach ($questions as $index => $question): ?>
                            <div class="question-item">
                                <div class="question-number">Question <?php echo $index + 1; ?></div>
                                <div class="question-text"><?php echo htmlspecialchars($question['questionText']); ?></div>
                                <ul class="option-list">
                                    <?php if (!empty($question['option1'])): ?>
                                        <li><i class="fas fa-circle" style="font-size: 0.4rem; color: #FE6B8B; margin-right: 10px;"></i><?php echo htmlspecialchars($question['option1']); ?></li>
                                    <?php endif; ?>
                                    <?php if (!empty($question['option2'])): ?>
                                        <li><i class="fas fa-circle" style="font-size: 0.4rem; color: #FE6B8B; margin-right: 10px;"></i><?php echo htmlspecialchars($question['option2']); ?></li>
                                    <?php endif; ?>
                                    <?php if (!empty($question['option3'])): ?>
                                        <li><i class="fas fa-circle" style="font-size: 0.4rem; color: #FE6B8B; margin-right: 10px;"></i><?php echo htmlspecialchars($question['option3']); ?></li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        <?php endforeach; ?>
                        
                        <div class="action-buttons">
                            <a href="lessonQuiz_direct.php?lessonId=<?php echo htmlspecialchars($lesson['lessonId']); ?>" class="btn-quiz">
                                <i class="fas fa-play-circle"></i> Start Quiz Now
                            </a>
                            <a href="lessonDisplay_direct.php" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-arrow-left"></i> Back to Lessons
                            </a>
                            <a href="../Reports/Make-report.html?type=lesson&id=<?php echo (int)$lesson['lessonId']; ?>" class="btn btn-outline-danger btn-lg" title="Report this lesson">
                                <i class="fa-solid fa-flag"></i> Report Lesson
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <p>No questions available for this lesson yet.</p>
                            <a href="lessonDisplay_direct.php" class="btn btn-primary mt-3">Back to All Lessons</a>
                        </div>
                    <?php endif; ?>
                </div>
                <?php else: ?>
                <div class="alert alert-danger text-center py-5">
                    <i class="fas fa-exclamation-circle fa-3x mb-3"></i>
                    <h4>Lesson Not Found</h4>
                    <p>The lesson you are looking for could not be found.</p>
                    <a href="lessonDisplay_direct.php" class="btn btn-primary mt-3">Back to Lessons</a>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <!-- Lesson Details End -->

        <!-- Footer Start -->
        <div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
            <div class="container py-5">
                <div class="row g-5">
                    <div class="col-lg-3 col-md-6">
                        <h3 class="text-white mb-4">Get In Touch</h3>
                        <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>Starr Education Center</p>
                        <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>+012 345 67890</p>
                        <p class="mb-2"><i class="fa fa-envelope me-3"></i>info@starr.edu</p>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h3 class="text-white mb-4">Quick Links</h3>
                        <a class="btn btn-link text-white-50" href="../index.html">Home</a>
                        <a class="btn btn-link text-white-50" href="../about.html">About Us</a>
                        <a class="btn btn-link text-white-50" href="lessonDisplay_direct.php">Lessons</a>
                        <a class="btn btn-link text-white-50" href="../contact.html">Contact Us</a>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h3 class="text-white mb-4">Newsletter</h3>
                        <p>Stay updated with our latest lessons!</p>
                        <div class="position-relative mx-auto" style="max-width: 400px;">
                            <input class="form-control bg-transparent w-100 py-3 ps-4 pe-5" type="text" placeholder="Your email">
                            <button type="button" class="btn btn-primary py-2 position-absolute top-0 end-0 mt-2 me-2">Subscribe</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="copyright">
                    <div class="row">
                        <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                            &copy; <a class="border-bottom" href="#">Starr Education Corner</a>, All Right Reserved.
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->

        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/lib/wow/wow.min.js"></script>
    <script src="../assets/lib/easing/easing.min.js"></script>
    <script src="../assets/lib/waypoints/waypoints.min.js"></script>
    <script src="../assets/lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="../assets/js/main.js"></script>
        <script>
            // Track lesson view for popularity stats
            document.addEventListener('DOMContentLoaded', function() {
                var lid = <?php echo isset($lesson['lessonId']) ? (int)$lesson['lessonId'] : '0'; ?>;
                if (lid > 0) {
                    fetch('../../../api/track_view.php?type=lesson&id=' + lid).catch(function(){});
                }
            });
        </script>
</body>
</html>
