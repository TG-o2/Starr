<?php
    session_start();
    require_once __DIR__ . '/../../../Controller/PostController.php';
    require_once __DIR__ . '/../../../Controller/BadgeHelper.php';
    require_once __DIR__ . '/../../../config/config.php';
    $postcontroller1 = new postcontroller();
    
    // Use shared BadgeHelper (thresholds: 0, 250, 750, 1500, 3000)
    
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
    
    // Google reCAPTCHA keys - replace with your actual keys
    $recaptcha_site_key = '6Lf5RSksAAAAAOnU2t9IEHm-3oO0be59c7P9eSUl';    
    $recaptcha_secret_key = 'YOUR_SECRET_KEY_HERE'; // Replace with your actual secret key
    
    $captcha_error = "";
    
    // Handle form submission with CAPTCHA verification
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
        // Check if CAPTCHA response exists
        if (empty($_POST['g-recaptcha-response'])) {
            $captcha_error = "Please complete the CAPTCHA.";
        } else {
            $recaptcha_response = $_POST['g-recaptcha-response'];
            
            // Verify CAPTCHA with Google
            $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret_key}&response={$recaptcha_response}");
            $captcha_result = json_decode($verify);
            
            if ($captcha_result->success == false) {
                $captcha_error = "CAPTCHA verification failed. Please try again.";
            } else {
                // CAPTCHA successful, proceed with deletion
                $postcontroller1->deletepost($_POST['id']);
                // Optionally redirect or show success message
                echo "<script>alert('Post deleted successfully!');</script>";
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Forum Posts</title>
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
    <link href="../lib/animate/animate.min.css" rel="stylesheet">
    <link href="../lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <!-- Template Stylesheet -->
    <link href="../assets/css/style.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Coming+Soon&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/9d17856d97.js" crossorigin="anonymous"></script>
    
    <!-- Google reCAPTCHA API -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        // Function to handle form submission with CAPTCHA
        function validateForm(form) {
            var response = grecaptcha.getResponse();
            if (response.length === 0) {
                alert("Please complete the CAPTCHA.");
                return false;
            }
            return true;
        }
        
        // Function to reset CAPTCHA
        function resetCaptcha() {
            grecaptcha.reset();
        }
        
        // Function to show confirmation dialog with CAPTCHA
        function confirmDelete(postId, subject) {
            // Create modal for CAPTCHA confirmation
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
            
            // Add modal to body if not exists
            if (!document.getElementById('deleteModal')) {
                document.body.insertAdjacentHTML('beforeend', modalHTML);
            }
            
            // Show modal
            var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
            
            // Reset CAPTCHA when modal is shown
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
                            <a href="math_threads.php" class="dropdown-item active">View threads</a>
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
                    <a href="../Education Corner/lessonDetails.php" class="nav-item nav-link">Education Corner</a>
                </div>
            </div>
        </nav>
        <!-- Navbar End -->

        <!-- Page Header End -->
        <div class="container-xxl py-5 page-header position-relative mb-5">
            <div class="container88">
                <div class="container py-5">
                    <?php if ($logged_in_user): ?>
                        <!-- Display logged-in user info -->
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
                        <!-- Show static icon if not logged in -->
                        <i class="fa-solid fa-user fa-10x" id="icon" style="color: #F9C74F; margin-left: 100px;"></i>
                        <br><br>
                        <nav aria-label="breadcrumb animated slideInDown">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#" style="font-size: 25px; color: white; margin-left: 130px; font-family: coming soon;">Guest</a></li>
                            </ol>
                        </nav>
                    <?php endif; ?>
                </div>
                <div class="container-xxl py-5">
                    <div class="container">
                        <div class="bg-light rounded">
                            <div class="row g-0">
                                <div class="col-lg-12 wow fadeIn" data-wow-delay="0.1s">
                                    <div class="h-100 d-flex flex-column justify-content-center p-5">
                                        <div class="row g-3">
                                            <div class="row g-2"> 
                                                <form action="addpost.php">
                                                    <input type="hidden" name="category" value="math">
                                                    <div class="col-6">
                                                        <button class="btn btn-primary w-100 py-3" type="submit">Create New Post</button>
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
        include_once __DIR__ . "/../../../Controller/PostController.php"; 
        $postcontroller1 = new postcontroller();
        $list = $postcontroller1->readpostsWithUserInfo(); // Use new method with user info
        
        // Filter posts based on search
        $search_query = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : '';
        
        foreach($list as $value):
            if($value['category'] == "math"):
                // Check if post matches search query
                if(empty($search_query) || strpos(strtolower($value['subject']), $search_query) !== false):
                    $post_id = $value['id'];
                    // Get user display name (username or full name)
                    $display_name = $value['username'] ?? ($value['fname'] . ' ' . $value['lname']);
                    $star_points = $value['starPoints'] ?? '0';
                    $avatar = $value['avatar'] ?? 'default-avatar.png';
        ?>
        <div class="container mb-4">
            <!-- Post Card -->
            <div class="card">
                <div class="card-body">
                    <!-- User Info Section -->
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
                    
                    <!-- Post Content -->
                    <h5 class="card-title"><?php echo htmlspecialchars($value['subject']); ?></h5>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <!-- View Thread Button -->
                        <button onclick="location.assign('thread.php?id=<?php echo $post_id; ?>')" 
                                class="btn btn-outline-primary">
                            <i class="fa fa-comments"></i> View Thread
                        </button>
                        
                        <!-- Action Buttons -->
                        <div>
                            <!-- Update Button -->
                            <a href="updatepost.php?id2=<?php echo $value['id']; ?>" 
                               class="btn btn-warning">
                                <i class="fa fa-edit"></i> Edit
                            </a>
                            
                            <!-- Delete Button with CAPTCHA confirmation -->
                            <button onclick="return confirmDelete('<?php echo $value['id']; ?>', '<?php echo addslashes($value['subject']); ?>')" 
                                    class="btn btn-danger">
                                <i class="fa fa-trash"></i> Delete
                            </button>
                            
                            <!-- Report Button -->
                            <a href="../Reports/Make-report.html?type=post&id=<?php echo $value['id']; ?>" 
                               class="btn btn-outline-danger" title="Report this post">
                                <i class="fa-solid fa-flag"></i> Report
                            </a>
                        </div>
                    </div>
                    
                    <!-- Post Metadata -->
                    <div class="mt-3 text-muted small">
                        <i class="fa fa-clock"></i> Posted on <?php echo date('F j, Y', strtotime($value['created_at'] ?? 'now')); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php 
                endif;
            endif;
        endforeach; 
        
        // If no posts found with search
        if($search_query && empty($list)): 
        ?>
        <div class="container">
            <div class="alert alert-info">
                No posts found matching "<?php echo htmlspecialchars($_GET['search']); ?>"
            </div>
        </div>
        <?php endif; ?>

        <!-- Footer Start -->
        <div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
            <!-- Your existing footer code -->
            <!-- ... -->
        </div>
        <!-- Footer End -->

        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../lib/wow/wow.min.js"></script>
    <script src="../lib/easing/easing.min.js"></script>
    <script src="../lib/waypoints/waypoints.min.js"></script>
    <script src="../lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="../assets/js/main.js"></script>
    
    <script>
        // Initialize Bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Show alert message if CAPTCHA error occurred
        <?php if (!empty($captcha_error)): ?>
        document.addEventListener('DOMContentLoaded', function() {
            alert('<?php echo $captcha_error; ?>');
        });
        <?php endif; ?>
    </script>
</body>
</html>