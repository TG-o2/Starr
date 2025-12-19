<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Education Corner | Starr</title>
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
        .lessons-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
            margin-bottom: 50px;
        }
        .lesson-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .lesson-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }
        .lesson-image {
            width: 100%;
            height: 220px;
            background: linear-gradient(135deg, #FE6B8B 0%, #FF8E53 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 4rem;
        }
        .lesson-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .lesson-info {
            padding: 25px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .lesson-title {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: #2c3e50;
        }
        .lesson-meta {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }
        .badge-custom {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .badge-age {
            background: #e3f2fd;
            color: #1976d2;
        }
        .badge-duration {
            background: #fff3e0;
            color: #f57c00;
        }
        .lesson-description {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 20px;
            flex: 1;
            line-height: 1.6;
        }
        .btn-view-lesson {
            background: #FE6B8B;
            color: white;
            padding: 12px 25px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            text-align: center;
            transition: all 0.3s ease;
            display: inline-block;
        }
        .btn-view-lesson:hover {
            background: #FF8E53;
            color: white;
            transform: scale(1.05);
        }
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .empty-state i {
            font-size: 5rem;
            color: #ddd;
            margin-bottom: 25px;
        }
        .empty-state p {
            font-size: 1.2rem;
            color: #666;
        }
        @media (max-width: 768px) {
            .lessons-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="container-xxl bg-white p-0">
        

        <!-- Navbar Start -->
        <nav class="navbar navbar-expand-lg bg-white navbar-light sticky-top px-4 px-lg-5 py-lg-0">
            <a class="navbar-brand">
                <h1 class="m-0 text-primary">
                    <img src="../logo.jpeg" alt="Starr Logo" style="height: 60px; vertical-align: middle; margin-right: 8px;">
                    Starr
                </h1>
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
                            <a href="../posts and comments/posts.html" class="dropdown-item">View posts</a>
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
                    <a href="lessonDisplay_direct.php" class="nav-item nav-link active">Education Corner</a>
                    <a href="../User-signup/viewProfile.php" class="nav-item nav-link">Profile</a>

                </div>
            </div>
        </nav>
        <!-- Navbar End -->

        <!-- Page Header Start -->
        <div class="container-xxl py-5 page-header position-relative mb-5">
            <div class="container py-5">
                <h1 class="display-2 text-white animated slideInDown mb-4">Education Corner</h1>
                <nav aria-label="breadcrumb animated slideInDown">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="../index.html">Home</a></li>
                        <li class="breadcrumb-item text-white active" aria-current="page">Lessons</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header End -->

        <!-- Lessons Section Start -->
        <div class="container-xxl py-5">
            <div class="container">
                <?php if (!empty($lessons)): ?>
                    <div class="text-center mb-5">
                        <h6 class="text-primary text-uppercase">Explore & Learn</h6>
                        <h1 class="mb-4">Available Lessons</h1>
                    </div>
                    <div class="lessons-grid">
                        <?php foreach ($lessons as $lesson): ?>
                            <div class="lesson-card">
                                <div class="lesson-image">
                                    <?php if (!empty($lesson['image'])): ?>
                                        <?php 
                                            $imageName = $lesson['image'];
                                            // If stored path includes directory, extract just the filename
                                            if (strpos($imageName, '/') !== false) {
                                                $imageName = basename($imageName);
                                            }
                                        ?>
                                        <img src="../assets/uploads/lessons/<?php echo urlencode($imageName); ?>" alt="<?php echo htmlspecialchars($lesson['title']); ?>">
                                    <?php else: ?>
                                        <i class="fas fa-book-open"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="lesson-info">
                                    <h3 class="lesson-title"><?php echo htmlspecialchars($lesson['title']); ?></h3>
                                    <div class="lesson-meta">
                                        <span class="badge-custom badge-age">
                                            <i class="fas fa-child"></i> <?php echo htmlspecialchars($lesson['ageRange']); ?>
                                        </span>
                                        <span class="badge-custom badge-duration">
                                            <i class="fas fa-clock"></i> <?php echo htmlspecialchars($lesson['duration']); ?> min
                                        </span>
                                    </div>
                                    <p class="lesson-description"><?php echo htmlspecialchars($lesson['description']); ?></p>
                                    <a href="lessonDetails_direct.php?lessonId=<?php echo htmlspecialchars($lesson['lessonId']); ?>" class="btn-view-lesson">
                                        <i class="fas fa-arrow-right"></i> View Details
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-graduation-cap"></i>
                        <p>No lessons available yet. Check back soon for exciting new content!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <!-- Lessons Section End -->

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
                        <p>Stay updated with our latest lessons and educational content!</p>
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
</body>
</html>
