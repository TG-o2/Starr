<?php
require_once __DIR__ . '/init.php';

$lessonId = (int) ($_GET['lessonId'] ?? 0);
if ($lessonId <= 0) {
    echo 'Invalid lesson ID';
    exit;
}

(new LessonController())->edit($lessonId, $_POST);
