<?php
// Back-office: questionList.php - View all questions
require_once __DIR__ . '/../../init.php';

// Handle delete action
if (isset($_GET['delete']) && isset($_GET['questionId'])) {
    $questionId = (int)$_GET['questionId'];
    $controller = new QuestionController();
    $controller->delete($questionId);
}

$controller = new QuestionController();
$controller->list();
?>
