<?php
// Back-office: lessonAdd.php - Add new lesson
require_once __DIR__ . '/../../init.php';

$controller = new LessonController();
$controller->add($_POST);
?>
