<?php
session_start();
require_once __DIR__ . '/../../../Controller/ResponseController.php';
require_once __DIR__ . '/../../../Model/Response.php';

header('Content-Type: application/json');

$reportId = $_POST['reportId'] ?? null;
$message = trim($_POST['user_reply'] ?? '');
$userId = $_SESSION['id'] ?? 5;

if (!$reportId || empty($message)) {
    echo json_encode(['success' => false, 'error' => 'Missing report ID or message']);
    exit;
}

try {
    $responseController = new ResponseController();
    
    // Handle file upload if present
    // Collect multiple attachments
    $attachments = [];
    if (isset($_FILES['attachment'])) {
        $uploadDir = __DIR__ . '/../../../uploads/report_attachments/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $files = $_FILES['attachment'];
        $count = is_array($files['name']) ? count($files['name']) : 0;
        for ($i = 0; $i < $count; $i++) {
            if ($files['error'][$i] === UPLOAD_ERR_OK) {
                $ext = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
                $safeExt = preg_replace('/[^a-zA-Z0-9]/','', $ext);
                $fileName = 'user_' . $userId . '_' . time() . '_' . $i . '.' . $safeExt;
                $targetPath = $uploadDir . $fileName;
                if (move_uploaded_file($files['tmp_name'][$i], $targetPath)) {
                    $attachments[] = 'uploads/report_attachments/' . $fileName;
                }
            }
        }
    }
    
    // Create response object
    $response = new Response();
    $response->setReportId($reportId);
    $response->setResponderId($userId);
    $response->setResponseText($message);
    $response->setResponseDate(date('Y-m-d H:i:s'));
    $response->setStatus('User Reply');
    // Store attachments JSON in actionTaken for now (backward compatible)
    $response->setActionTaken(!empty($attachments) ? json_encode($attachments) : 'None');
    $response->setAllowUserReply(0);
    
    $responseController->addResponse($response);
    
    echo json_encode([
        'success' => true,
        'message' => htmlspecialchars($message),
        'time' => date('g:i A'),
        'attachments' => $attachments,
        'sender' => 'You'
    ]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
