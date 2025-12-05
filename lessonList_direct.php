<?php
// Back-office: lessonList.php - View all lessons
require_once __DIR__ . '/../../init.php';

// Handle delete action
if (isset($_GET['delete']) && isset($_GET['lessonId'])) {
    $lessonId = (int)$_GET['lessonId'];
    $controller = new LessonController();
    $controller->delete($lessonId);
}

$controller = new LessonController();
$controller->list();
?>
