<?php
// Original-style add comment (create only)
require_once __DIR__ . '/../../../Controller/Config.php';
require_once __DIR__ . '/../../../Model/Comments.php';
require_once __DIR__ . '/../../../Controller/commentController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $news_id = $_POST['news_id'] ?? '';
    $comment_content = $_POST['comment_content'] ?? '';

    if (!empty($news_id) && !empty($comment_content) && ctype_digit($news_id) && (int)$news_id > 0) {
        $comment = new Comments();
        $comment->setNewsId((int)$news_id);
        $comment->setContent($comment_content);
        $comment->setCreatedAt(date('Y-m-d H:i:s'));

        $commentController = new CommentController();
        $result = $commentController->addComment($comment);
        if ($result) {
            header('Location: gestionnews.php?comment_success=1');
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