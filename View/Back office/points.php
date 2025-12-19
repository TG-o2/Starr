<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../Controller/StarrPointsController.php';

$db = Config::getConnexion();
$controller = new StarrPointsController($db);

$message = '';
$alert_type = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action === 'create') {
            $starr_id = $_POST['starr_id'] ?? '';
            $total_points = $_POST['total_points'] ?? 0;
            $login_streak = $_POST['login_streak'] ?? 0;
            $last_login_date = $_POST['last_login_date'] ?? date('Y-m-d H:i:s');

            $errors = $controller->validateInput($starr_id, $total_points, $login_streak);
            if (count($errors) > 0) {
                $message = 'Error: ' . implode(', ', $errors);
                $alert_type = 'danger';
            } else {
                $result = $controller->create($starr_id, $total_points, $last_login_date, $login_streak);
                $message = $result['message'];
                $alert_type = $result['success'] ? 'success' : 'danger';
            }
        }

        elseif ($action === 'update') {
            $starr_id = $_POST['starr_id'] ?? '';
            $total_points = $_POST['total_points'] ?? 0;
            $login_streak = $_POST['login_streak'] ?? 0;
            $last_login_date = $_POST['last_login_date'] ?? date('Y-m-d H:i:s');

            $errors = $controller->validateInput($starr_id, $total_points, $login_streak);
            if (count($errors) > 0) {
                $message = 'Error: ' . implode(', ', $errors);
                $alert_type = 'danger';
            } else {
                $result = $controller->update($starr_id, $total_points, $last_login_date, $login_streak);
                $message = $result['message'];
                $alert_type = $result['success'] ? 'success' : 'danger';
            }
        }

        elseif ($action === 'delete') {
            $starr_id = $_POST['starr_id'] ?? '';
            $result = $controller->delete($starr_id);
            $message = $result['message'];
            $alert_type = $result['success'] ? 'success' : 'danger';
        }

        elseif ($action === 'update_streak') {
            $starr_id = $_POST['starr_id'] ?? '';
            $result = $controller->updateLoginStreak($starr_id);
            $message = $result['message'];
            $alert_type = $result['success'] ? 'success' : 'danger';
        }

        elseif ($action === 'reset_streak') {
            $starr_id = $_POST['starr_id'] ?? '';
            $result = $controller->resetLoginStreak($starr_id);
            $message = $result['message'];
            $alert_type = $result['success'] ? 'success' : 'danger';
        }
    }
}

// Get all users
$all_users = $controller->getAll(100, 0);
$users = $all_users['success'] ? $all_users['data'] : [];

// Get leaderboard
$leaderboard = $controller->getLeaderboard(10);
$top_users = $leaderboard['success'] ? $leaderboard['data'] : [];

// Check if editing
$edit_user = null;
if (isset($_GET['edit_id'])) {
    $result = $controller->getById($_GET['edit_id']);
    if ($result['success']) {
        $edit_user = $result['data'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Points Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; padding: 20px; }
        .container { max-width: 1200px; }
        .card { box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075); }
        .user-card { border-left: 4px solid #0d6efd; }
        .badge-primary { background-color: #0d6efd; }
    </style>
</head>
<body>

<div class="container">
    <h1 class="mb-4"><i class="fas fa-star"></i> Points Management</h1>

    <?php if ($message): ?>
        <div class="alert alert-<?php echo $alert_type; ?> alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($message); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row mb-4">
        <!-- Add/Edit Form -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <?php echo $edit_user ? 'Edit User' : 'Add New User'; ?>
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="action" value="<?php echo $edit_user ? 'update' : 'create'; ?>">
                        
                        <div class="mb-3">
                            <label class="form-label">Starr ID *</label>
                            <input type="number" name="starr_id" class="form-control" value="<?php echo $edit_user ? htmlspecialchars($edit_user['starr_id']) : ''; ?>" required <?php echo $edit_user ? 'readonly' : ''; ?>>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Total Points *</label>
                            <input type="number" name="total_points" class="form-control" value="<?php echo $edit_user ? htmlspecialchars($edit_user['total_points']) : ''; ?>" min="0" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Login Streak *</label>
                            <input type="number" name="login_streak" class="form-control" value="<?php echo $edit_user ? htmlspecialchars($edit_user['login_streak']) : ''; ?>" min="0" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Last Login Date *</label>
                            <input type="datetime-local" name="last_login_date" class="form-control" value="<?php echo $edit_user ? date('Y-m-d\TH:i', strtotime($edit_user['last_login_date'])) : ''; ?>" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> <?php echo $edit_user ? 'Update' : 'Add'; ?>
                            </button>
                        </div>

                        <?php if ($edit_user): ?>
                            <a href="points.php" class="btn btn-secondary w-100 mt-2">Cancel</a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>

        <!-- Leaderboard -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-trophy"></i> Top 10 Leaderboard</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <?php if (!empty($top_users)): ?>
                            <?php foreach ($top_users as $idx => $user): ?>
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <strong><?php echo ($idx + 1); ?>. User #<?php echo htmlspecialchars($user['starr_id']); ?></strong>
                                            <br>
                                            <small class="text-muted">Streak: <?php echo htmlspecialchars($user['login_streak']); ?> days</small>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-star"></i> <?php echo htmlspecialchars($user['total_points']); ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted">No leaderboard data available</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- All Users -->
    <div class="card">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="fas fa-users"></i> All Users</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($users)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Starr ID</th>
                                <th>Total Points</th>
                                <th>Login Streak</th>
                                <th>Last Login</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($user['starr_id']); ?></strong></td>
                                    <td><span class="badge bg-warning text-dark"><?php echo htmlspecialchars($user['total_points']); ?></span></td>
                                    <td><?php echo htmlspecialchars($user['login_streak']); ?></td>
                                    <td><?php echo htmlspecialchars(date('M d, Y H:i', strtotime($user['last_login_date']))); ?></td>
                                    <td>
                                        <a href="points.php?edit_id=<?php echo htmlspecialchars($user['starr_id']); ?>" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="starr_id" value="<?php echo htmlspecialchars($user['starr_id']); ?>">
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this user?')">
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
                <p class="text-muted text-center">No users found. Add one to get started!</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
