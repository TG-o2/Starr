<?php
header('Content-Type: application/json');

require '../../config/config.php';

try {
    $db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (empty($input['report_id']) || empty($input['action'])) {
        throw new Exception('Missing report_id or action');
    }

    $reportId = (int)$input['report_id'];
    $action = $input['action']; // 'resolved' or 'rejected'

    // Validate action
    if (!in_array($action, ['resolved', 'rejected'])) {
        throw new Exception('Invalid action. Must be "resolved" or "rejected"');
    }

    // Update report status
    $stmt = $db->prepare("UPDATE report SET report_status = :status WHERE report_id = :id");
    $stmt->execute([
        ':status' => $action,
        ':id' => $reportId
    ]);

    if ($stmt->rowCount() > 0) {
        echo json_encode([
            'success' => true,
            'message' => 'Report ' . $action . ' successfully',
            'report_id' => $reportId
        ]);
    } else {
        throw new Exception('Report not found or no changes made');
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
