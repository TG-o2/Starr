<?php
require_once __DIR__ . '/admin_guard.php';
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../Controller/PointTransactionController.php';

$db = Config::getConnexion();
$controller = new PointTransactionController($db);

$message = '';
$alert_type = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action === 'create') {
            $starr_id = $_POST['starr_id'] ?? '';
            $points_change = $_POST['points_change'] ?? 0;
            $reason = $_POST['reason'] ?? '';
            $created_by = $_POST['created_by'] ?? 'Admin';

            $result = $controller->create($starr_id, $points_change, $reason, $created_by);
            $message = $result['message'];
            $alert_type = $result['success'] ? 'success' : 'danger';
        }

        elseif ($action === 'update') {
            $transaction_id = $_POST['transaction_id'] ?? '';
            $starr_id = $_POST['starr_id'] ?? '';
            $points_change = $_POST['points_change'] ?? 0;
            $reason = $_POST['reason'] ?? '';
            $created_by = $_POST['created_by'] ?? 'Admin';

            $result = $controller->update($transaction_id, $starr_id, $points_change, $reason, $created_by);
            $message = $result['message'];
            $alert_type = $result['success'] ? 'success' : 'danger';
        }

        elseif ($action === 'delete') {
            $transaction_id = $_POST['transaction_id'] ?? '';
            $result = $controller->delete($transaction_id);
            $message = $result['message'];
            $alert_type = $result['success'] ? 'success' : 'danger';
        }
    }
}

// Get all transactions
$all_transactions = $controller->getAll(200, 0);
$transactions = $all_transactions['success'] ? $all_transactions['data'] : [];

// Check if editing
$edit_transaction = null;
if (isset($_GET['edit_id'])) {
    $result = $controller->getById($_GET['edit_id']);
    if ($result['success']) {
        $edit_transaction = $result['data'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Transactions | Admin</title>

  <!-- Custom fonts for this template -->
  <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
  <script src="https://kit.fontawesome.com/9d17856d97.js" crossorigin="anonymous"></script>
  <style>
    .card { box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075); }
    .transaction-positive { color: #28a745; font-weight: bold; }
    .transaction-negative { color: #dc3545; font-weight: bold; }
  </style>
</head>
<body id="page-top">

<!-- Page Wrapper -->
<div id="wrapper">

  <!-- Sidebar -->
  <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="Admin Dashboard/Dashboard.php">
        <div class="sidebar-brand-icon rotate-n-15">
          <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Admin Starr<sup>*</sup></div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item">
        <a class="nav-link" href="Admin Dashboard/Dashboard.php">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span>
        </a>
      </li>

      <!-- Heading -->
      

     

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
            <a class="collapse-item" href="Admin Moderation/Ban.html">Ban</a>
            <a class="collapse-item" href="../Post/Post-backoffice.php">Posts</a>
            <a class="collapse-item" href="User/list_users.php">View Profiles</a>
            <div class="collapse-divider"></div>
          </div>
        </div>
      </li>

      <!-- Nav Item - Charts -->
      <li class="nav-item">
        <a class="nav-link" href="Admin Moderation/Review-list.php">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>View Reports</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="Admin Moderation/Handle-report.php">
          <i class="fa-solid fa-flag"></i>
          <span>Handle Reports</span>
        </a>
      </li>
      <li class="nav-item active">
        <a class="nav-link" href="transactions.php">
          <i class="fas fa-coins"></i>
          <span>Transactions</span>
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
        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">
          <h1 class="h3 mb-4 text-gray-800"><i class="fas fa-history"></i> Point Transactions Management</h1>

          <?php if ($message): ?>
            <div class="alert alert-<?php echo $alert_type; ?> alert-dismissible fade show" role="alert">
              <?php echo htmlspecialchars($message); ?>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
          <?php endif; ?>

          <div class="row mb-4">
        <!-- Add/Edit Transaction Form -->
          <div class="col-md-5">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <?php echo $edit_transaction ? 'Edit Transaction' : 'Add New Transaction'; ?>
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="action" value="<?php echo $edit_transaction ? 'update' : 'create'; ?>">
                        <?php if ($edit_transaction): ?>
                            <input type="hidden" name="transaction_id" value="<?php echo htmlspecialchars($edit_transaction['transaction_id']); ?>">
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label class="form-label">User ID *</label>
                            <input type="number" name="starr_id" class="form-control" value="<?php echo $edit_transaction ? htmlspecialchars($edit_transaction['starr_id']) : ''; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Points Change * (positive or negative)</label>
                            <input type="number" name="points_change" class="form-control" value="<?php echo $edit_transaction ? htmlspecialchars($edit_transaction['points_change']) : ''; ?>" required>
                            <small class="text-muted">Use positive numbers to add points, negative to deduct</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Reason *</label>
                            <textarea name="reason" class="form-control" rows="3" required><?php echo $edit_transaction ? htmlspecialchars($edit_transaction['reason']) : ''; ?></textarea>
                            <small class="text-muted">Why are you adding/deducting these points?</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Created By</label>
                            <input type="text" name="created_by" class="form-control" value="<?php echo $edit_transaction ? htmlspecialchars($edit_transaction['created_by']) : 'Admin'; ?>">
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> <?php echo $edit_transaction ? 'Update Transaction' : 'Create Transaction'; ?>
                            </button>
                        </div>

                        <?php if ($edit_transaction): ?>
                            <a href="transactions.php" class="btn btn-secondary w-100 mt-2">Cancel</a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Panel -->
          <div class="col-md-7">
            <div class="card bg-light">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> How It Works</h5>
                </div>
                <div class="card-body">
                    <p><strong>Point Transactions</strong> track all point changes for users.</p>
                    <ul>
                        <li><span class="transaction-positive">✓ Positive numbers</span> = Add points to user</li>
                        <li><span class="transaction-negative">✗ Negative numbers</span> = Deduct points from user</li>
                    </ul>
                    <p class="mb-0"><strong>Example transactions:</strong></p>
                    <ul>
                        <li>+50: "Completed assignment"</li>
                        <li>-10: "Cheating penalty"</li>
                        <li>+100: "Achieved 7-day streak"</li>
                    </ul>
                    <p class="mt-3 text-muted"><strong>Note:</strong> Creating, updating, or deleting a transaction automatically updates the user's total points in STARR_POINTS.</p>
                </div>
            </div>
        </div>
          </div>

          <!-- All Transactions -->
          <div class="card">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-list"></i> All Transactions (<?php echo count($transactions); ?>)</h5>
        </div>
          <div class="card-body">
            <?php if (!empty($transactions)): ?>
                <div class="table-responsive">
                    <table class="table table-hover table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Starr ID</th>
                                <th>Points Change</th>
                                <th>Reason</th>
                                <th>Created By</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($transactions as $transaction): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($transaction['transaction_id']); ?></td>
                                    <td><strong><?php echo htmlspecialchars($transaction['starr_id']); ?></strong></td>
                                    <td>
                                        <span class="<?php echo ($transaction['points_change'] > 0) ? 'transaction-positive' : 'transaction-negative'; ?>">
                                            <?php echo ($transaction['points_change'] > 0 ? '+' : '') . htmlspecialchars($transaction['points_change']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars(substr($transaction['reason'], 0, 50)); ?></td>
                                    <td><?php echo htmlspecialchars($transaction['created_by']); ?></td>
                                    <td><?php echo htmlspecialchars(date('M d, Y H:i', strtotime($transaction['created_at']))); ?></td>
                                    <td>
                                        <a href="transactions.php?edit_id=<?php echo htmlspecialchars($transaction['transaction_id']); ?>" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="transaction_id" value="<?php echo htmlspecialchars($transaction['transaction_id']); ?>">
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this transaction? Points will be reversed.')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted text-center">No transactions found. Create one to get started!</p>
            <?php endif; ?>
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
            <span>Copyright &copy; Admin Starr</span>
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
  <script src="assets/vendor/jquery/jquery.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="assets/js/sb-admin-2.min.js"></script>

</body>
</html>
