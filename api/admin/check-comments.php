<?php
header('Content-Type: application/json');

require '../../config/config.php';

try {
    $db = Config::getConnexion();

    // Check comments table structure
    $commentColumns = $db->query("DESCRIBE comments")->fetchAll(PDO::FETCH_ASSOC);
    $commentSample = $db->query("SELECT * FROM comments LIMIT 1")->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'comment_columns' => $commentColumns,
        'comment_sample' => $commentSample
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
