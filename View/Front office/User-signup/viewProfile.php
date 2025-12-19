<?php
require_once "../../../config/config.php";
require_once "../../../Controller/UserController.php";
require_once "../../../Controller/BadgeHelper.php";
require_once "../../../Model/User.php";
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Logout handler - UPDATED
if (isset($_GET['logout'])) {
    // Get user ID before destroying session
    $user_id = $_SESSION['user_id'];
    
    // Reset verified status to 0
    $controller = new UserController();
    $controller->resetVerified($user_id);
    
    // Destroy session
    session_unset();
    session_destroy();
    
    header('Location: login.php?logged_out=1');
    exit;
}

// User data from session
$fname       = $_SESSION['fname'] ?? '';
$lname       = $_SESSION['lname'] ?? '';
$username    = $_SESSION['username'] ?? '';
$email       = $_SESSION['email'] ?? '';
$DOB         = $_SESSION['DOB'] ?? '';
$role        = $_SESSION['role'] ?? 'User';
$avatar      = $_SESSION['avatar'] ?? '';
$description = $_SESSION['description'] ?? 'No description yet.';

// Fetch latest starPoints from database (don't rely on session)
$controller = new UserController();
$userId = $_SESSION['user_id'] ?? null;
$starPoints = '0';

if ($userId) {
    $pdo = Config::getConnexion();
    $stmt = $pdo->prepare("SELECT COALESCE(sp.total_points, 0) as starPoints 
                           FROM user u 
                           LEFT JOIN STARR_POINTS sp ON u.user_id = sp.starr_id 
                           WHERE u.user_id = ?");
    $stmt->execute([$userId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        $starPoints = $result['starPoints'] ?? '0';
    }
}

// Default avatar if none exists
$avatarPath = '../assets/img/userProfile/default-avatar.png';
if (!empty($avatar) && $avatar !== 'NULL') {
    $testPath = '../assets/img/userProfile/' . $avatar;
    if (file_exists($testPath)) {
        $avatarPath = $testPath;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Starr - My Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@600&family=Lobster+Two:wght@700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- CSS -->
    <link href="../assets/lib/animate/animate.min.css" rel="stylesheet">
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">

    <style>
        .profile-card { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
        .avatar-img { width: 180px; height: 180px; object-fit: cover; border: 8px solid white; box-shadow: 0 10px 30px rgba(0,0,0,0.3); }
        .info-box { background: white; border-radius: 15px; padding: 25px; margin-bottom: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); transition: transform 0.3s; }
        .info-box:hover { transform: translateY(-5px); }
        .info-label { color: #667eea; font-weight: 600; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px; }
        .info-value { font-size: 1.3rem; font-weight: 500; color: #333; margin-top: 8px; }
        .logout-btn { background: #e74c3c; color: white; border: none; padding: 10px 25px; border-radius: 50px; font-weight: 600; }
        .logout-btn:hover { background: #c0392b; }
        .star-display { font-size: 4rem; color: #f39c12; }
    </style>
</head>
<body>
    <div class="container-xxl bg-white p-0">

        <!-- Spinner -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>

        <!-- Navbar with Logout Button -->
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
                    <a href="index.html" class="nav-item nav-link ">Home</a>
                    
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle " data-bs-toggle="dropdown">Posts</a>
                        <div class="dropdown-menu rounded-0 rounded-bottom border-0 m-0">
                            <a href="../posts and comments/addpost.php" class="dropdown-item ">Create a post</a>
                            <a href="posts and comments/math_threads.php" class="dropdown-item ">View threads</a>
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
                    <a href="viewProfile.php" class="nav-item nav-link active">Profile</a>

                </div>
            

            <div class="ms-auto">
                <a href="?logout=1" class="btn logout-btn logout-trigger">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </a>
            </div>
        </div>
        </nav>
        <!-- Navbar End -->
        <!-- Page Header -->
        <div class="container-fluid page-header py-5 mb-5" style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('../assets/img/carousel-1.jpg') center/cover no-repeat;">
            <div class="container text-center py-5">
                <h1 class="display-3 text-white mb-3 animated slideInDown">My Profile</h1>
                <p class="fs-5 text-white-50">Welcome back, <strong><?php echo htmlspecialchars($fname); ?>!</strong></p>
            </div>
        </div>

        <!-- Profile Content -->
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-10 text-center mb-5">
                    <img src="<?php echo htmlspecialchars($avatarPath); ?>" 
                         alt="Profile" class="rounded-circle avatar-img shadow" onerror="this.src='../assets/img/userProfile/default-avatar.png';">
                    <div class="mt-4">
                        <i class="fas fa-star star-display"></i>
                        <h2 class="mt-3 text-primary fw-bold"><?php echo number_format($starPoints); ?> Star Points</h2>
                        <div class="mt-2">
                            <?php echo BadgeHelper::renderBadge((int)$starPoints); ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-10">
                    <div class="row g-4">
                        <div class="col-md-6"><div class="info-box"><p class="info-label">Full Name</p><p class="info-value"><?php echo htmlspecialchars($fname . ' ' . $lname); ?></p></div></div>
                        <div class="col-md-6"><div class="info-box"><p class="info-label">Username</p><p class="info-value">@<?php echo htmlspecialchars($username); ?></p></div></div>
                        <div class="col-md-6"><div class="info-box"><p class="info-label">Email</p><p class="info-value"><?php echo htmlspecialchars($email); ?></p></div></div>
                        <div class="col-md-6"><div class="info-box"><p class="info-label">Date of Birth</p><p class="info-value"><?php echo $DOB ? date('F j, Y', strtotime($DOB)) : 'Not set'; ?></p></div></div>
                        <div class="col-md-6"><div class="info-box"><p class="info-label">Role</p><p class="info-value"><span class="badge bg-primary fs-6 px-4 py-2"><?php echo ucfirst($role); ?></span></p></div></div>
                        <div class="col-12"><div class="info-box"><p class="info-label">About Me</p><p class="info-value"><?php echo nl2br(htmlspecialchars($description)); ?></p></div></div>
                    </div>

                    <div class="text-center mt-5">
                        <a href="editUser.php" class="btn btn-primary btn-lg px-5 py-3 rounded-pill me-3">
                            <i class="fas fa-edit me-2"></i> Edit Profile
                        </a>
                        <a href="?logout=1" class="btn btn-danger btn-lg px-5 py-3 rounded-pill logout-trigger">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top">
            <i class="bi bi-arrow-up"></i>
        </a>
    </div>

    <!-- Scripts (all external) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/lib/wow/wow.min.js"></script>
    <script src="../assets/lib/easing/easing.min.js"></script>

    <!-- Your editUser.js for shake effect + logout alert -->
    <script src="../assets/js/editUser.js"></script>

    <script>
        // Hide spinner
        window.addEventListener('load', () => {
            document.getElementById('spinner')?.classList.remove('show');
        });

        // Sweet logout confirmation using your existing editUser.js style
        document.querySelectorAll('.logout-trigger').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                if (confirm('Are you sure you want to logout?')) {
                    window.location.href = this.getAttribute('href');
                }
            });
        });

        // Show alert on login.php after logout
        <?php if (isset($_GET['logged_out'])): ?>
            alert('You have been logged out successfully!');
        <?php endif; ?>
    </script>
    <style>
    /* Kill template cloud overlay */
    .page-header::before,
    .page-header::after {
        content: none !important;
        display: none !important;
        background: none !important;
    }
</style>

</body>
</html>