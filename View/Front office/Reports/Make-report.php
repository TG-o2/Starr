<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (empty($_POST['report-reason']) || empty($_POST['report-description'])) {
        echo "Please fill in all required fields.";
        exit;
    }

    // Trim all inputs (removes spaces)
    $userId = trim($_POST['reported-user-id']);
    $postId = trim($_POST['reported-post-id']);
    $commentId = trim($_POST['reported-comment-id']);
    $lessonId = trim($_POST['reported-lesson-id']);

    // Check if ALL are empty
    if ($userId === "" && $postId === "" && $commentId === "" && $lessonId === "") {
        echo "Report must include at least one reported entity.";
        exit;
    }
    if ($userId != "" && ($_POST['report-type'] )!= "User") {
        echo "Report type must match the reported entity.";
        exit;
    }
    if ($postId != "" && ($_POST['report-type'] )!= "Post") {
        echo "Report type must match the reported entity.";
        exit;
    }
    if ($commentId != "" && ($_POST['report-type'] )!= "Comment") {
        echo "Report type must match the reported entity.";
        exit;
    }
    if ($lessonId != "" && ($_POST['report-type'] )!= "Lesson") {
        echo "Report type must match the reported entity.";
        exit;
    }

    echo "Form submitted successfully!";
}
?>
