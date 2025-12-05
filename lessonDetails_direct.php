<?php
// Front-office: lessonDetails.php - View lesson details
require_once __DIR__ . '/../../init.php';

$lessonId = (int)($_GET['lessonId'] ?? $_GET['id'] ?? 0);
if ($lessonId <= 0) {
    echo "Invalid lesson ID";
    exit;
}

$controller = new LessonController();
$controller->details($lessonId);
?>
