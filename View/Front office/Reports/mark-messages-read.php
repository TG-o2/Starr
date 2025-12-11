<?php
session_start();
require_once __DIR__ . '/../../../Controller/ResponseController.php';
require_once __DIR__ . '/../../../Controller/ReportController.php';

header('Content-Type: application/json');

$reportId = $_POST['reportId'] ?? null;
$userId = $_SESSION['id'] ?? 5;
$adminId = $_SESSION['admin_id'] ?? 4;

if (!$reportId) {
    echo json_encode(['success' => false, 'error' => 'Missing report ID']);
    exit;
}

try {
    // Mark all admin messages for this report as read using direct SQL to avoid controller signature mismatch
    $db = Config::getConnexion();
    $stmt = $db->prepare("UPDATE responses SET status = 'read' WHERE reportId = :rid AND responderId = :adminId");
    $stmt->execute([':rid' => $reportId, ':adminId' => $adminId]);
    
    echo json_encode(['success' => true]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
