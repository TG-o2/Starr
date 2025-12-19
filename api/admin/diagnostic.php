<?php
header('Content-Type: application/json');

require '../../config/config.php';

try {
    $db = Config::getConnexion();

    // Get all table info
    $tables = $db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    
    // Get user count and sample data
    $userCount = $db->query("SELECT COUNT(*) FROM user")->fetchColumn();
    $users = $db->query("SELECT user_id, username, fname, lname FROM user LIMIT 3")->fetchAll(PDO::FETCH_ASSOC);
    
    // Get post count and column names
    $postCount = $db->query("SELECT COUNT(*) FROM posts")->fetchColumn();
    $postColumns = $db->query("DESCRIBE posts")->fetchAll(PDO::FETCH_ASSOC);
    $postsample = $db->query("SELECT * FROM posts LIMIT 2")->fetchAll(PDO::FETCH_ASSOC);
    
    // Get comment count
    $commentCount = $db->query("SELECT COUNT(*) FROM comments")->fetchColumn();
    
    // Get message count
    $messageCount = $db->query("SELECT COUNT(*) FROM messages")->fetchColumn();
    
    // Get report count and check column names
    $reportCount = $db->query("SELECT COUNT(*) FROM report")->fetchColumn();
    $reportColumns = $db->query("DESCRIBE report")->fetchAll(PDO::FETCH_ASSOC);
    $reportsample = $db->query("SELECT * FROM report LIMIT 2")->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'tables' => $tables,
        'counts' => [
            'users' => (int)$userCount,
            'posts' => (int)$postCount,
            'comments' => (int)$commentCount,
            'messages' => (int)$messageCount,
            'reports' => (int)$reportCount
        ],
        'samples' => [
            'user_columns' => $postColumns,
            'posts' => $users,
            'posts' => $postsample,
            'report_columns' => $reportColumns,
            'report_samples' => $reportsample
        ]
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
}
?>
