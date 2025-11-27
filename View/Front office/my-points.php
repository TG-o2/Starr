<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../Controller/PointTransactionController.php';
require_once __DIR__ . '/../../Controller/StarrPointsController.php';

$db = Config::getConnexion();
$transaction_controller = new PointTransactionController($db);
$points_controller = new StarrPointsController($db);

// For this example, we'll use a GET parameter for starr_id
// In a real system, this would come from session authentication
$starr_id = isset($_GET['starr_id']) ? (int)$_GET['starr_id'] : null;

if (!$starr_id) {
    die('<div class="alert alert-danger">No user ID provided. Add ?starr_id=USER_ID to URL.</div>');
}

// Get user's points info
$user_points = $points_controller->getById($starr_id);
$user_data = $user_points['success'] ? $user_points['data'] : null;

if (!$user_data) {
    die('<div class="alert alert-danger">User not found.</div>');
}

// Get user's transactions
$transactions_result = $transaction_controller->getByStarrId($starr_id, 100, 0);
$transactions = $transactions_result['success'] ? $transactions_result['data'] : [];

// Get transaction summary
$summary = $transaction_controller->getTransactionSummary($starr_id);
$summary_data = $summary['success'] ? $summary['data'] : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Points & Transactions - Starr</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; padding: 20px; }
        .container { max-width: 900px; }
        .card { box-shadow: 0 10px 30px rgba(0,0,0,0.2); border: none; }
        .card-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; }
        .stats-box { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .stat-item { text-align: center; padding: 15px; }
        .stat-value { font-size: 28px; font-weight: bold; color: #667eea; }
        .stat-label { font-size: 12px; color: #666; text-transform: uppercase; margin-top: 5px; }
        .transaction-positive { color: #28a745; }
        .transaction-negative { color: #dc3545; }
        .transaction-item { padding: 15px; border-left: 4px solid #667eea; background: #f8f9fa; margin-bottom: 10px; border-radius: 4px; }
        .transaction-item.positive { border-left-color: #28a745; }
        .transaction-item.negative { border-left-color: #dc3545; }
        .transaction-points { font-weight: bold; font-size: 18px; }
        .user-header { background: white; padding: 30px; border-radius: 8px; margin-bottom: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .user-info { display: flex; justify-content: space-between; align-items: center; }
        .user-title { font-size: 24px; font-weight: bold; color: #333; }
        .badge-custom { background: #667eea; color: white; padding: 10px 15px; border-radius: 20px; font-size: 14px; }
    </style>
</head>
<body>

<div class="container">
    <!-- User Header -->
    <div class="user-header">
        <div class="user-info">
            <div>
                <h1 class="user-title"><i class="fas fa-user-circle"></i> User #<?php echo htmlspecialchars($starr_id); ?></h1>
                <p class="text-muted mb-0">Your Points & Transaction History</p>
            </div>
            <div class="text-end">
                <a href="index.html" class="btn btn-outline-secondary">← Back Home</a>
            </div>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="stats-box">
        <div class="row">
            <div class="col-md-3">
                <div class="stat-item">
                    <div class="stat-value"><?php echo htmlspecialchars($user_data['total_points']); ?></div>
                    <div class="stat-label"><i class="fas fa-star"></i> Total Points</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-item">
                    <div class="stat-value"><?php echo htmlspecialchars($user_data['login_streak']); ?></div>
                    <div class="stat-label"><i class="fas fa-fire"></i> Day Streak</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-item">
                    <div class="stat-value"><?php echo $summary_data ? htmlspecialchars($summary_data['total_transactions']) : '0'; ?></div>
                    <div class="stat-label"><i class="fas fa-history"></i> Transactions</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-item">
                    <div class="stat-value"><?php echo htmlspecialchars(date('M d, Y', strtotime($user_data['last_login_date']))); ?></div>
                    <div class="stat-label"><i class="fas fa-calendar"></i> Last Login</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction Summary -->
    <?php if ($summary_data): ?>
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-0">
                <div class="card-body text-center">
                    <h5 class="card-title"><i class="fas fa-plus-circle transaction-positive"></i> Points Earned</h5>
                    <p class="card-text transaction-positive" style="font-size: 24px; font-weight: bold;">
                        +<?php echo htmlspecialchars($summary_data['positive_transactions'] > 0 ? 
                            array_sum(array_filter(array_map(function($t) { return $t['points_change'] > 0 ? $t['points_change'] : 0; }, $transactions))) : 0); ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0">
                <div class="card-body text-center">
                    <h5 class="card-title"><i class="fas fa-minus-circle transaction-negative"></i> Points Deducted</h5>
                    <p class="card-text transaction-negative" style="font-size: 24px; font-weight: bold;">
                        <?php echo htmlspecialchars($summary_data['negative_transactions'] > 0 ? 
                            array_sum(array_filter(array_map(function($t) { return $t['points_change'] < 0 ? $t['points_change'] : 0; }, $transactions))) : 0); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Transactions List -->
    <div class="card">
        <div class="card-header text-white">
            <h5 class="mb-0"><i class="fas fa-list"></i> Transaction History</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($transactions)): ?>
                <?php foreach ($transactions as $transaction): ?>
                    <div class="transaction-item <?php echo ($transaction['points_change'] > 0) ? 'positive' : 'negative'; ?>">
                        <div class="d-flex justify-content-between align-items-start">
                            <div style="flex: 1;">
                                <p class="mb-1" style="font-weight: 600;">
                                    <?php echo htmlspecialchars($transaction['reason']); ?>
                                </p>
                                <small class="text-muted">
                                    <i class="fas fa-user"></i> <?php echo htmlspecialchars($transaction['created_by']); ?> 
                                    | 
                                    <i class="fas fa-calendar"></i> <?php echo htmlspecialchars(date('M d, Y H:i', strtotime($transaction['created_at']))); ?>
                                </small>
                            </div>
                            <div class="text-end">
                                <span class="transaction-points <?php echo ($transaction['points_change'] > 0) ? 'transaction-positive' : 'transaction-negative'; ?>">
                                    <?php echo ($transaction['points_change'] > 0 ? '+' : '') . htmlspecialchars($transaction['points_change']); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle"></i> No transactions yet. Complete tasks and activities to earn points!
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <div style="text-align: center; margin-top: 30px; color: white;">
        <p>Starr Points System © 2025</p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
