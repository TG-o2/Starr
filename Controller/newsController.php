<?php
require_once('Config.php');
require_once __DIR__ . '/../Model/News.php';

class NewsController {
    
    public function getAllNews() {
    $sql = "SELECT * FROM news ORDER BY published_date DESC";
    $db = Config::getConnexion();
    try {
        $query = $db->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    } catch (Exception $e) {
        error_log('Error fetching news: ' . $e->getMessage());
        return [];
    }
}
    public function addNews($news) {
    $db = Config::getConnexion();
    $sql = "INSERT INTO news (title, content, published_date, updated_date, status, teacherid, category, image) 
            VALUES (:title, :content, :published_date, :updated_date, :status, :teacherid, :category, :image)";
    try {
        $query = $db->prepare($sql);
        $query->execute([
            'title' => $news->getTitle(),
            'content' => $news->getContent(),
            'published_date' => $news->getPublished_date(),
            'updated_date' => $news->getUpdated_date(),
            'status' => $news->getStatus(),
            'teacherid' => $news->getTeacherid(),
            'category' => $news->getCategory(),
            'image' => $news->getImage()
        ]);
        return true;
    } catch (Exception $e) {
        error_log('Erreur lors de l\'ajout de la news: ' . $e->getMessage());
        return false;
    }
}
    
public function deleteNews($id) {
    $db = Config::getConnexion();
    $sql = "DELETE FROM news WHERE newsid = :id"; 
    try {
        $query = $db->prepare($sql);
        $query->execute(['id' => $id]);
        return true;
    } catch (Exception $e) {
        error_log('Erreur lors de la suppression: ' . $e->getMessage());
        return false;
    }
}
    
    public function getNewsById($id) {
    $db = Config::getConnexion();
    $sql = "SELECT * FROM news WHERE newsid = :id";
    try {
        $query = $db->prepare($sql);
        $query->execute(['id' => $id]);
        return $query->fetch();
    } catch (Exception $e) {
        error_log("Erreur récupération news par ID: " . $e->getMessage());
        return false;
    }
}

public function updateNews($id, $title, $content, $category, $image) {
    $db = Config::getConnexion();
    $sql = "UPDATE news SET 
            title = :title, 
            content = :content, 
            category = :category, 
            image = :image,
            updated_date = NOW()
            WHERE newsid = :id";
    
    try {
        $query = $db->prepare($sql);
        return $query->execute([
            'id' => $id,
            'title' => $title,
            'content' => $content,
            'category' => $category,
            'image' => $image
        ]);
    } catch (Exception $e) {
        error_log('Erreur mise à jour news: ' . $e->getMessage());
        return false;
    }
}
    public function getNewsByNewsId($news_id) {
        $db = Config::getConnexion();
        $sql = "SELECT * FROM news WHERE news_id = :news_id";
        try {
            $query = $db->prepare($sql);
            $query->execute(['news_id' => $news_id]);
            $result = $query->fetch();
            if ($result) {
                return new News(
                    $result['nom'],
                    $result['news_id'],
                    $result['content'],
                    $result['created_at']
                );
            }
            return null;
        } catch (Exception $e) {
            error_log("Erreur récupération news par news_id: " . $e->getMessage());
            return null;
        }
    }
    
    public function getNewsByName($nom) {
        $db = Config::getConnexion();
        $sql = "SELECT * FROM news WHERE nom = :nom";
        try {
            $query = $db->prepare($sql);
            $query->execute(['nom' => $nom]);
            return $query->fetchAll();
        } catch (Exception $e) {
            error_log("Erreur récupération news par nom: " . $e->getMessage());
            return null;
        }
    }
}