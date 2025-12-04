<?php
session_start();

require_once __DIR__ . '/../../../Controller/ResponseController.php';
require_once __DIR__ . '/../../../Model/Response.php';


if (!isset($_SESSION['id'])) {
    $_SESSION['id'] = 5;  
}
$userId = $_SESSION['id'];
$reportId = $_POST['reportId'];
$userMessage = trim($_POST['user_reply']);


if (!$userMessage) {
    die("Message cannot be empty");
}

$response = new Response();
$response->setReportId($reportId);
$response->setResponderId($userId);
$response->setResponseText($userMessage);
$response->setResponseDate(date('Y-m-d H:i:s'));
$response->setActionTaken("User Reply");
$response->setStatus("user_replied");
$response->setAllowUserReply(0); // user never controls reply permission

$controller = new ResponseController();
$controller->addResponse($response);

// redirect back to messages page
header("Location: Messages.php?reportId=" . $reportId);
exit;
?>