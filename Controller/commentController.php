<?php
require_once('Config.php');
require_once __DIR__ . '/../Model/Comments.php';

class CommentController {
    
    public function getAllComments() {
        $sql = "SELECT * FROM comments ORDER BY created_at DESC";
        $db = Config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute();
            return $query->fetchAll();
        } catch (Exception $e) {
            error_log('Error fetching comments: ' . $e->getMessage());
            return [];
        }
    }
    
    public function addComment($comment) {
    $db = Config::getConnexion();
    $sql = "INSERT INTO comments (news_id, content, created_at) 
            VALUES (:news_id, :content, :created_at)";
    try {
        $query = $db->prepare($sql);
        $success = $query->execute([
            'news_id' => $comment->getNewsId(),
            'content' => $comment->getContent(),
            'created_at' => $comment->getCreatedAt()
        ]);
        
        // Debug: Check if execute worked
        error_log("SQL Execute result: " . ($success ? 'SUCCESS' : 'FAILED'));
        error_log("Last insert ID: " . $db->lastInsertId());
        
        return $success;
    } catch (Exception $e) {
        error_log('Erreur lors de l\'ajout du commentaire: ' . $e->getMessage());
        return false;
    }
}
    
    public function deleteComment($id) {
        $db = Config::getConnexion();
        $sql = "DELETE FROM comments WHERE id = :id"; 
        try {
            $query = $db->prepare($sql);
            $query->execute(['id' => $id]);
            return $query->rowCount() > 0; // true only if a row was actually deleted
        } catch (Exception $e) {
            error_log('Erreur lors de la suppression du commentaire: ' . $e->getMessage());
            return false;
        }
    }
    
    public function getCommentById($id) {
        $db = Config::getConnexion();
        $sql = "SELECT * FROM comments WHERE id = :id";
        try {
            $query = $db->prepare($sql);
            $query->execute(['id' => $id]);
            $result = $query->fetch();
            if ($result) {
                return new Comments(
                    $result['id'],
                    $result['news_id'],
                    $result['content'],
                    $result['created_at']
                );
            }
            return null;
        } catch (Exception $e) {
            error_log("Erreur récupération commentaire par ID: " . $e->getMessage());
            return null;
        }
    }
    
    public function updateComment($id, $content) {
        $db = Config::getConnexion();
        $sql = "UPDATE comments SET 
                content = :content,
                created_at = NOW()
                WHERE id = :id";
        
        try {
            $query = $db->prepare($sql);
            return $query->execute([
                'id' => $id,
                'content' => $content
            ]);
        } catch (Exception $e) {
            error_log('Erreur mise à jour commentaire: ' . $e->getMessage());
            return false;
        }
    }
    
    public function getCommentsByNewsId($news_id) {
        $db = Config::getConnexion();
        $sql = "SELECT * FROM comments WHERE news_id = :news_id ORDER BY created_at DESC";
        try {
            $query = $db->prepare($sql);
            $query->execute(['news_id' => $news_id]);
            return $query->fetchAll();
        } catch (Exception $e) {
            error_log("Erreur récupération commentaires par news_id: " . $e->getMessage());
            return [];
        }
    }
    
    public function getCommentsByContent($content) {
        $db = Config::getConnexion();
        $sql = "SELECT * FROM comments WHERE content LIKE :content ORDER BY created_at DESC";
        try {
            $query = $db->prepare($sql);
            $query->execute(['content' => '%' . $content . '%']);
            return $query->fetchAll();
        } catch (Exception $e) {
            error_log("Erreur récupération commentaires par contenu: " . $e->getMessage());
            return [];
        }
    }
}