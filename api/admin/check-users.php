<?php
header('Content-Type: application/json');

require '../../config/config.php';

try {
    $db = Config::getConnexion();

    // Check user table structure
    $userColumns = $db->query("DESCRIBE user")->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'user_columns' => $userColumns
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
