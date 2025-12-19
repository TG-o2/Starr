<?php
session_start();

// Role-based access control: Only teachers can access
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'teacher') {
    header('Location: ../../Front office/index.html');
    exit('Access denied. Teacher role required.');
}

require_once __DIR__ . '/../../../config/config.php';

$db = Config::getConnexion();
$teacher_id = $_SESSION['user_id'];
$teacher_name = $_SESSION['fname'] . ' ' . $_SESSION['lname'];

// Get teacher-specific stats
$stats = [
    'total_lessons' => 0,
    'total_news' => 0,
    'total_students_engaged' => 0,
    'total_views' => 0,
    'recent_interactions' => []
];

try {
    // Count posts created by this teacher
    $posts_query = "SELECT COUNT(*) as count FROM posts WHERE user_id = :teacher_id";
    $stmt = $db->prepare($posts_query);
    $stmt->execute([':teacher_id' => $teacher_id]);
    $stats['total_lessons'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
    
    // Count news posted by this teacher
    $news_query = "SELECT COUNT(*) as count FROM news WHERE teacherid = :teacher_id";
    $stmt = $db->prepare($news_query);
    $stmt->execute([':teacher_id' => $teacher_id]);
    $stats['total_news'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
    
    // Get total views on lessons and news
    $views_query = "SELECT COUNT(*) as count FROM content_views WHERE content_type IN ('lesson', 'news') 
                   AND content_id IN (
                       SELECT id FROM lessons WHERE teacher_id = :teacher_id
                       UNION
                       SELECT id FROM news WHERE created_by = :teacher_id
                   )";
    $stmt = $db->prepare($views_query);
    $stmt->execute([':teacher_id' => $teacher_id]);
    $stats['total_views'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
    
    // Get recent activity (comments on teacher's content)
    $activity_query = "SELECT c.id, c.content, c.created_at, u.username, u.fname, u.lname 
                      FROM comments c
                      JOIN user u ON c.user_id = u.user_id
                      WHERE c.post_id IN (
                          SELECT id FROM posts WHERE user_id = :teacher_id
                      )
                      ORDER BY c.created_at DESC
                      LIMIT 5";
    $stmt = $db->prepare($activity_query);
    $stmt->execute([':teacher_id' => $teacher_id]);
    $stats['recent_interactions'] = $stmt->fetchAll(PDO::FETCH_ASSOC) ?? [];
} catch (Exception $e) {
    error_log('Teacher Dashboard Error: ' . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Teacher Dashboard - Manage News and Education Content">
    <meta name="author" content="">

    <title>Teacher Dashboard - Starr</title>

    <!-- Custom fonts for this template-->
    <link href="../Admin Dashboard/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <script src="https://kit.fontawesome.com/9d17856d97.js" crossorigin="anonymous"></script>

    <!-- Custom styles for this template-->
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-success sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="Dashboard.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Teacher Starr</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="../Teacher Dashboard/Dashboard.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Content Management
            </div>

            <!-- Nav Item - News -->
            <li class="nav-item">
                <a class="nav-link" href="../bacci/backoffice.php">
                    <i class="fas fa-fw fa-newspaper"></i>
                    <span>Manage News</span></a>
            </li>

            <!-- Nav Item - Posts -->
            <li class="nav-item">
                <a class="nav-link" href="../Education Corner/lessonList.php">
                    <i class="fas fa-fw fa-book"></i>
                    <span>Manage Lessons</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../Teacher Dashboard/Dashboard.php">
                    <i class="fas fa-fw fa-book"></i>
                    <span>Home</span></a>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo htmlspecialchars($teacher_name); ?></span>
                                <img class="img-profile rounded-circle"
                                    src="../../Front office/assets/img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="../../Front office/User-signup/viewProfile.php">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profiles
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="../../../logout.php">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Teacher Dashboard</h1>
                        <p class="text-muted small">Welcome back, <?php echo htmlspecialchars($_SESSION['fname']); ?>! Manage your News and Education content here.</p>
                    </div>

                    <!-- Content Row - Quick Stats -->
                    <div class="row">

                        <!-- Total Lessons Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Total Lessons</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $stats['total_lessons']; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-book fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Total News Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                News Published</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $stats['total_news']; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-newspaper fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Total Views Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Total Views</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $stats['total_views']; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-eye fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Quick Access</div>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="../bacci/backoffice.php" class="btn btn-warning btn-sm"><i class="fas fa-plus"></i> News</a>
                                                <a href="../Post/Post-backoffice.php" class="btn btn-warning btn-sm"><i class="fas fa-plus"></i> Posts</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Recent Student Interactions -->
                        <div class="col-lg-8">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Recent Student Interactions</h6>
                                </div>
                                <div class="card-body">
                                    <?php if (!empty($stats['recent_interactions'])): ?>
                                        <div class="list-group">
                                            <?php foreach ($stats['recent_interactions'] as $interaction): ?>
                                                <div class="list-group-item list-group-item-action">
                                                    <div class="d-flex w-100 justify-content-between">
                                                        <h6 class="mb-1"><?php echo htmlspecialchars($interaction['fname'] . ' ' . $interaction['lname']); ?></h6>
                                                        <small class="text-muted"><?php echo date('M d, Y', strtotime($interaction['created_at'])); ?></small>
                                                    </div>
                                                    <p class="mb-1"><?php echo htmlspecialchars(substr($interaction['content'], 0, 100)); ?>...</p>
                                                    <small class="text-muted">@<?php echo htmlspecialchars($interaction['username']); ?></small>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <p class="text-muted text-center">No student interactions yet.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Teacher Info Card -->
                        <div class="col-lg-4">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-success">Teacher Info</h6>
                                </div>
                                <div class="card-body">
                                    <p><strong>Name:</strong> <?php echo htmlspecialchars($teacher_name); ?></p>
                                    <p><strong>Role:</strong> <span class="badge badge-success">Teacher</span></p>
                                    <p><strong>Last Login:</strong> <?php echo htmlspecialchars($_SESSION['last_login'] ?? 'Today'); ?></p>
                                    <hr>
                                    <h6 class="font-weight-bold">Quick Links:</h6>
                                    <h6 class="font-weight-bold">Quick Links:</h6>
                                    <ul class="list-unstyled">
                                        <li><a href="../bacci/backoffice.php"><i class="fas fa-newspaper"></i> Manage News</a></li>
                                        <li><a href="../Education Corner/lessonList.php"><i class="fas fa-book"></i> Manage Lessons</a></li>
                                        <li><a href="../../Front office/User-signup/viewProfile.php"><i class="fas fa-user"></i> View Profile</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Starr Teacher Dashboard 2025</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="../Admin Dashboard/vendor/jquery/jquery.min.js"></script>
    <script src="../Admin Dashboard/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../Admin Dashboard/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../Admin Dashboard/js/sb-admin-2.min.js"></script>

</body>

</html>
