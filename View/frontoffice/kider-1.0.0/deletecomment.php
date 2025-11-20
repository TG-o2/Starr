<?php
// Synchronous deletion (no AJAX / JSON output)
require_once __DIR__ . '/../../../Controller/Config.php';
require_once __DIR__ . '/../../../Model/Comments.php';
require_once __DIR__ . '/../../../Controller/commentController.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$controller = new CommentController();
$message = 'Invalid comment ID';

if (isset($_POST['comment_id']) && ctype_digit($_POST['comment_id'])) {
    $comment_id = (int)$_POST['comment_id'];
    if ($comment_id > 0 && $controller->deleteComment($comment_id)) {
        $message = 'Comment deleted successfully';
    } else {
        $message = 'Failed to delete comment';
    }
}

$_SESSION['comment_flash'] = $message;
header('Location: gestionnews.php');
exit;
?>