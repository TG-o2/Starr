<?php
header('Content-Type: application/json');

require '../../config/config.php';

try {
    $db = Config::getConnexion();

    // Total users (16)
    $users = (int)$db->query("SELECT COUNT(*) FROM user")->fetchColumn();
    
    // Active posts in last 30 days (3)
    $activePosts = (int)$db->query("SELECT COUNT(*) FROM posts WHERE DATE(created_at) >= DATE_SUB(NOW(), INTERVAL 30 DAY)")->fetchColumn();
    
    // Open reports (reportStatus = 'pending') (9 total)
    $openReports = (int)$db->query("SELECT COUNT(*) FROM report WHERE reportStatus = 'pending'")->fetchColumn();
    
    // Pending messages (0)
    $pendingMessages = (int)$db->query("SELECT COUNT(*) FROM messages")->fetchColumn();

    echo json_encode([
        'success' => true,
        'data' => [
            'total_users' => $users,
            'active_posts' => $activePosts,
            'open_reports' => $openReports,
            'pending_messages' => $pendingMessages
        ]
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
