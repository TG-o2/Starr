<?php
require_once __DIR__ . '/init.php';

if (isset($_GET['delete'], $_GET['lessonId'])) {
    $lessonId = (int) $_GET['lessonId'];
    (new LessonController())->delete($lessonId);
}

(new LessonController())->list();
