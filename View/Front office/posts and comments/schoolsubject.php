<?php
    session_start();
    require_once __DIR__ . '/../../../config/config.php';
    
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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>School Subject Posts | Starr</title>
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
    <link href="https://fonts.googleapis.com/css2?family=Coming+Soon&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/9d17856d97.js" crossorigin="anonymous"></script>
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
                            <a href="addpost.php" class="dropdown-item active">Create a post</a>
                            <a href="math_threads.php" class="dropdown-item">View threads</a>
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
                        <!-- Show logged in user info -->
                        <div class="d-flex align-items-center" style="margin-left: 100px;">
                            <img src="../assets/img/userProfile/<?php echo htmlspecialchars($logged_in_user['avatar'] ?? 'default-avatar.png'); ?>" 
                                 alt="User Avatar" 
                                 class="rounded-circle me-3" 
                                 style="width: 80px; height: 80px; object-fit: cover; border: 3px solid #F9C74F;"
                                 onerror="this.src='../assets/img/userProfile/default-avatar.png';">
                            <div>
                                <h1 class="text-white" style="font-family: coming soon; font-size: 56px; margin: 0; font-weight: bold; line-height: 1.2;">
                                    <?php echo htmlspecialchars($logged_in_user['username'] ?? ($logged_in_user['fname'] . ' ' . $logged_in_user['lname'])); ?>
                                </h1>
                                <p class="text-warning" style="font-size: 22px; margin: 12px 0; font-weight: 600;">
                                    <i class="fas fa-star"></i> <?php echo number_format($logged_in_user['starPoints'] ?? 0); ?> Star Points
                                </p>
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
                            
            </div>
        </div>
        <!-- Page Header End -->
    </div>
</body>
<style>
    #iheb:hover{
            transform: scale(1.05);
            background-color:red !important;
    }
    #iheb2:hover{
            transform: scale(1.05);
            background-color:red !important;
    }
    #iheb3:hover{
            transform: scale(1.05);
            background-color:red !important;
    }
    #iheb4:hover{
            transform: scale(1.05);
            background-color:red !important;
    }
</style>
<div style="display: flex; justify-content: center;">
<table>
<tr>
<td>
<div onclick="location.assign('math_threads.php?search=')" id="iheb"class="wow fadeInUp" data-wow-delay="0.1s" style="background-color: #90be6d; width: 500px; border-radius: 20px; padding: 15px;margin: 0 auto; margin: 10px;">
    <div class="testimonial-item bg-light rounded p-5" >
        <h1 class="fs-5" style="font-family: coming soon;">Math</h1>
        <br></br>
        <p class="fs-5" style="font-family: coming soon;">Get help with specific subject</p>
        
        <br></br>
        <div class="row g-1">
                                    <div class="col-4">
                                        <div class="border-top border-3 border-primary pt-2">
                                            <h6 class="text-primary mb-1">views:</h6>
                                            <small>103</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="border-top border-3 border-success pt-2">
                                            <h6 class="text-success mb-1">created at:</h6>
                                            <small>17:52 AM</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="border-top border-3 border-warning pt-2">
                                            <h6 class="text-warning mb-1">likes:<i class="fa-solid fa-heart" style="color: #FFD43B;"></i></h6>
                                            <small>78</small>
                                        </div>
                                    </div>
                                </div>
    </div>
</div>
</td>
<td>
<div id="iheb2"class="wow fadeInUp" data-wow-delay="0.1s" style="background-color: #90be6d; width: 500px;  max-width: auto;; border-radius: 20px; padding: 15px;margin: 0 auto; margin: 10px;">
    <div class="testimonial-item bg-light rounded p-5">
        <h1 class="fs-5" style="font-family: coming soon;">History</h1>
        <br></br>
        <p class="fs-5" style="font-family: coming soon;">Tips, tools, and study groups</p>
        
        <br></br>
        <div class="row g-1">
                                    <div class="col-4">
                                        <div class="border-top border-3 border-primary pt-2">
                                            <h6 class="text-primary mb-1">views:</h6>
                                            <small>103</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="border-top border-3 border-success pt-2">
                                            <h6 class="text-success mb-1">created at:</h6>
                                            <small>17:52 AM</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="border-top border-3 border-warning pt-2">
                                            <h6 class="text-warning mb-1">likes:<i class="fa-solid fa-heart" style="color: #FFD43B;"></i></h6>
                                            <small>78</small>
                                        </div>
                                    </div>
                                </div>
    </div>
</div>
</td>
</tr>
<tr>
<td>
<div id="iheb3"class="wow fadeInUp" data-wow-delay="0.1s" style="background-color: #90be6d; width: 500px; border-radius: 20px; padding: 15px;margin: 0 auto; margin: 10px;">
    <div class="testimonial-item bg-light rounded p-5">
        <h1 class="fs-5" style="font-family: coming soon;">Science</h1>
        <br></br>
        <p class="fs-5" style="font-family: coming soon;">Introduction and off-topic</p>
        
        <br></br>
        <div class="row g-1">
                                    <div class="col-4">
                                        <div class="border-top border-3 border-primary pt-2">
                                            <h6 class="text-primary mb-1">views:</h6>
                                            <small>103</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="border-top border-3 border-success pt-2">
                                            <h6 class="text-success mb-1">created at:</h6>
                                            <small>17:52 AM</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="border-top border-3 border-warning pt-2">
                                            <h6 class="text-warning mb-1">likes:<i class="fa-solid fa-heart" style="color: #FFD43B;"></i></h6>
                                            <small>78</small>
                                        </div>
                                    </div>
                                </div>
    </div>
</div>
</td>
<td>
<div id="iheb4"class="wow fadeInUp" data-wow-delay="0.1s" style="background-color: #90be6d; width: 500px; border-radius: 20px; padding: 15px;margin: 0 auto; margin: 10px;">
    <div class="testimonial-item bg-light rounded p-5">
        <h1 class="fs-5" style="font-family: coming soon;">English</h1>
        <br></br>
        <p class="fs-5" style="font-family: coming soon;">Suggestions and feedback</p>
        
        <br></br>
        <div class="row g-1">
                                    <div class="col-4">
                                        <div class="border-top border-3 border-primary pt-2">
                                            <h6 class="text-primary mb-1">views:</h6>
                                            <small>103</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="border-top border-3 border-success pt-2">
                                            <h6 class="text-success mb-1">created at:</h6>
                                            <small>17:52 AM</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="border-top border-3 border-warning pt-2">
                                            <h6 class="text-warning mb-1">likes:<i class="fa-solid fa-heart" style="color: #FFD43B;"></i></h6>
                                            <small>78</small>
                                        </div>
                                    </div>
                                </div>
    </div>
</div>
</td>
</tr>
</table>
</div>

    <!-- Footer Start -->
    

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../lib/wow/wow.min.js"></script>
    <script src="../lib/easing/easing.min.js"></script>
    <script src="../lib/waypoints/waypoints.min.js"></script>
    <script src="../lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="../assets/js/main.js"></script>
</body>

</html>
