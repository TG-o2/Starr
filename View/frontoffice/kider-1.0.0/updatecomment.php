<?php
// Synchronous update 
require_once __DIR__ . '/../../../Controller/Config.php';
require_once __DIR__ . '/../../../Model/Comments.php';
require_once __DIR__ . '/../../../Controller/commentController.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$message = 'Invalid update request';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment_id = $_POST['comment_id'] ?? '';
    $comment_content = trim($_POST['comment_content'] ?? '');
    if ($comment_id !== '' && ctype_digit($comment_id) && (int)$comment_id > 0 && $comment_content !== '') {
        $commentController = new CommentController();
        if ($commentController->updateComment((int)$comment_id, $comment_content)) {
            $message = 'Comment updated successfully';
        } else {
            $message = 'Failed to update comment';
        }
    } else {
        $message = 'All fields are required';
    }
}

$_SESSION['comment_flash'] = $message;
header('Location: gestionnews.php');
exit;
?>