<?php
// Front-office: lessonDisplay.php - Browse lessons
require_once __DIR__ . '/../../init.php';

$controller = new LessonController();
$controller->displayFront();
?>
