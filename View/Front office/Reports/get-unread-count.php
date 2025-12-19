<?php
session_start();
require_once __DIR__ . '/../../../Controller/ResponseController.php';
require_once __DIR__ . '/../../../Controller/ReportController.php';

header('Content-Type: application/json');

$userId = $_SESSION['id'] ?? 5;
$adminId = $_SESSION['admin_id'] ?? 4;

try {
    $reportController = new ReportController();
    $responseController = new ResponseController();
    
    // Get all user's reports
    $reports = $reportController->getReportsByReporter($userId);
    $unreadCounts = [];
    
    foreach ($reports as $report) {
        $reportId = $report->getReportId();
        $responses = $responseController->getResponsesByReportId($reportId);
        
        $unreadCount = 0;
        
        // Count unread messages from admin: treat any status not 'read' as unread
        foreach ($responses as $response) {
            if ($response->getResponderId() == $adminId) {
                $status = strtolower($response->getStatus() ?? '');
                if ($status !== 'read') {
                    $unreadCount++;
                }
            }
        }
        
        $unreadCounts[$reportId] = $unreadCount;
    }
    
    echo json_encode(['success' => true, 'unreadCounts' => $unreadCounts]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
