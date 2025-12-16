<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../init.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$lessonId = (int)($_GET['lessonId'] ?? 0);
if ($lessonId <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid lesson ID']);
    exit;
}

try {
    $questionModel = new QuestionModel();
    $questions = $questionModel->getByLesson($lessonId);
    
    echo json_encode([
        'success' => true,
        'questions' => $questions
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage()
    ]);
}
?>
