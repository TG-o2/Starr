<?php
// Original-style add comment (create only)
require_once __DIR__ . '/../../../Controller/Config.php';
require_once __DIR__ . '/../../../Model/Comments.php';
require_once __DIR__ . '/../../../Controller/commentController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $news_id = $_POST['news_id'] ?? '';
    $comment_content = $_POST['comment_content'] ?? '';
    $reply_to = $_POST['reply_to'] ?? '';

    if (!empty($news_id) && !empty($comment_content) && ctype_digit($news_id) && (int)$news_id > 0) {
        // If replying to a comment, embed a lightweight marker in content without DB changes
        if ($reply_to !== '' && ctype_digit($reply_to) && (int)$reply_to > 0) {
            $comment_content = 'REPLY_TO:' . (int)$reply_to . '|' . $comment_content;
        }
        $comment = new Comments();
        $comment->setNewsId((int)$news_id);
        $comment->setContent($comment_content);
        $comment->setCreatedAt(date('Y-m-d H:i:s'));

        $commentController = new CommentController();
        $result = $commentController->addComment($comment);
        if ($result) {
            // Differentiate reply success for front-office notifications
            if ($reply_to !== '' && ctype_digit($reply_to) && (int)$reply_to > 0) {
                header('Location: gestionnews.php?reply_success=1&reply_parent=' . (int)$reply_to);
            } else {
                header('Location: gestionnews.php?comment_success=1');
            }
            exit;
        } else {
            header('Location: gestionnews.php?comment_error=1');
            exit;
        }
    } else {
        header('Location: gestionnews.php?comment_error=2');
        exit;
    }
} else {
    header('Location: gestionnews.php');
    exit;
}
?>