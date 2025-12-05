<?php
// Front-office: lessonQuiz.php - Take quiz
require_once __DIR__ . '/../../init.php';

$lessonId = (int)($_GET['lessonId'] ?? $_GET['lesson_id'] ?? $_GET['id'] ?? 0);
if ($lessonId <= 0) {
    echo "Invalid lesson ID";
    exit;
}

$controller = new LessonController();
$controller->quiz($lessonId);
?>
