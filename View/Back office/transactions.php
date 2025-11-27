<?php
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Point Transactions Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; padding: 20px; }
        .container { max-width: 1400px; }
        .card { box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075); }
        .transaction-positive { color: #28a745; font-weight: bold; }
        .transaction-negative { color: #dc3545; font-weight: bold; }
    </style>
</head>
<body>

<div class="container">
    <h1 class="mb-4"><i class="fas fa-history"></i> Point Transactions Management</h1>

    <?php if ($message): ?>
        <div class="alert alert-<?php echo $alert_type; ?> alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($message); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
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
                            <label class="form-label">Starr ID *</label>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
