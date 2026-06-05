<?php
    session_start();
    require_once __DIR__ . '/../../../Controller/PostController.php';
    require_once __DIR__ . '/../../../Controller/BadgeHelper.php';
    require_once __DIR__ . '/../../../config/config.php';
    $postcontroller1 = new postcontroller();
    
    // Get category from URL parameter, default to 'all'
    $current_category = isset($_GET['category']) ? $_GET['category'] : 'all';
    
    // Category configurations
    $categories = [
        'all' => ['name' => 'All Posts', 'icon' => 'fa-comments', 'color' => '#667eea'],
        'math' => ['name' => 'School Subjects', 'icon' => 'fa-graduation-cap', 'color' => '#90be6d'],
        'study' => ['name' => 'Study Resources', 'icon' => 'fa-book', 'color' => '#f9c74f'],
        'student' => ['name' => 'Student Life & Support', 'icon' => 'fa-users', 'color' => '#f8961e'],
        'projects' => ['name' => 'Projects & Activities', 'icon' => 'fa-project-diagram', 'color' => '#f3722c']
    ];
    
    $category_info = isset($categories[$current_category]) ? $categories[$current_category] : $categories['all'];
    
    // Get current user info from session
    $logged_in_user = null;
    if (isset($_SESSION['user_id'])) {
        $pdo = Config::getConnexion();
        $stmt = $pdo->prepare("SELECT u.user_id, u.username, u.fname, u.lname, u.avatar, COALESCE(sp.total_points, 0) as starPoints 
                               FROM user u 
                               LEFT JOIN STARR_POINTS sp ON u.user_id = sp.starr_id 
                               WHERE u.user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $logged_in_user = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Google reCAPTCHA keys
    $recaptcha_site_key = '6Lf5RSksAAAAAOnU2t9IEHm-3oO0be59c7P9eSUl';    
    $recaptcha_secret_key = 'YOUR_SECRET_KEY_HERE';
    
    $captcha_error = "";
    
    // Handle form submission with CAPTCHA verification
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
        if (empty($_POST['g-recaptcha-response'])) {
            $captcha_error = "Please complete the CAPTCHA.";
        } else {
            $recaptcha_response = $_POST['g-recaptcha-response'];
            $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret_key}&response={$recaptcha_response}");
            $captcha_result = json_decode($verify);
            
            if ($captcha_result->success == false) {
                $captcha_error = "CAPTCHA verification failed. Please try again.";
            } else {
                $postcontroller1->deletepost($_POST['id']);
                echo "<script>alert('Post deleted successfully!');</script>";
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo htmlspecialchars($category_info['name']); ?> - Forum Posts</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">
<meta charset="utf-8">
    <title>Add Post | Starr</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="../img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Handlee&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="../lib/animate/animate.min.css" rel="stylesheet">
    <link href="../lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">  
    <!-- Template Stylesheet -->
    <link href="../assets/css/style.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <!-- Template Stylesheet -->
    <link href="../assets/css/style.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/9d17856d97.js" crossorigin="anonymous"></script>
    
    <!-- Google reCAPTCHA API -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        function validateForm(form) {
            var response = grecaptcha.getResponse();
            if (response.length === 0) {
                alert("Please complete the CAPTCHA.");
                return false;
            }
            return true;
        }
        
        function resetCaptcha() {
            grecaptcha.reset();
        }
        
        function confirmDelete(postId, subject) {
            var modalHTML = `
                <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete the post: "<strong>${subject}</strong>"?</p>
                                <form id="deleteForm" method="POST" onsubmit="return validateForm(this)">
                                    <input type="hidden" name="id" value="${postId}">
                                    <div class="mb-3">
                                        <div class="g-recaptcha" data-sitekey="<?php echo $recaptcha_site_key; ?>"></div>
                                    </div>
                                    <?php if (!empty($captcha_error)): ?>
                                        <div class="alert alert-danger"><?php echo $captcha_error; ?></div>
                                    <?php endif; ?>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-danger">Delete Post</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            if (!document.getElementById('deleteModal')) {
                document.body.insertAdjacentHTML('beforeend', modalHTML);
            }
            
            var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
            
            deleteModal._element.addEventListener('shown.bs.modal', function () {
                grecaptcha.reset();
            });
            
            return false;
        }
    </script>
</head>
<body>
    <div class="container-xxl bg-white p-0">
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
                        <a href="#" class="nav-link dropdown-toggle active" data-bs-toggle="dropdown">Posts</a>
                        <div class="dropdown-menu rounded-0 rounded-bottom border-0 m-0">
                            <a href="addpost.php" class="dropdown-item ">Create a post</a>
                            <a href="posts.html" class="dropdown-item active">View threads</a>
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
                    <a href="../Education Corner/lessonDisplay_direct.php" class="nav-item nav-link">Education Corner</a>
                    <a href="../User-signup/viewProfile.php" class="nav-item nav-link">View Profile</a>
                </div>
            </div>
        </nav>
        <!-- Navbar End -->

        <!-- Page Header -->
        <div class="container-xxl py-5 page-header position-relative mb-5">
            <div class="container88">
                <div class="container py-5">
                    <?php if ($logged_in_user): ?>
                        <div class="d-flex align-items-center gap-4">
                            <img src="../assets/img/userProfile/<?php echo htmlspecialchars($logged_in_user['avatar'] ?? 'default-avatar.png'); ?>" 
                                 alt="<?php echo htmlspecialchars($logged_in_user['username']); ?>"
                                 class="rounded-circle"
                                 style="width: 160px; height: 160px; object-fit: cover; border: 5px solid #F9C74F; flex-shrink: 0;"
                                 onerror="this.src='../assets/img/userProfile/default-avatar.png';">
                            <div>
                                <h1 class="text-white" style="font-family: coming soon; font-size: 56px; margin: 0; font-weight: bold; line-height: 1.2;">
                                    <?php echo htmlspecialchars($logged_in_user['username'] ?? ($logged_in_user['fname'] . ' ' . $logged_in_user['lname'])); ?>
                                </h1>
                                <p class="text-warning" style="font-size: 22px; margin: 12px 0; font-weight: 600;">
                                    <i class="fas fa-star"></i> <?php echo number_format($logged_in_user['starPoints'] ?? 0); ?> Star Points
                                </p>
                                <div class="mt-1">
                                    <?php echo BadgeHelper::renderBadge((int)($logged_in_user['starPoints'] ?? 0), 'badge-on-dark'); ?>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <i class="fa-solid <?php echo $category_info['icon']; ?> fa-10x" id="icon" style="color: <?php echo $category_info['color']; ?>; margin-left: 100px;"></i>
                        <br><br>
                        <nav aria-label="breadcrumb animated slideInDown">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#" style="font-size: 25px; color: white; margin-left: 130px; font-family: coming soon;"><?php echo htmlspecialchars($category_info['name']); ?></a></li>
                            </ol>
                        </nav>
                    <?php endif; ?>
                </div>
                
                <!-- Category Filter + Create Post -->
                <div class="container-xxl py-5">
                    <div class="container">
                        <div class="bg-light rounded">
                            <div class="row g-0">
                                <div class="col-lg-12 wow fadeIn" data-wow-delay="0.1s">
                                    <div class="h-100 d-flex flex-column justify-content-center p-5">
                                        <!-- Category Filter Pills -->
                                        <div class="mb-4 d-flex flex-wrap gap-2">
                                            <a href="threads.php?category=all" class="btn btn-<?php echo $current_category === 'all' ? 'primary' : 'outline-primary'; ?>">
                                                <i class="fa fa-comments"></i> All
                                            </a>
                                            <a href="threads.php?category=math" class="btn btn-<?php echo $current_category === 'math' ? 'primary' : 'outline-primary'; ?>">
                                                <i class="fa fa-graduation-cap"></i> School Subjects
                                            </a>
                                            <a href="threads.php?category=study" class="btn btn-<?php echo $current_category === 'study' ? 'primary' : 'outline-primary'; ?>">
                                                <i class="fa fa-book"></i> Study Resources
                                            </a>
                                            <a href="threads.php?category=student" class="btn btn-<?php echo $current_category === 'student' ? 'primary' : 'outline-primary'; ?>">
                                                <i class="fa fa-users"></i> Student Life
                                            </a>
                                            <a href="threads.php?category=projects" class="btn btn-<?php echo $current_category === 'projects' ? 'primary' : 'outline-primary'; ?>">
                                                <i class="fa fa-project-diagram"></i> Projects
                                            </a>
                                        </div>
                                        
                                        <div class="row g-3">
                                            <div class="row g-2"> 
                                                <form action="addpost.php" method="GET">
                                                    <?php if ($current_category !== 'all'): ?>
                                                        <input type="hidden" name="category" value="<?php echo htmlspecialchars($current_category); ?>">
                                                    <?php endif; ?>
                                                    <div class="col-6">
                                                        <button class="btn btn-primary w-100 py-3" type="submit">
                                                            <i class="fa fa-plus"></i> Create New Post
                                                        </button>
                                                        <br><br>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page Header End -->

        <!-- Search Form -->
        <div class="container mb-4">
            <div class="bg-light rounded p-4">
                <form method="GET" action="">
                    <input type="hidden" name="category" value="<?php echo htmlspecialchars($current_category); ?>">
                    <div class="row g-3">
                        <div class="col-md-10">
                            <div class="form-floating">
                                <input id="search" name="search" type="text" class="form-control" placeholder="Search posts..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                                <label for="search">Search Posts</label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary w-100 h-100" type="submit">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Posts Display -->
        <?php 
        $list = $postcontroller1->readpostsWithUserInfo();
        $search_query = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : '';
        $posts_found = false;
        
        foreach($list as $value):
            // Filter by category (if not 'all')
            $matches_category = ($current_category === 'all') || ($value['category'] == $current_category);
            
            if($matches_category):
                // Check if post matches search query
                if(empty($search_query) || strpos(strtolower($value['subjects']), $search_query) !== false):
                    $posts_found = true;
                    $post_id = $value['id'];
                    $display_name = $value['username'] ?? ($value['fname'] . ' ' . $value['lname']);
                    $star_points = $value['starPoints'] ?? '0';
                    $avatar = $value['avatar'] ?? 'default-avatar.png';
        ?>
        <div class="container mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <img src="../assets/img/userProfile/<?php echo htmlspecialchars($avatar); ?>" 
                             alt="<?php echo htmlspecialchars($display_name); ?>" 
                             class="rounded-circle me-3" 
                             style="width: 50px; height: 50px; object-fit: cover;"
                             onerror="this.src='../assets/img/userProfile/default-avatar.png';">
                        <div>
                            <h6 class="mb-0 d-flex align-items-center gap-2">
                                <?php echo htmlspecialchars($display_name); ?>
                                <?php echo BadgeHelper::renderBadge((int)$star_points); ?>
                                <a href="../Reports/Make-report.html?type=user&id=<?php echo (int)$value['user_id']; ?>" 
                                   class="btn btn-xs btn-outline-danger ms-2" style="padding:2px 8px; font-size:0.75rem;" title="Report this user">
                                    <i class="fa-solid fa-flag"></i> Report User
                                </a>
                            </h6>
                            <small class="text-warning">
                                <i class="fas fa-star"></i> <?php echo number_format((int)$star_points); ?> Stars
                            </small>
                        </div>
                    </div>
                    
                    <h5 class="card-title"><?php echo htmlspecialchars($value['subjects']); ?></h5>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <button onclick="location.assign('thread.php?id=<?php echo $post_id; ?>')" 
                                class="btn btn-outline-primary">
                            <i class="fa fa-comments"></i> View Thread
                        </button>
                        
                        <div>
                            <a href="updatepost.php?id2=<?php echo $value['id']; ?>" 
                               class="btn btn-warning">
                                <i class="fa fa-edit"></i> Edit
                            </a>
                            
                            <button onclick="return confirmDelete('<?php echo $value['id']; ?>', '<?php echo addslashes($value['subjects']); ?>')" 
                                    class="btn btn-danger">
                                <i class="fa fa-trash"></i> Delete
                            </button>
                            
                            <a href="../Reports/Make-report.html?type=post&id=<?php echo $value['id']; ?>" 
                               class="btn btn-outline-danger" title="Report this post">
                                <i class="fa-solid fa-flag"></i> Report
                            </a>
                        </div>
                    </div>
                    
                    <div class="mt-3 text-muted small">
                        <i class="fa fa-clock"></i> Posted on <?php echo date('F j, Y', strtotime($value['created_at'] ?? 'now')); ?>
                        <?php if ($value['category']): ?>
                            <span class="badge bg-secondary ms-2"><?php echo htmlspecialchars(ucfirst($value['category'])); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php 
                endif;
            endif;
        endforeach; 
        
        if (!$posts_found): 
        ?>
        <div class="container">
            <div class="alert alert-info text-center">
                <i class="fa fa-info-circle fa-3x mb-3"></i>
                <h5>No posts found</h5>
                <p><?php echo !empty($search_query) ? 'Try adjusting your search terms.' : 'Be the first to create a post in this category!'; ?></p>
                <a href="addpost.php<?php echo $current_category !== 'all' ? '?category=' . htmlspecialchars($current_category) : ''; ?>" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Create Post
                </a>
            </div>
        </div>
        <?php endif; ?>

        <!-- Footer Start -->
        <div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
            <div class="container py-5">
                <div class="row g-5">
                    <div class="col-lg-3 col-md-6">
                        <h3 class="text-white mb-4">Get In Touch</h3>
                        <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>123 Street, New York, USA</p>
                        <div class="d-flex pt-2">
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-youtube"></i></a>
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h3 class="text-white mb-4">Quick Links</h3>
                        <a class="btn btn-link text-white-50" href="">About Us</a>
                        <a class="btn btn-link text-white-50" href="">Contact Us</a>
                        <a class="btn btn-link text-white-50" href="">Privacy Policy</a>
                        <a class="btn btn-link text-white-50" href="">Terms & Condition</a>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h3 class="text-white mb-4">Categories</h3>
                        <a class="btn btn-link text-white-50" href="threads.php?category=math">School Subjects</a>
                        <a class="btn btn-link text-white-50" href="threads.php?category=study">Study Resources</a>
                        <a class="btn btn-link text-white-50" href="threads.php?category=student">Student Life</a>
                        <a class="btn btn-link text-white-50" href="threads.php?category=projects">Projects</a>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h3 class="text-white mb-4">Newsletter</h3>
                        <p>Stay updated with our latest news.</p>
                        <div class="position-relative mx-auto" style="max-width: 400px;">
                            <input class="form-control bg-transparent w-100 py-3 ps-4 pe-5" type="text" placeholder="Your email">
                            <button type="button" class="btn btn-primary py-2 position-absolute top-0 end-0 mt-2 me-2">SignUp</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->

        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../lib/wow/wow.min.js"></script>
    <script src="../lib/easing/easing.min.js"></script>
    <script src="../lib/waypoints/waypoints.min.js"></script>
    <script src="../lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="../assets/js/main.js"></script>
    
    <script>
        <?php if (!empty($captcha_error)): ?>
        document.addEventListener('DOMContentLoaded', function() {
            alert('<?php echo $captcha_error; ?>');
        });
        <?php endif; ?>
    </script>
</body>
</html>
