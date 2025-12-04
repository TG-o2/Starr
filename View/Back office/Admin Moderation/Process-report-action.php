<?php
session_start();

require_once __DIR__ . '/../../../Controller/ReportController.php';
require_once __DIR__ . '/../../../Model/Report.php';
require_once __DIR__ . '/../../../Controller/ResponseController.php';
require_once __DIR__ . '/../../../Model/Response.php';

// Admin ID
$adminId = $_SESSION['id'] ?? 3;

// Get POST data
$reportId = $_POST['reportId'] ?? null;
$action = $_POST['action'] ?? null;
$customMessage = $_POST['adminMessage'] ?? null;

if (!$reportId || !$action) {
    die("Report ID and action are required.");
}

$responseController = new ResponseController();
$reportController = new ReportController();

// Sanitize inputs
$reportId = intval($reportId);
$action = trim($action);

// Set default message, status, allow_user_reply based on action
switch ($action) {
    case 'reject':
        $actionTaken = 'Rejected';
        $status = 'Rejected';
        $allowReply = 0;
        $defaultMessage = "Your report has been rejected. No action will be taken.";
        break;

    case 'approve':
        $actionTaken = 'Approved';
        $status = 'Approved';
        $allowReply = 0;
        $defaultMessage = "Your report has been approved. No further action required.";
        break;

    case 'escalate':
        $actionTaken = 'Escalated';
        $status = 'Escalated';
        $allowReply = 1;
        $defaultMessage = "Your report has been escalated. You may reply if needed.";
        break;

    case 'request_info':
        $actionTaken = 'Need Info';
        $status = 'Need Info';
        $allowReply = 1;
        $defaultMessage = "More information is required. Please respond.";
        break;

    default:
        die("Invalid action.");
}

// Use admin message if provided
$messageText = trim($customMessage ?: $defaultMessage);

try {
    // Centralized method will update an existing "Picked Up" response or create one.
    $responseController->updatePickedUpResponse($reportId, $actionTaken, $status, $allowReply, $messageText, $adminId);

    // Update report status
    $reportController->updateReportStatus($reportId, $status);

    // Redirect back (use safe encoding). Use `result` to avoid colliding with the `status` filter on Review-list.php
    header('Location: Review-list.php?reportId=' . urlencode((string)$reportId) . '&result=success');
    exit;
} catch (Exception $e) {
    error_log('Process-report-action error: ' . $e->getMessage());
    header('Location: Review-list.php?reportId=' . urlencode((string)$reportId) . '&result=error');
    exit;
}
?>
