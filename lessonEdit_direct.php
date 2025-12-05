<?php
// Back-office: lessonEdit.php - Edit lesson
require_once __DIR__ . '/../../init.php';

$lessonId = (int)($_GET['lessonId'] ?? 0);
if ($lessonId <= 0) {
    echo "Invalid lesson ID";
    exit;
}

$controller = new LessonController();
$controller->edit($lessonId, $_POST);
?>
