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
$customMessage = $_POST['message'] ?? null;

if (!$reportId || !$action) {
    die("Report ID and action are required.");
}

$responseController = new ResponseController();
$reportController = new ReportController();

// Get all responses for this report
$responses = $responseController->getResponsesByReportId($reportId);

// Find the "Picked Up" response, if any
$pickedUpResponse = null;
foreach ($responses as $resp) {
    if ($resp->getActionTaken() === 'Picked Up') {
        $pickedUpResponse = $resp;
        break;
    }
}

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
$messageText = $customMessage ?: $defaultMessage;

if ($pickedUpResponse) {
    // Update existing "Picked Up" response
    $pickedUpResponse->setActionTaken($actionTaken);
    $pickedUpResponse->setResponseText($messageText);
    $pickedUpResponse->setStatus($status);
    $pickedUpResponse->setAllowUserReply($allowReply);

    $responseController->updateResponse($pickedUpResponse, $pickedUpResponse->getResponseId());
} else {
    // No picked up response? Create new response
    $newResponse = new Response();
    $newResponse->setReportId($reportId);
    $newResponse->setResponderId($adminId);
    $newResponse->setResponseDate(date('Y-m-d H:i:s'));
    $newResponse->setActionTaken($actionTaken);
    $newResponse->setResponseText($messageText);
    $newResponse->setStatus($status);
    $newResponse->setAllowUserReply($allowReply);

    $responseController->addResponse($newResponse);
}

// Update report status
$reportController->updateReportStatus($reportId, $status);

// Redirect back
header("Location: Review-list.php?reportId=$reportId&status=success");
exit;
?>
