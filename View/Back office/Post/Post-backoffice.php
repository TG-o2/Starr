
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Cards</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/9d17856d97.js" crossorigin="anonymous"></script>

</head>

<body id="page-top">

    <?php
    // Initialize stats variables at the top of the page
    require_once __DIR__ . '/../../../config/config.php';
    require_once __DIR__ . '/../../../Controller/PostController.php';
    require_once __DIR__ . '/../../../Controller/messagescontroller.php';
    
    $db = Config::getConnexion();
    $postcontroller1 = new PostController($db);
    $messagescontroller1 = new messagescontroller($db);
    $list = $postcontroller1->readpostsWithUserInfo();
    $listmes = $messagescontroller1->readmessages();
    
    // Calculate today's stats
    $today = date('Y-m-d');
    $todays_posts = 0;
    $todays_comments = 0;
    $total_engagement = 0;
    $total_posts_today = 0;
    $todays_reactions = 0;
    
    // Count today's posts and calculate engagement
    foreach ($list as $post) {
        $post_date = substr($post['created_at'], 0, 10);
        if ($post_date === $today) {
            $todays_posts++;
            $total_posts_today++;
            $total_engagement += (int)($post['view_count'] ?? 0) + (int)($post['like_count'] ?? 0) + (int)($post['number_messages'] ?? 0);
        }
    }
    
    // Count today's comments
    foreach ($listmes as $comment) {
        $comment_date = substr($comment['created_at'], 0, 10);
        if ($comment_date === $today) {
            $todays_comments++;
            $todays_reactions += (int)($comment['like_count'] ?? 0);
        }
    }
    
    // Calculate engagement rate (interactions per post)
    $engagement_rate = ($total_posts_today > 0) ? round(($total_engagement / $total_posts_today) / 100, 2) : 0;
    $engagement_percent = min(100, (int)($engagement_rate * 10)); // Convert to percentage for progress bar
    
    // Handle form submissions
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Add Post
        if (isset($_POST['add_post_btn'])) {
            $subject = $_POST['post_subject'] ?? '';
            $content = $_POST['post_content'] ?? '';
            $category = $_POST['post_category'] ?? '';
            $user_id = $_SESSION['user_id'] ?? 1; // Fallback to admin if not logged in
            
            if (!empty($subject) && !empty($content) && !empty($category)) {
                try {
                    $insert_query = "INSERT INTO posts (subject, content, category, user_id, created_at, number_messages, view_count, like_count)
                                   VALUES (:subject, :content, :category, :user_id, NOW(), 0, 0, 0)";
                    $stmt = $db->prepare($insert_query);
                    $stmt->execute([
                        ':subject' => $subject,
                        ':content' => $content,
                        ':category' => $category,
                        ':user_id' => $user_id
                    ]);
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Post added successfully!
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>';
                    // Refresh posts list
                    $list = $postcontroller1->readpostsWithUserInfo();
                } catch (Exception $e) {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">Error adding post: ' . htmlspecialchars($e->getMessage()) . '
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>';
                }
            }
        }
        
        // Delete Post
        if (isset($_POST['delete_post_btn'])) {
            $post_id = $_POST['delete_post_id'] ?? '';
            
            if (!empty($post_id)) {
                try {
                    $delete_query = "DELETE FROM posts WHERE id = :id";
                    $stmt = $db->prepare($delete_query);
                    $stmt->execute([':id' => $post_id]);
                    
                    if ($stmt->rowCount() > 0) {
                        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Post deleted successfully!
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>';
                        $list = $postcontroller1->readpostsWithUserInfo();
                    } else {
                        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">Post not found.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>';
                    }
                } catch (Exception $e) {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">Error deleting post: ' . htmlspecialchars($e->getMessage()) . '
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>';
                }
            }
        }
        
        // Add Comment
        if (isset($_POST['add_comment_btn'])) {
            $post_id = $_POST['comment_post_id'] ?? '';
            $content = $_POST['comment_content'] ?? '';
            $user_id = $_SESSION['user_id'] ?? 1;
            
            if (!empty($post_id) && !empty($content)) {
                try {
                    $insert_query = "INSERT INTO comments (post_id, content, user_id, created_at, like_count, number_replies)
                                   VALUES (:post_id, :content, :user_id, NOW(), 0, 0)";
                    $stmt = $db->prepare($insert_query);
                    $stmt->execute([
                        ':post_id' => $post_id,
                        ':content' => $content,
                        ':user_id' => $user_id
                    ]);
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Comment added successfully!
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>';
                    $listmes = $messagescontroller1->readmessages();
                } catch (Exception $e) {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">Error adding comment: ' . htmlspecialchars($e->getMessage()) . '
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>';
                }
            }
        }
        
        // Delete Comment
        if (isset($_POST['delete_comment_btn'])) {
            $comment_id = $_POST['delete_comment_id'] ?? '';
            
            if (!empty($comment_id)) {
                try {
                    $delete_query = "DELETE FROM comments WHERE id = :id";
                    $stmt = $db->prepare($delete_query);
                    $stmt->execute([':id' => $comment_id]);
                    
                    if ($stmt->rowCount() > 0) {
                        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Comment deleted successfully!
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>';
                        $listmes = $messagescontroller1->readmessages();
                    } else {
                        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">Comment not found.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>';
                    }
                } catch (Exception $e) {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">Error deleting comment: ' . htmlspecialchars($e->getMessage()) . '
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>';
                }
            }
        }
    }
    ?>

    <!-- Page Wrapper -->
    <div id="wrapper">

        
    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="Dashboard.php">
        <div class="sidebar-brand-icon rotate-n-15">
          <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Admin Starr<sup>*</sup></div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item active">
        <a class="nav-link" href="../Admin Dashboard/Dashboard.php">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span>
        </a>
      </li>
      <li class="nav-item ">
        <a class="nav-link" href="../User/list_users.php">
          <i class="fas fa-fw fa-users"></i>
          <span>Users</span>
        </a>
      </li>
     


      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        Rewards System
      </div>

      <!-- Nav Item - Points -->
      <li class="nav-item">
        <a class="nav-link" href="#" onclick="event.preventDefault(); $('#points-tab').tab('show');">
          <i class="fas fa-fw fa-star"></i>
          <span>Manage Points</span>
        </a>
      </li>

      <!-- Nav Item - Transactions -->
      <li class="nav-item">
        <a class="nav-link" href="#" onclick="event.preventDefault(); $('#transactions-tab').tab('show');">
          <i class="fas fa-fw fa-exchange-alt"></i>
          <span>Point Transactions</span>
        </a>
      </li>
      <li class="nav-item"><a class="nav-link" href="../transactions.php"><i class="fas fa-coins"></i><span>Transactions</span></a></li>


      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        Work
      </div>

      <!-- Nav Item - Pages Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true" aria-controls="collapsePages">
          <i class="fas fa-fw fa-folder"></i>
          <span>profiles</span>
        </a>
        <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Shortcuts:</h6>
            <a class="collapse-item" href="../Admin Moderation/Ban.html">Ban</a>
            <a class="collapse-item" href="Post-backoffice.php">Posts</a>
            <a class="collapse-item" href="../User/list_users.php">View Profiles</a>
            <div class="collapse-divider"></div>
          </div>
        </div>
      </li>

      <!-- Nav Item - Charts -->
      <li class="nav-item">
        <a class="nav-link" href="../Admin Moderation/Review-list.php">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>View Reports</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../Admin Moderation/Handle-report.php">
          <i class="fa-solid fa-flag"></i>
          <span>Handle Reports</span>
        </a>
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

                    <!-- Topbar Search -->
                    <form
                        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                                aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                        <!-- Nav Item - Alerts -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-danger badge-counter">3+</span>
                            </a>
                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header">
                                    Alerts Center
                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-primary">
                                            <i class="fas fa-file-alt text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 12, 2019</div>
                                        <span class="font-weight-bold">A new monthly report is ready to download!</span>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-success">
                                            <i class="fas fa-donate text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 7, 2019</div>
                                        $290.29 has been deposited into your account!
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-warning">
                                            <i class="fas fa-exclamation-triangle text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 2, 2019</div>
                                        Spending Alert: We've noticed unusually high spending for your account.
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                            </div>
                        </li>

                        <!-- Nav Item - Messages -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-envelope fa-fw"></i>
                                <!-- Counter - Messages -->
                                <span class="badge badge-danger badge-counter">7</span>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="messagesDropdown">
                                <h6 class="dropdown-header">
                                    Message Center
                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="img/undraw_profile_1.svg"
                                            alt="...">
                                        <div class="status-indicator bg-success"></div>
                                    </div>
                                    <div class="font-weight-bold">
                                        <div class="text-truncate">Hi there! I am wondering if you can help me with a
                                            problem I've been having.</div>
                                        <div class="small text-gray-500">Emily Fowler · 58m</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="img/undraw_profile_2.svg"
                                            alt="...">
                                        <div class="status-indicator"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">I have the photos that you ordered last month, how
                                            would you like them sent to you?</div>
                                        <div class="small text-gray-500">Jae Chun · 1d</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="img/undraw_profile_3.svg"
                                            alt="...">
                                        <div class="status-indicator bg-warning"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">Last month's report looks great, I am very happy with
                                            the progress so far, keep up the good work!</div>
                                        <div class="small text-gray-500">Morgan Alvarez · 2d</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="https://source.unsplash.com/Mv9hjnEUHR4/60x60"
                                            alt="...">
                                        <div class="status-indicator bg-success"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">Am I a good boy? The reason I ask is because someone
                                            told me that people say this to all dogs, even if they aren't good...</div>
                                        <div class="small text-gray-500">Chicken the Dog · 2w</div>
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Douglas McGee</span>
                                <img class="img-profile rounded-circle"
                                    src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
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
                        <h1 class="h3 mb-0 text-gray-800">Post and comment</h1>
                    </div>

                    <div class="row">

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2" style="cursor: pointer;" onclick="document.getElementById('posts-section').scrollIntoView({behavior: 'smooth'});">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Todays posts</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $todays_posts; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Annual) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2" style="cursor: pointer;" onclick="document.getElementById('posts-section').scrollIntoView({behavior: 'smooth'});">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Todays comments</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $todays_comments; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tasks Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">posts engagement rate
                                            </div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo ($total_posts_today > 0 ? round(($total_engagement / $total_posts_today), 1) : 0); ?></div>
                                                </div>
                                                <div class="col">
                                                    <div class="progress progress-sm mr-2">
                                                        <div class="progress-bar bg-info" role="progressbar"
                                                            style="width: <?php echo $engagement_percent; ?>%" aria-valuenow="<?php echo $engagement_percent; ?>" aria-valuemin="0"
                                                            aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pending Requests Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Todays reactions</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $todays_reactions; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-thumbs-up fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="posts-section">

                        <div class="col-lg-6">

                            <!-- Delete Post Form -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="m-0 font-weight-bold text-danger">Delete Post</h6>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="">
                                        <div class="col-sm-12 mb-3 mb-sm-0">
                                            <input type="number" class="form-control form-control-user" name="delete_post_id"
                                                placeholder="Enter Post ID" required style="margin-bottom: 15px;">
                                        </div>
                                        <div class="col-sm-12 mb-3 mb-sm-0">
                                            <textarea class="form-control form-control-user" name="delete_reason"
                                                placeholder="Reason for deletion (optional)" rows="2" style="margin-bottom: 15px;"></textarea>
                                        </div>
                                        <button type="submit" name="delete_post_btn" class="btn btn-danger btn-icon-split">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-trash"></i>
                                            </span>
                                            <span class="text">Delete Post</span>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Add Post Form -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-success">Add New Post</h6>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="" enctype="multipart/form-data">
                                        <div class="col-sm-12 mb-3">
                                            <input type="text" class="form-control form-control-user" name="post_subject"
                                                placeholder="Post Subject" required style="margin-bottom: 15px;">
                                        </div>
                                        <div class="col-sm-12 mb-3">
                                            <textarea class="form-control form-control-user" name="post_content"
                                                placeholder="Post Content" rows="4" required style="margin-bottom: 15px;"></textarea>
                                        </div>
                                        <div class="col-sm-12 mb-3">
                                            <select class="form-control form-control-user" name="post_category" required style="margin-bottom: 15px;">
                                                <option value="">Select Category</option>
                                                <option value="math">Math</option>
                                                <option value="science">Science</option>
                                                <option value="english">English</option>
                                                <option value="history">History</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-12 mb-3">
                                            <input type="file" class="form-control" name="post_image"
                                                accept="image/*" style="margin-bottom: 15px;">
                                        </div>
                                        <button type="submit" name="add_post_btn" class="btn btn-success btn-icon-split">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-check"></i>
                                            </span>
                                            <span class="text">Add Post</span>
                                        </button>
                                    </form>
                                </div>
                            </div>

                        </div>

                        <div class="col-lg-6">

                            <!-- Delete Comment Form -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-danger">Delete Comment</h6>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="">
                                        <div class="col-sm-12 mb-3 mb-sm-0">
                                            <input type="number" class="form-control form-control-user" name="delete_comment_id"
                                                placeholder="Enter Comment ID" required style="margin-bottom: 15px;">
                                        </div>
                                        <div class="col-sm-12 mb-3 mb-sm-0">
                                            <textarea class="form-control form-control-user" name="delete_comment_reason"
                                                placeholder="Reason for deletion (optional)" rows="2" style="margin-bottom: 15px;"></textarea>
                                        </div>
                                        <button type="submit" name="delete_comment_btn" class="btn btn-danger btn-icon-split">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-trash"></i>
                                            </span>
                                            <span class="text">Delete Comment</span>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Add Comment Form -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-success">Add Comment</h6>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="">
                                        <div class="col-sm-12 mb-3 mb-sm-0">
                                            <input type="number" class="form-control form-control-user" name="comment_post_id"
                                                placeholder="Post ID to comment on" required style="margin-bottom: 15px;">
                                        </div>
                                        <div class="col-sm-12 mb-3 mb-sm-0">
                                            <textarea class="form-control form-control-user" name="comment_content"
                                                placeholder="Write your comment" rows="3" required style="margin-bottom: 15px;"></textarea>
                                        </div>
                                        <button type="submit" name="add_comment_btn" class="btn btn-success btn-icon-split">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-check"></i>
                                            </span>
                                            <span class="text">Add Comment</span>
                                        </button>
                                    </form>
                                </div>
                            </div>

                        </div>
                            <?php
    // Loop through posts and display them
    foreach($list as $value){?>
    <div class="card mb-4">
                                <div class="card-header">
                                    <a href="#" class="btn btn-danger btn-icon-split">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-trash"></i>
                                        </span>
                                        <span class="text">Delete post</span>
                                    </a>
                                </div>
                                <div class="card-body">
                                    <div class="col-sm-12 mb-3 mb-sm-0">
                                        <p>id: <?php echo htmlspecialchars($value['id']); ?> </p>
                                        <p>subject: <?php echo htmlspecialchars($value['subject']); ?> </p>
                                        <p>content: <?php echo htmlspecialchars($value['content']); ?> </p>
                                        <p>number_messages: <?php echo htmlspecialchars($value['number_messages']); ?> </p>
                                        <p>view_count: <?php echo htmlspecialchars($value['view_count']); ?> </p>
                                        <p>like_count: <?php echo htmlspecialchars($value['like_count']); ?> </p>
                                        <p>username: <?php echo htmlspecialchars($value['username'] ?? 'N/A'); ?> </p>
                                        <p>category: <?php echo htmlspecialchars($value['category']); ?> </p>
                                        <p>created_at: <?php echo htmlspecialchars($value['created_at']); ?> </p>
                                    </div>
                                    <br />
                                    <div class="col-sm-12 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user" id="exampleFirstName"
                                            placeholder="Why you want to delete this Post?" style="margin-bottom: 30px;">
                                    </div>
                                </div>
                </div>
    <?php } ?>
    <?php foreach($listmes as $value){?>
    <div class="card mb-4">
        <div class="card-header">
            <a href="#" class="btn btn-danger btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-trash"></i>
                </span>
                <span class="text">Delete comment</span>
            </a>
        </div>
        <div class="card-body">
            <div class="col-sm-12 mb-3 mb-sm-0">
                <p>id: <?php echo htmlspecialchars($value['id']); ?> </p>
                <p>post_id: <?php echo htmlspecialchars($value['post_id']); ?> </p>
                <p>content: <?php echo htmlspecialchars($value['content']); ?> </p>
                <p>number_replies: <?php echo htmlspecialchars($value['number_replies'] ?? 0); ?> </p>
                <p>like_count: <?php echo htmlspecialchars($value['like_count'] ?? 0); ?> </p>
                <p>user_name: <?php echo htmlspecialchars($value['user_name'] ?? 'N/A'); ?> </p>
                <p>created_at: <?php echo htmlspecialchars($value['created_at']); ?> </p>
            </div>
            <br />
            <div class="col-sm-12 mb-3 mb-sm-0">
                <input type="text" class="form-control form-control-user" id="exampleFirstName"
                    placeholder="Why you want to delete this comment?" style="margin-bottom: 30px;">
            </div>
        </div>
    </div>
    <?php } ?>
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
                        <span>Copyright &copy; Your Website 2020</span>
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

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../assets/js/sb-admin-2.min.js"></script>

</body>

</html>
