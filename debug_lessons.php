<?php
require_once 'init.php';

$lessonModel = new LessonModel();
$lessons = $lessonModel->getAll();

echo "<pre>";
foreach ($lessons as $lesson) {
    echo 'Lesson ID: ' . $lesson['lessonId'] . "\n";
    echo 'Title: ' . $lesson['title'] . "\n";
    echo 'Thumbnail URL: ' . ($lesson['thumbnail_url'] ?? 'NULL') . "\n";
    echo '----------------------------------------' . "\n";
}
echo "</pre>";
?>
