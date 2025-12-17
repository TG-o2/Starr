<?php
    require_once "../../Controller/messagescontroller.php";
    $messagescontroller1 = new messagescontroller();
    
    // Google reCAPTCHA keys - replace with your actual keys
    $recaptcha_site_key = '6Lf5RSksAAAAAOnU2t9IEHm-3oO0be59c7P9eSUl';    
    $recaptcha_secret_key = 'YOUR_SECRET_KEY_HERE'; // Replace with your actual secret key
    
    $captcha_error = "";
    
    // Handle form submission with CAPTCHA verification
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id2'])) {
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
                $messagescontroller1->deletemessages($_POST['id2']);
                // Optionally redirect or show success message
                echo "<script>alert('Message deleted successfully!');</script>";
                // Refresh page to show updated list
                echo "<script>setTimeout(function(){ window.location.reload(); }, 500);</script>";
            }
        }
    }
    
    // Get the post ID from URL
    $post_id = isset($_GET['id']) ? $_GET['id'] : 0;
    
    // Pagination settings
    $messages_per_page = 3;
    $current_page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $offset = ($current_page - 1) * $messages_per_page;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Forum Thread - Messages</title>
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
    <style>
        .load-more-container {
            text-align: center;
            margin: 20px 0;
        }
        
        .load-more-btn {
            padding: 10px 30px;
            font-size: 16px;
        }
        
        .messages-count {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
        }
        
        .hidden-message {
            display: none;
        }
    </style>
    <script>
        // Function to handle CAPTCHA validation
        function validateForm(form) {
            var response = grecaptcha.getResponse();
            if (response.length === 0) {
                alert("Please complete the CAPTCHA.");
                return false;
            }
            return true;
        }
        
        // Function to show confirmation dialog with CAPTCHA for message deletion
        function confirmDelete(messageId, content) {
            // Truncate content if too long
            var displayContent = content.length > 50 ? content.substring(0, 50) + '...' : content;
            
            // Create modal for CAPTCHA confirmation
            var modalHTML = `
                <div class="modal fade" id="deleteMessageModal" tabindex="-1" aria-labelledby="deleteMessageModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteMessageModalLabel">Confirm Message Deletion</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete this message?</p>
                                <div class="alert alert-info">
                                    <strong>Preview:</strong> "${displayContent}"
                                </div>
                                <form id="deleteMessageForm" method="POST" onsubmit="return validateForm(this)">
                                    <input type="hidden" name="id2" value="${messageId}">
                                    <div class="mb-3">
                                        <div class="g-recaptcha" data-sitekey="<?php echo $recaptcha_site_key; ?>"></div>
                                    </div>
                                    <?php if (!empty($captcha_error)): ?>
                                        <div class="alert alert-danger"><?php echo $captcha_error; ?></div>
                                    <?php endif; ?>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-danger">Delete Message</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            // Add modal to body if not exists
            if (!document.getElementById('deleteMessageModal')) {
                document.body.insertAdjacentHTML('beforeend', modalHTML);
            }
            
            // Show modal
            var deleteModal = new bootstrap.Modal(document.getElementById('deleteMessageModal'));
            deleteModal.show();
            
            // Reset CAPTCHA when modal is shown
            deleteModal._element.addEventListener('shown.bs.modal', function () {
                grecaptcha.reset();
            });
            
            return false;
        }
        
        // Like function
        function toggleLike(heart) {
            if (heart.style.color === 'red') {
                heart.style.color = '#FFD43B';
                heart.style.transform = 'scale(1)';
            } else {
                heart.style.color = 'red';
                heart.style.transform = 'scale(1.2)';
                
                // Reset animation after click
                setTimeout(() => {
                    heart.style.transform = 'scale(1)';
                }, 300);
            }
        }
        
        // Load More functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Hide all messages beyond the first 3
            var allMessages = document.querySelectorAll('.message-item');
            var messagesToShow = 3;
            
            // If we're on page 1 and there are more than 3 messages, hide the extras
            if (allMessages.length > messagesToShow) {
                for (var i = messagesToShow; i < allMessages.length; i++) {
                    allMessages[i].classList.add('hidden-message');
                }
                
                // Show load more button if hidden messages exist
                if (allMessages.length > messagesToShow) {
                    var loadMoreBtn = document.getElementById('loadMoreBtn');
                    if (loadMoreBtn) {
                        loadMoreBtn.style.display = 'block';
                    }
                }
            }
            
            // Update message count
            var totalMessages = <?php 
                include_once "../../Controller/messagescontroller.php"; 
                $messagescontroller1 = new messagescontroller();
                $list_mes = $messagescontroller1->readmessages();
                $count = 0;
                foreach($list_mes as $value) {
                    if($value['post_id'] == $post_id) {
                        $count++;
                    }
                }
                echo $count;
            ?>;
            
            var shownMessages = Math.min(messagesToShow, totalMessages);
            var messageCountEl = document.getElementById('messageCount');
            if (messageCountEl) {
                messageCountEl.innerHTML = 'Showing ' + shownMessages + ' of ' + totalMessages + ' messages';
            }
        });
        
        // Load more messages function
        function loadMoreMessages() {
            var hiddenMessages = document.querySelectorAll('.message-item.hidden-message');
            var messagesToLoad = 3;
            var loadedCount = 0;
            
            // Show next 3 hidden messages
            for (var i = 0; i < hiddenMessages.length && loadedCount < messagesToLoad; i++) {
                hiddenMessages[i].classList.remove('hidden-message');
                loadedCount++;
            }
            
            // Update message count
            var visibleMessages = document.querySelectorAll('.message-item:not(.hidden-message)');
            var totalMessages = <?php 
                $count = 0;
                foreach($list_mes as $value) {
                    if($value['post_id'] == $post_id) {
                        $count++;
                    }
                }
                echo $count;
            ?>;
            
            var messageCountEl = document.getElementById('messageCount');
            if (messageCountEl) {
                messageCountEl.innerHTML = 'Showing ' + visibleMessages.length + ' of ' + totalMessages + ' messages';
            }
            
            // Hide load more button if no more hidden messages
            if (hiddenMessages.length - loadedCount <= 0) {
                var loadMoreBtn = document.getElementById('loadMoreBtn');
                if (loadMoreBtn) {
                    loadMoreBtn.style.display = 'none';
                }
            }
            
            // Smooth scroll to newly loaded messages
            if (loadedCount > 0 && hiddenMessages.length > 0) {
                var firstNewMessage = document.querySelector('.message-item:not(.hidden-message):last-child');
                if (firstNewMessage) {
                    firstNewMessage.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            }
            
            return false;
        }
        
        // Function to load ALL messages at once
        function loadAllMessages() {
            var hiddenMessages = document.querySelectorAll('.message-item.hidden-message');
            
            // Show all hidden messages
            hiddenMessages.forEach(function(message) {
                message.classList.remove('hidden-message');
            });
            
            // Update message count
            var totalMessages = <?php 
                $count = 0;
                foreach($list_mes as $value) {
                    if($value['post_id'] == $post_id) {
                        $count++;
                    }
                }
                echo $count;
            ?>;
            
            var messageCountEl = document.getElementById('messageCount');
            if (messageCountEl) {
                messageCountEl.innerHTML = 'Showing all ' + totalMessages + ' messages';
            }
            
            // Hide load more button
            var loadMoreBtn = document.getElementById('loadMoreBtn');
            if (loadMoreBtn) {
                loadMoreBtn.style.display = 'none';
            }
            
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
            <div class="container">
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
                                                <form action="http://localhost/Post%20and%20Comments/View/FrontOfiice/addmessages.php?">
                                                    <input type="hidden" name="post_id" value="<?php echo $post_id ?>">
                                                    <div class="col-12">
                                                        <button class="btn btn-primary w-100 py-3" type="submit">
                                                            <i class="fa fa-plus"></i> Add New Message
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

        <!-- Main Content -->
        <div class="container">
            <!-- Original Post -->
            <?php 
            include_once "../../Controller/PostController.php";
            $postcontroller1 = new postcontroller();
            $list = $postcontroller1->readposts();
            
            foreach($list as $value):
                if($value['id'] == $post_id):
            ?>
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fa fa-comment"></i> Original Post</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0">
                            <img class="rounded-circle" src="img/testimonial-3.jpg" style="width: 60px; height: 60px;" alt="User">
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="card-title"><?php echo htmlspecialchars($value['subject']); ?></h5>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div>
                                    <span class="text-muted">
                                        <i class="fa fa-clock"></i> 
                                        <?php echo date('F j, Y, g:i a', strtotime($value['created_at'] ?? 'now')); ?>
                                    </span>
                                </div>
                                <div>
                                    <span class="badge bg-secondary">Posted by: kids name</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
                endif;
            endforeach; 
            ?>

            <!-- Messages Header -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4><i class="fa fa-comments"></i> Messages</h4>
                <div id="messageCount" class="messages-count">
                    <!-- Will be populated by JavaScript -->
                </div>
            </div>

            <!-- Messages/Replies -->
            <?php 
            $list_mes = $messagescontroller1->readmessages();
            $hasMessages = false;
            $messageIndex = 0;
            
            foreach($list_mes as $value):
                if($value['post_id'] == $post_id):
                    $hasMessages = true;
                    $messageIndex++;
            ?>
            <div class="card mb-3 message-item" id="message-<?php echo $value['id']; ?>">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0">
                            <img class="rounded-circle" src="img/testimonial-3.jpg" style="width: 60px; height: 60px;" alt="User">
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="card-text"><?php echo htmlspecialchars($value['content']); ?></p>
                            
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div>
                                    <span class="text-muted small">
                                        <i class="fa fa-clock"></i> 
                                        <?php echo date('F j, Y, g:i a', strtotime($value['created_at'] ?? 'now')); ?>
                                    </span>
                                </div>
                                
                                <div class="d-flex align-items-center gap-2">
                                    <!-- Like Button -->
                                    <i class="fa-solid fa-heart fa-lg" style="color: #FFD43B; cursor: pointer;" 
                                       onclick="toggleLike(this)" id="likeButton<?php echo $value['id']; ?>" 
                                       title="Like this message">
                                    </i>
                                    
                                    <!-- Action Buttons -->
                                    <div class="btn-group btn-group-sm" role="group">
                                        <!-- Update Button -->
                                        <a href="http://localhost/Post%20and%20Comments/View/FrontOfiice/updatemessages.php?post_id=<?php echo $post_id ?>&id3=<?php echo $value['id']?>" 
                                           class="btn btn-outline-warning" title="Edit message">
                                            <i class="fa fa-edit"></i> Edit
                                        </a>
                                        
                                        <!-- Delete Button with CAPTCHA -->
                                        <button onclick="return confirmDelete('<?php echo $value['id']; ?>', '<?php echo addslashes($value['content']); ?>')" 
                                                class="btn btn-outline-danger" title="Delete message">
                                            <i class="fa fa-trash"></i> Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
                endif;
            endforeach; 
            
            // If no messages found
            if (!$hasMessages):
            ?>
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fa fa-comments fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No messages yet</h5>
                    <p class="text-muted">Be the first to reply to this post!</p>
                    <a href="http://localhost/Post%20and%20Comments/View/FrontOfiice/addmessages.php?post_id=<?php echo $post_id ?>" 
                       class="btn btn-primary">
                        <i class="fa fa-plus"></i> Add First Message
                    </a>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Load More Button (initially hidden) -->
            <div id="loadMoreContainer" class="load-more-container">
                <button id="loadMoreBtn" class="btn btn-outline-primary load-more-btn" onclick="loadMoreMessages()" style="display: none;">
                    <i class="fa fa-chevron-down"></i> Load More Messages
                </button>
                <button id="loadAllBtn" class="btn btn-link text-decoration-none" onclick="loadAllMessages()" style="display: none;">
                    Show all messages
                </button>
            </div>
            
            <!-- Back to Posts Button -->
            <div class="mt-4 mb-5">
                <a href="javascript:history.back()" class="btn btn-outline-secondary">
                    <i class="fa fa-arrow-left"></i> Back to Posts
                </a>
            </div>
        </div>

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
                        <a class="btn btn-link text-white-50" href="">Our Services</a>
                        <a class="btn btn-link text-white-50" href="">Privacy Policy</a>
                        <a class="btn btn-link text-white-50" href="">Terms & Condition</a>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h3 class="text-white mb-4">Photo Gallery</h3>
                        <div class="row g-2 pt-2">
                            <div class="col-4">
                                <img class="img-fluid rounded bg-light p-1" src="img/classes-1.jpg" alt="">
                            </div>
                            <div class="col-4">
                                <img class="img-fluid rounded bg-light p-1" src="img/classes-2.jpg" alt="">
                            </div>
                            <div class="col-4">
                                <img class="img-fluid rounded bg-light p-1" src="img/classes-3.jpg" alt="">
                            </div>
                            <div class="col-4">
                                <img class="img-fluid rounded bg-light p-1" src="img/classes-4.jpg" alt="">
                            </div>
                            <div class="col-4">
                                <img class="img-fluid rounded bg-light p-1" src="img/classes-5.jpg" alt="">
                            </div>
                            <div class="col-4">
                                <img class="img-fluid rounded bg-light p-1" src="img/classes-6.jpg" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h3 class="text-white mb-4">Newsletter</h3>
                        <p>Stay updated with our latest news and announcements.</p>
                        <div class="position-relative mx-auto" style="max-width: 400px;">
                            <input class="form-control bg-transparent w-100 py-3 ps-4 pe-5" type="text" placeholder="Your email">
                            <button type="button" class="btn btn-primary py-2 position-absolute top-0 end-0 mt-2 me-2">SignUp</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="copyright">
                    <div class="row">
                        <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                            &copy; <a class="border-bottom" href="#">Your Site Name</a>, All Right Reserved. 
                            Designed By <a class="border-bottom" href="https://htmlcodex.com">HTML Codex</a>
                        </div>
                        <div class="col-md-6 text-center text-md-end">
                            <div class="footer-menu">
                                <a href="">Home</a>
                                <a href="">Cookies</a>
                                <a href="">Help</a>
                                <a href="">FQAs</a>
                            </div>
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
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    
    <script>
        // Show alert message if CAPTCHA error occurred
        <?php if (!empty($captcha_error)): ?>
        document.addEventListener('DOMContentLoaded', function() {
            alert('<?php echo $captcha_error; ?>');
        });
        <?php endif; ?>
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Count total and visible messages
            var totalMessages = <?php 
                $count = 0;
                foreach($list_mes as $value) {
                    if($value['post_id'] == $post_id) {
                        $count++;
                    }
                }
                echo $count;
            ?>;
            
            var visibleMessages = Math.min(3, totalMessages);
            
            // Update message count display
            var messageCountEl = document.getElementById('messageCount');
            if (messageCountEl) {
                messageCountEl.innerHTML = 'Showing ' + visibleMessages + ' of ' + totalMessages + ' messages';
            }
            
            // Show/hide load more buttons
            if (totalMessages > 3) {
                var loadMoreBtn = document.getElementById('loadMoreBtn');
                var loadAllBtn = document.getElementById('loadAllBtn');
                if (loadMoreBtn) loadMoreBtn.style.display = 'inline-block';
                if (loadAllBtn) loadAllBtn.style.display = 'inline-block';
                
                // Hide messages beyond the first 3
                var allMessages = document.querySelectorAll('.message-item');
                for (var i = 3; i < allMessages.length; i++) {
                    allMessages[i].classList.add('hidden-message');
                }
            }
        });
    </script>
</body>
</html>