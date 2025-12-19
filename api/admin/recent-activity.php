<?php
header('Content-Type: application/json');

require '../../config/config.php';

try {
    $db = Config::getConnexion();

    // Get recent posts (last 10)
    $posts = $db->query("
        SELECT 'post' as activity_type, id, user_id, subject as action, created_at as timestamp
        FROM posts
        ORDER BY created_at DESC
        LIMIT 10
    ")->fetchAll(PDO::FETCH_ASSOC);

    // Comments table has: id, news_id, content, created_at (no user_id)
    // Skip comments since we don't have user info

    // Get recent reports (last 10)
    $reports = $db->query("
        SELECT 'report' as activity_type, reportId as id, reporterId as user_id, CONCAT('Report #', LPAD(reportId, 3, '0')) as action, reportDate as timestamp
        FROM report
        ORDER BY reportDate DESC
        LIMIT 10
    ")->fetchAll(PDO::FETCH_ASSOC);

    // Merge and sort by timestamp (most recent first)
    $allActivities = array_merge($posts, $reports);
    usort($allActivities, function($a, $b) {
        return strtotime($b['timestamp']) - strtotime($a['timestamp']);
    });

    // Keep only latest 10
    $recentActivities = array_slice($allActivities, 0, 10);

    echo json_encode([
        'success' => true,
        'data' => $recentActivities,
        'count' => count($recentActivities)
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
