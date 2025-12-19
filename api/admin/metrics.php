<?php
require_once __DIR__ . '/../../config/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

try {
    $db = Config::getConnexion();

    // Core entity counts
    $users = (int)$db->query("SELECT COUNT(*) FROM user")->fetchColumn();
    $posts = (int)$db->query("SELECT COUNT(*) FROM posts")->fetchColumn();
    $comments = (int)$db->query("SELECT COUNT(*) FROM comments")->fetchColumn();
    $messages = (int)$db->query("SELECT COUNT(*) FROM messages")->fetchColumn();
    // Points & transactions
    $transactions = (int)$db->query("SELECT COUNT(*) FROM POINT_TRANSACTIONS")->fetchColumn();
    $pointsTotal = (int)$db->query("SELECT COALESCE(SUM(total_points),0) FROM STARR_POINTS")->fetchColumn();

    // Reports totals and by status
    $totalReports = (int)$db->query("SELECT COUNT(*) FROM report")->fetchColumn();
    $statusCounts = [];
    $stmt = $db->query("SELECT reportStatus, COUNT(*) as cnt FROM report GROUP BY reportStatus");
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $statusCounts[$row['reportStatus'] ?: 'unknown'] = (int)$row['cnt'];
    }

    // Content views totals last 30 days
    $db->exec("CREATE TABLE IF NOT EXISTS content_views (
        id INT AUTO_INCREMENT PRIMARY KEY,
        content_type VARCHAR(32) NOT NULL,
        content_id INT NOT NULL,
        user_id INT NULL,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_type_id (content_type, content_id),
        INDEX idx_created (created_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $viewsTotals = [
        'news' => 0,
        'lesson' => 0
    ];
    $stmt2 = $db->prepare("SELECT content_type, COUNT(*) as cnt FROM content_views WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) GROUP BY content_type");
    $stmt2->execute();
    foreach ($stmt2->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $viewsTotals[$row['content_type']] = (int)$row['cnt'];
    }

    echo json_encode([
        'ok' => true,
        'counts' => [
            'users' => $users,
            'posts' => $posts,
            'comments' => $comments,
            'messages' => $messages,
            'transactions' => $transactions,
            'points_total' => $pointsTotal,
        ],
        'reports' => [
            'total' => $totalReports,
            'by_status' => $statusCounts,
        ],
        'views_last_30_days' => $viewsTotals,
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}
