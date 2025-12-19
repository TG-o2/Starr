<?php
require_once __DIR__ . '/../admin_guard.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Dashboard</title>

    <!-- Custom fonts for this template -->
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">

    <script src="https://kit.fontawesome.com/9d17856d97.js" crossorigin="anonymous"></script>
</head>
<body id="page-top">

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
        <a class="nav-link" href="Dashboard.php">
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
            <a class="collapse-item" href="../../Front office/index.html">Front</a>
            <a class="collapse-item" href="../Post/Post-backoffice.php">Posts</a>
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
          <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
            <div class="input-group">
              <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
              <div class="input-group-append">
                <button class="btn btn-primary" type="button">
                  <i class="fas fa-search fa-sm"></i>
                </button>
              </div>
            </div>
          </form>


            <div class="topbar-divider d-none d-sm-block"></div>

            
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
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

          <!-- Tab Navigation -->
          <ul class="nav nav-tabs mb-4" id="dashboardTabs" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
                <i class="fas fa-tachometer-alt"></i> Dashboard
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="points-tab" data-toggle="tab" href="#points" role="tab" aria-controls="points" aria-selected="false">
                <i class="fas fa-star"></i> Manage Points
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="transactions-tab" data-toggle="tab" href="#transactions" role="tab" aria-controls="transactions" aria-selected="false">
                <i class="fas fa-exchange-alt"></i> Point Transactions
              </a>
            </li>
          </ul>

          <!-- Tab Content -->
          <div class="tab-content" id="dashboardTabContent">
            
            <!-- Dashboard Tab -->
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
          <!-- QUICK STATS ROW -->
          <div class="row" id="quickStatsRow">
            <!-- Total Users Card -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Total Users
                      </div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalUsersCount">0</div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Active Posts Card -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                        Active Posts (30d)
                      </div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800" id="activePostsCount">0</div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Open Reports Card -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                        Open Reports
                      </div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800" id="openReportsCount">0</div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-flag fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Pending Messages Card -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                        Unread Messages
                      </div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800" id="pendingMessagesCount">0</div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-envelope fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>

          <!-- RECENT ACTIVITY FEED -->
          <div class="row">

            <!-- Recent Activity Card -->
            <div class="col-lg-8 mb-4">
              <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Recent Activity Feed</h6>
                  <span class="badge badge-primary" id="activityCount">0</span>
                </div>
                <div class="card-body">
                  <div id="recentActivityContainer">
                    <p class="text-center text-muted">Loading activity...</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- OPEN REPORTS WIDGET -->
            <div class="col-lg-4 mb-4">
              <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-danger">Pending Reports</h6>
                  <span class="badge badge-danger" id="reportsCount">0</span>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                  <div id="openReportsContainer">
                    <p class="text-center text-muted">Loading reports...</p>
                  </div>
                </div>
              </div>
            </div>

          </div>

          <!-- CHARTS ROW -->
          <div class="row">

            <!-- User Growth Chart -->
            <div class="col-lg-7 mb-4">
              <div class="card shadow mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-chart-line"></i> User Growth (Last 30 Days)
                  </h6>
                </div>
                <div class="card-body">
                  <div class="chart-area">
                    <canvas id="userGrowthChart"></canvas>
                  </div>
                </div>
              </div>
            </div>

            <!-- Badge Distribution Chart -->
            <div class="col-lg-5 mb-4">
              <div class="card shadow mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-medal"></i> Badge Distribution
                  </h6>
                </div>
                <div class="card-body">
                  <div class="chart-bar">
                    <canvas id="badgeDistributionChart"></canvas>
                  </div>
                  <div class="mt-3 text-center small">
                    <span class="mr-3"><i class="fas fa-circle" style="color: #cd7f32;"></i> Bronze</span>
                    <span class="mr-3"><i class="fas fa-circle" style="color: #c0c0c0;"></i> Silver</span>
                    <span class="mr-3"><i class="fas fa-circle" style="color: #ffd700;"></i> Gold</span>
                    <span class="mr-3"><i class="fas fa-circle" style="color: #e5e4e2;"></i> Platinum</span>
                    <span><i class="fas fa-circle" style="color: #b9f2ff;"></i> Diamond</span>
                  </div>
                </div>
              </div>
            </div>

          </div>


            </div>
            <!-- End Dashboard Tab -->

            <!-- Points Management Tab -->
            <div class="tab-pane fade" id="points" role="tabpanel" aria-labelledby="points-tab">
              <?php
                require_once __DIR__ . '/../../../config/config.php';
                require_once __DIR__ . '/../../../Controller/StarrPointsController.php';

                $db = Config::getConnexion();
                $pointsController = new StarrPointsController($db);

                $pointsMessage = '';
                $pointsAlertType = '';

                // Handle form submission
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pointsAction'])) {
                    $action = $_POST['pointsAction'];

                    if ($action === 'create') {
                        $starr_id = $_POST['starr_id'] ?? '';
                        $total_points = $_POST['total_points'] ?? 0;
                        $login_streak = $_POST['login_streak'] ?? 0;
                        $last_login_date = $_POST['last_login_date'] ?? date('Y-m-d H:i:s');

                        $errors = $pointsController->validateInput($starr_id, $total_points, $login_streak);
                        if (count($errors) > 0) {
                            $pointsMessage = 'Error: ' . implode(', ', $errors);
                            $pointsAlertType = 'danger';
                        } else {
                            $result = $pointsController->create($starr_id, $total_points, $last_login_date, $login_streak);
                            $pointsMessage = $result['message'];
                            $pointsAlertType = $result['success'] ? 'success' : 'danger';
                        }
                    } elseif ($action === 'delete') {
                        $starr_id = $_POST['starr_id'] ?? '';
                        $result = $pointsController->delete($starr_id);
                        $pointsMessage = $result['message'];
                        $pointsAlertType = $result['success'] ? 'success' : 'danger';
                    }
                }

                $allUsersResult = $pointsController->getAll(200, 0);
                $allUsers = (!empty($allUsersResult['success']) && !empty($allUsersResult['data'])) ? $allUsersResult['data'] : [];
                $leaderboardResult = $pointsController->getLeaderboard(10);
                $leaderboard = (!empty($leaderboardResult['success']) && !empty($leaderboardResult['data'])) ? $leaderboardResult['data'] : [];
              ?>
              
              <h5 class="mb-4">Manage Points</h5>
              
              <?php if ($pointsMessage): ?>
                <div class="alert alert-<?php echo htmlspecialchars($pointsAlertType); ?> alert-dismissible fade show" role="alert">
                  <?php echo htmlspecialchars($pointsMessage); ?>
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
              <?php endif; ?>

              <div class="row mb-4">
                <div class="col-xl-3 col-md-6">
                  <div class="card border-left-primary shadow">
                    <div class="card-body">
                      <div class="text-xs font-weight-bold text-primary mb-1">Total Users</div>
                      <div class="h5 text-gray-800"><?php echo count($allUsers); ?></div>
                    </div>
                  </div>
                </div>
                <div class="col-xl-3 col-md-6">
                  <div class="card border-left-success shadow">
                    <div class="card-body">
                      <div class="text-xs font-weight-bold text-success mb-1">Top Scorer</div>
                      <div class="h5 text-gray-800"><?php echo (!empty($leaderboard) && isset($leaderboard[0]['starr_id'])) ? htmlspecialchars($leaderboard[0]['starr_id']) : 'N/A'; ?></div>
                    </div>
                  </div>
                </div>
                <div class="col-xl-3 col-md-6">
                  <div class="card border-left-info shadow">
                    <div class="card-body">
                      <div class="text-xs font-weight-bold text-info mb-1">Average Points</div>
                      <div class="h5 text-gray-800"><?php echo (!empty($allUsers)) ? intval(array_sum(array_column($allUsers, 'total_points')) / count($allUsers)) : 0; ?></div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-12">
                  <div class="card shadow mb-4">
                    <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">All Users</h6></div>
                    <div class="card-body">
                      <div class="table-responsive">
                        <table class="table table-bordered">
                          <thead><tr><th>STARR ID</th><th>Points</th><th>Streak</th><th>Last Login</th></tr></thead>
                          <tbody>
                            <?php foreach ($allUsers as $user): ?>
                              <?php 
                                $lastLoginSafe = (!empty($user['last_login_date'])) ? date('M d, Y', strtotime($user['last_login_date'])) : '—';
                              ?>
                              <tr>
                                <td><?php echo htmlspecialchars($user['starr_id']); ?></td>
                                <td><?php echo htmlspecialchars($user['total_points']); ?></td>
                                <td><?php echo htmlspecialchars($user['login_streak']); ?></td>
                                <td><?php echo $lastLoginSafe; ?></td>
                              </tr>
                            <?php endforeach; ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- End Points Tab -->

            <!-- Transactions Tab -->
            <div class="tab-pane fade" id="transactions" role="tabpanel" aria-labelledby="transactions-tab">
              <?php
                require_once __DIR__ . '/../../../config/config.php';
                require_once __DIR__ . '/../../../Controller/PointTransactionController.php';

                $transDb = Config::getConnexion();
                $transController = new PointTransactionController($transDb);

                $transMessage = '';
                $transAlertType = '';

                // Handle form submission
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['transAction'])) {
                    $action = $_POST['transAction'];

                    if ($action === 'create') {
                        $starr_id = $_POST['trans_starr_id'] ?? '';
                        $points_change = $_POST['points_change'] ?? 0;
                        $reason = $_POST['reason'] ?? '';
                        $created_by = $_POST['created_by'] ?? 'Admin';

                        $result = $transController->create($starr_id, $points_change, $reason, $created_by);
                        $transMessage = $result['message'];
                        $transAlertType = $result['success'] ? 'success' : 'danger';
                        
                        // Redirect to prevent form resubmission on page refresh
                        if ($result['success']) {
                            header('Location: ' . $_SERVER['PHP_SELF'] . '#transactions');
                            exit;
                        }
                    } elseif ($action === 'delete') {
                        $transaction_id = $_POST['transaction_id'] ?? '';
                        $result = $transController->delete($transaction_id);
                        $transMessage = $result['message'];
                        $transAlertType = $result['success'] ? 'success' : 'danger';
                        
                        // Redirect to prevent form resubmission on page refresh
                        if ($result['success']) {
                            header('Location: ' . $_SERVER['PHP_SELF'] . '#transactions');
                            exit;
                        }
                    }
                }

                $all_transactions = $transController->getAll(200, 0);
                $transactions = $all_transactions['success'] ? $all_transactions['data'] : [];
              ?>

              <h5 class="mb-4">Point Transactions</h5>

              <?php if ($transMessage): ?>
                <div class="alert alert-<?php echo htmlspecialchars($transAlertType); ?> alert-dismissible fade show" role="alert">
                  <?php echo htmlspecialchars($transMessage); ?>
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
              <?php endif; ?>

              <div class="row mb-4">
                <div class="col-xl-3 col-md-6">
                  <div class="card border-left-info shadow">
                    <div class="card-body">
                      <div class="text-xs font-weight-bold text-info mb-1">Total Transactions</div>
                      <div class="h5 text-gray-800"><?php echo count($transactions); ?></div>
                    </div>
                  </div>
                </div>
                <div class="col-xl-3 col-md-6">
                  <div class="card border-left-success shadow">
                    <div class="card-body">
                      <div class="text-xs font-weight-bold text-success mb-1">Points Earned</div>
                      <div class="h5 text-gray-800"><?php $pos = array_filter($transactions, fn($t) => $t['points_change'] > 0); echo array_sum(array_column($pos, 'points_change')); ?></div>
                    </div>
                  </div>
                </div>
                <div class="col-xl-3 col-md-6">
                  <div class="card border-left-danger shadow">
                    <div class="card-body">
                      <div class="text-xs font-weight-bold text-danger mb-1">Points Deducted</div>
                      <div class="h5 text-gray-800"><?php $neg = array_filter($transactions, fn($t) => $t['points_change'] < 0); echo abs(array_sum(array_column($neg, 'points_change'))); ?></div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-12">
                  <div class="card shadow mb-4">
                    <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Transaction History</h6></div>
                    <div class="card-body">
                      <div class="table-responsive">
                        <table class="table table-bordered">
                          <thead><tr><th>ID</th><th>STARR ID</th><th>Points</th><th>Reason</th><th>Date</th></tr></thead>
                          <tbody>
                            <?php foreach (array_reverse($transactions) as $trans): ?>
                              <tr>
                                <td><?php echo $trans['transaction_id']; ?></td>
                                <td><?php echo htmlspecialchars($trans['starr_id']); ?></td>
                                <td><span class="badge <?php echo $trans['points_change'] > 0 ? 'badge-success' : 'badge-danger'; ?>"><?php echo $trans['points_change']; ?></span></td>
                                <td><?php echo htmlspecialchars(substr($trans['reason'], 0, 20)); ?></td>
                                <td><?php echo date('M d, Y', strtotime($trans['created_at'])); ?></td>
                              </tr>
                            <?php endforeach; ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- End Transactions Tab -->

          </div>
          <!-- End Tab Content -->

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; Your Website 2021</span>
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
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
  <script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../assets/js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="../assets/vendor/chart.js/Chart.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="../assets/js/demo/chart-area-demo.js"></script>
  <script src="../assets/js/demo/chart-pie-demo.js"></script>

  <!-- Dashboard Dynamic Content Script -->
  <script>
    function loadQuickStats() {
      const controller = new AbortController();
      const timeoutId = setTimeout(() => controller.abort(), 10000); // 10 second timeout
      
      fetch('../../../api/admin/quick-stats.php', { signal: controller.signal })
        .then(response => {
          clearTimeout(timeoutId);
          if (!response.ok) throw new Error('HTTP ' + response.status);
          return response.json();
        })
        .then(data => {
          if (data.success) {
            document.getElementById('totalUsersCount').textContent = data.data.total_users;
            document.getElementById('activePostsCount').textContent = data.data.active_posts;
            document.getElementById('openReportsCount').textContent = data.data.open_reports;
            document.getElementById('pendingMessagesCount').textContent = data.data.pending_messages;
          } else {
            console.warn('Quick stats returned success: false', data);
          }
        })
        .catch(error => console.error('Error loading quick stats:', error));
    }

    function loadRecentActivity() {
      const controller = new AbortController();
      const timeoutId = setTimeout(() => controller.abort(), 10000); // 10 second timeout
      
      fetch('../../../api/admin/recent-activity.php', { signal: controller.signal })
        .then(response => {
          clearTimeout(timeoutId);
          if (!response.ok) throw new Error('HTTP ' + response.status);
          return response.json();
        })
        .then(data => {
          console.log('Recent activity data:', data);
          const container = document.getElementById('recentActivityContainer');
          
          if (!data.success) {
            container.innerHTML = '<p class="text-center text-danger"><i class="fas fa-exclamation-triangle"></i> Error loading activities</p>';
            console.error('API error:', data.error);
            return;
          }
          
          if (data.count === 0) {
            container.innerHTML = '<p class="text-center text-muted">No recent activity</p>';
            document.getElementById('activityCount').textContent = '0';
            return;
          }

          document.getElementById('activityCount').textContent = data.count;
          container.innerHTML = '<ul class="list-group">';
            
          data.data.forEach(activity => {
            const timestamp = new Date(activity.timestamp);
            const timeAgo = getTimeAgo(timestamp);
            const icon = activity.activity_type === 'post' ? 'file-alt' : 
                        activity.activity_type === 'comment' ? 'comment' : 'flag';
            const color = activity.activity_type === 'post' ? 'primary' : 
                         activity.activity_type === 'comment' ? 'info' : 'danger';

            container.innerHTML += `
              <li class="list-group-item border-left-${color}">
                <div class="d-flex justify-content-between align-items-start">
                  <div>
                    <i class="fas fa-${icon} text-${color} mr-2"></i>
                    <strong>${activity.action}</strong>
                    <br>
                    <small class="text-muted">${timeAgo}</small>
                  </div>
                </div>
              </li>
            `;
          });

          container.innerHTML += '</ul>';
        })
        .catch(error => {
          clearTimeout(timeoutId);
          console.error('Error loading recent activity:', error);
          const container = document.getElementById('recentActivityContainer');
          if (error.name === 'AbortError') {
            container.innerHTML = '<p class="text-center text-danger"><i class="fas fa-clock"></i> Request timeout</p>';
          } else {
            container.innerHTML = '<p class="text-center text-danger"><i class="fas fa-exclamation-circle"></i> Failed to load</p>';
          }
        });
    }

    function loadOpenReports() {
      const controller = new AbortController();
      const timeoutId = setTimeout(() => controller.abort(), 10000); // 10 second timeout
      
      fetch('../../../api/admin/open-reports.php', { signal: controller.signal })
        .then(response => {
          clearTimeout(timeoutId);
          if (!response.ok) throw new Error('HTTP ' + response.status);
          return response.json();
        })
        .then(data => {
          console.log('Open reports data:', data);
          const container = document.getElementById('openReportsContainer');
          
          if (!data.success) {
            container.innerHTML = '<p class="text-center text-danger"><i class="fas fa-exclamation-triangle"></i> Error loading reports</p>';
            console.error('API error:', data.error);
            return;
          }
          
          document.getElementById('reportsCount').textContent = data.count;

          if (data.count === 0) {
            container.innerHTML = '<p class="text-center text-success"><i class="fas fa-check-circle"></i> No pending reports</p>';
          } else {
            container.innerHTML = '<ul class="list-group">';

            data.data.forEach(report => {
              const reportDate = new Date(report.report_date);
              const timeAgo = getTimeAgo(reportDate);

              container.innerHTML += `
                <li class="list-group-item">
                  <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                      <strong class="d-block">Report #${report.report_id_padded}</strong>
                      <small class="text-muted">${report.report_reason}</small>
                    </div>
                    <span class="badge badge-${report.badge_color}">
                      <i class="fas fa-${report.badge_icon}"></i> ${report.severity}
                    </span>
                  </div>
                  <div class="small mb-2">
                    <i class="fas fa-user-circle"></i> ${report.reported_user_name || 'Unknown'} (reported by ${report.reporter_name || 'Unknown'})
                  </div>
                  <small class="text-muted d-block mb-2">${timeAgo}</small>
                  <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-outline-primary" title="View Details" onclick="viewReportDetails(${report.report_id})">
                      <i class="fas fa-eye"></i>
                    </button>
                    <button type="button" class="btn btn-outline-success" title="Resolve" onclick="resolveReport(${report.report_id})">
                      <i class="fas fa-check"></i>
                    </button>
                    <button type="button" class="btn btn-outline-danger" title="Reject" onclick="rejectReport(${report.report_id})">
                      <i class="fas fa-times"></i>
                    </button>
                  </div>
                </li>
              `;
            });

            container.innerHTML += '</ul>';
          }
        })
        .catch(error => {
          clearTimeout(timeoutId);
          console.error('Error loading open reports:', error);
          const container = document.getElementById('openReportsContainer');
          if (error.name === 'AbortError') {
            container.innerHTML = '<p class="text-center text-danger"><i class="fas fa-clock"></i> Request timeout</p>';
          } else {
            container.innerHTML = '<p class="text-center text-danger"><i class="fas fa-exclamation-circle"></i> Failed to load</p>';
          }
        });
    }

    function getTimeAgo(date) {
      const seconds = Math.floor((new Date() - date) / 1000);
      if (seconds < 60) return 'Just now';
      if (seconds < 3600) return Math.floor(seconds / 60) + ' min ago';
      if (seconds < 86400) return Math.floor(seconds / 3600) + ' h ago';
      if (seconds < 604800) return Math.floor(seconds / 86400) + ' d ago';
      return date.toLocaleDateString();
    }

    function viewReportDetails(reportId) {
      window.location.href = '../Admin Moderation/Review-list.php?report_id=' + reportId;
    }

    function resolveReport(reportId) {
      if (confirm('Mark this report as resolved?')) {
        fetch('../../../api/admin/resolve-report.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ report_id: reportId, action: 'resolved' })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert('Report resolved successfully');
            loadOpenReports();
            loadQuickStats();
          } else {
            alert('Error: ' + data.error);
          }
        })
        .catch(error => console.error('Error resolving report:', error));
      }
    }

    function rejectReport(reportId) {
      if (confirm('Reject/close this report?')) {
        fetch('../../../api/admin/resolve-report.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ report_id: reportId, action: 'rejected' })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert('Report rejected successfully');
            loadOpenReports();
            loadQuickStats();
          } else {
            alert('Error: ' + data.error);
          }
        })
        .catch(error => console.error('Error rejecting report:', error));
      }
    }

    // Chart instances
    let userGrowthChart = null;
    let badgeDistributionChart = null;

    function loadUserGrowthChart() {
      fetch('../../../api/admin/user-growth.php')
        .then(response => response.json())
        .then(data => {
          if (data.success && data.data.length > 0) {
            const labels = data.data.map(item => {
              const date = new Date(item.date);
              return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
            });
            const values = data.data.map(item => parseInt(item.new_users));

            const ctx = document.getElementById('userGrowthChart').getContext('2d');
            
            if (userGrowthChart) {
              userGrowthChart.destroy();
            }

            userGrowthChart = new Chart(ctx, {
              type: 'line',
              data: {
                labels: labels,
                datasets: [{
                  label: 'New Users',
                  data: values,
                  borderColor: '#4e73df',
                  backgroundColor: 'rgba(78, 115, 223, 0.1)',
                  borderWidth: 2,
                  fill: true,
                  tension: 0.3,
                  pointBackgroundColor: '#4e73df',
                  pointBorderColor: '#fff',
                  pointBorderWidth: 2,
                  pointRadius: 4,
                  pointHoverRadius: 6
                }]
              },
              options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                  y: {
                    beginAtZero: true,
                    ticks: {
                      stepSize: 1
                    }
                  }
                },
                plugins: {
                  legend: {
                    display: false
                  },
                  tooltip: {
                    callbacks: {
                      label: function(context) {
                        return context.parsed.y + ' new user' + (context.parsed.y !== 1 ? 's' : '');
                      }
                    }
                  }
                }
              }
            });
          }
        })
        .catch(error => console.error('Error loading user growth chart:', error));
    }

    function loadBadgeDistributionChart() {
      fetch('../../../api/admin/badge-distribution.php')
        .then(response => response.json())
        .then(data => {
          if (data.success && data.data.length > 0) {
            const labels = data.data.map(item => item.badge_tier);
            const values = data.data.map(item => parseInt(item.user_count));
            
            // Badge colors
            const colors = labels.map(tier => {
              switch(tier) {
                case 'Diamond': return '#b9f2ff';
                case 'Platinum': return '#e5e4e2';
                case 'Gold': return '#ffd700';
                case 'Silver': return '#c0c0c0';
                case 'Bronze': return '#cd7f32';
                default: return '#858796';
              }
            });

            const ctx = document.getElementById('badgeDistributionChart').getContext('2d');
            
            if (badgeDistributionChart) {
              badgeDistributionChart.destroy();
            }

            badgeDistributionChart = new Chart(ctx, {
              type: 'bar',
              data: {
                labels: labels,
                datasets: [{
                  label: 'Users',
                  data: values,
                  backgroundColor: colors,
                  borderColor: colors.map(c => c),
                  borderWidth: 1
                }]
              },
              options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                  y: {
                    beginAtZero: true,
                    ticks: {
                      stepSize: 1
                    }
                  }
                },
                plugins: {
                  legend: {
                    display: false
                  },
                  tooltip: {
                    callbacks: {
                      label: function(context) {
                        return context.parsed.y + ' user' + (context.parsed.y !== 1 ? 's' : '');
                      }
                    }
                  }
                }
              }
            });
          }
        })
        .catch(error => console.error('Error loading badge distribution chart:', error));
    }

    // Load all data on page load
    document.addEventListener('DOMContentLoaded', function() {
      loadQuickStats();
      loadRecentActivity();
      loadOpenReports();
      loadUserGrowthChart();
      loadBadgeDistributionChart();

      // Refresh every 30 seconds (excluding charts to avoid flicker)
      setInterval(function() {
        loadQuickStats();
        loadRecentActivity();
        loadOpenReports();
      }, 30000);
    });
  </script>
