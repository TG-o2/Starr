<?php
    require_once "../../Controller/PostController.php";
    $postcontroller1 = new postcontroller();
    
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
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
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
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->

        <!-- Navbar Start -->
        <nav class="navbar navbar-expand-lg bg-white navbar-light sticky-top px-4 px-lg-5 py-lg-0">
            <a href="index.html" class="navbar-brand">
                <h1 class="m-0 text-primary"><img src="../kider-1.0.0/img/Starr.jpg" alt="Starr Logo" style="height: 45px; vertical-align: middle; margin-right: 8px;">Starr</h1>
            </a>
            <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav mx-auto">
                    <a href="index.html" class="nav-item nav-link">Home</a>
                    <a href="about.html" class="nav-item nav-link">About Us</a>
                    <a href="classes.html" class="nav-item nav-link active">Posts</a>
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
                <a href="" class="btn btn-primary rounded-pill px-3 d-none d-lg-block">Join Us<i class="fa fa-arrow-right ms-3"></i></a>
            </div>
        </nav>
        <!-- Navbar End -->

        <!-- Page Header End -->
        <div class="container-xxl py-5 page-header position-relative mb-5">
            <div class="container88">
                <div class="container py-5">
                    <i class="fa-solid fa-user fa-10x" id="icon" style="color: #F9C74F; margin-left: 100px;"></i>
                    <br><br>
                    <nav aria-label="breadcrumb animated slideInDown">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#" style="font-size: 25px; color: white; margin-left: 130px; font-family: coming soon;">USER_NAME</a></li>
                        </ol>
                    </nav>
                </div>
                <div class="container-xxl py-5">
                    <div class="container">
                        <div class="bg-light rounded">
                            <div class="row g-0">
                                <div class="col-lg-12 wow fadeIn" data-wow-delay="0.1s">
                                    <div class="h-100 d-flex flex-column justify-content-center p-5">
                                        <div class="row g-3">
                                            <div class="row g-2"> 
                                                <form action="http://localhost/Post%20and%20Comments/View/FrontOfiice/addpost.php?">
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
        include_once "../../Controller/PostController.php"; 
        $postcontroller1 = new postcontroller();
        $list = $postcontroller1->readposts();
        
        // Filter posts based on search
        $search_query = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : '';
        
        foreach($list as $value):
            if($value['category'] == "math"):
                // Check if post matches search query
                if(empty($search_query) || strpos(strtolower($value['subject']), $search_query) !== false):
                    $post_id = $value['id'];
        ?>
        <div class="container mb-4">
            <!-- Post Card -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($value['subject']); ?></h5>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <!-- View Thread Button -->
                        <button onclick="location.assign('http://localhost/Post%20and%20Comments/View/FrontOfiice/thread.php?id=<?php echo $post_id; ?>')" 
                                class="btn btn-outline-primary">
                            <i class="fa fa-comments"></i> View Thread
                        </button>
                        
                        <!-- Action Buttons -->
                        <div>
                            <!-- Update Button -->
                            <a href="http://localhost/Post%20and%20Comments/View/FrontOfiice/updatepost.php?id2=<?php echo $value['id']; ?>" 
                               class="btn btn-warning">
                                <i class="fa fa-edit"></i> Edit
                            </a>
                            
                            <!-- Delete Button with CAPTCHA confirmation -->
                            <button onclick="return confirmDelete('<?php echo $value['id']; ?>', '<?php echo addslashes($value['subject']); ?>')" 
                                    class="btn btn-danger">
                                <i class="fa fa-trash"></i> Delete
                            </button>
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
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    
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