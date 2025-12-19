<?php
// Simple content view tracking endpoint
// Usage: GET/POST ?type=news|lesson&id=<int>&user=<optional user id>

require_once __DIR__ . '/../config/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$type = isset($_REQUEST['type']) ? strtolower(trim($_REQUEST['type'])) : '';
$id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;
$user = isset($_REQUEST['user']) ? (int)$_REQUEST['user'] : null;

if (!$type || !$id) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Missing type or id']);
    exit;
}

try {
    $db = Config::getConnexion();
    // Create table if not exists
    $db->exec("CREATE TABLE IF NOT EXISTS content_views (
        id INT AUTO_INCREMENT PRIMARY KEY,
        content_type VARCHAR(32) NOT NULL,
        content_id INT NOT NULL,
        user_id INT NULL,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_type_id (content_type, content_id),
        INDEX idx_created (created_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $stmt = $db->prepare("INSERT INTO content_views (content_type, content_id, user_id) VALUES (:t, :cid, :uid)");
    $stmt->execute([
        ':t' => $type,
        ':cid' => $id,
        ':uid' => $user
    ]);

    echo json_encode(['ok' => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}
