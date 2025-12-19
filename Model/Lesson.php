<?php

require_once __DIR__ . '/../config/config.php';

class LessonModel
{
    private $connexion;

    public function __construct()
    {
        $this->connexion = Config::getConnexion();
    }

    public function create(array $data): int
    {
        $query = "INSERT INTO lessons (title, description, content, ageRange, category, difficulty, duration, 
                  thumbnail_url, quiz_time_limit, average_age, is_published, is_featured, created_by, updated_at) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        
        $stmt = $this->connexion->prepare($query);
        
        $stmt->execute([
            $data['title'] ?? '',
            $data['description'] ?? '',
            $data['content'] ?? '',
            $data['ageRange'] ?? '',
            $data['category'] ?? 'General',
            $data['difficulty'] ?? 'beginner',
            $data['duration'] ?? 0,
            $data['thumbnail_url'] ?? null,
            $data['quiz_time_limit'] ?? 30,
            $data['average_age'] ?? 12,
            $data['is_published'] ?? 0,
            $data['is_featured'] ?? 0,
            $data['created_by'] ?? 1
        ]);

        return (int)$this->connexion->lastInsertId();
    }

    public function getById(int $lessonId): ?array
    {
        $query = "SELECT * FROM lessons WHERE lessonId = ?";
        $stmt = $this->connexion->prepare($query);
        $stmt->execute([$lessonId]);
        
        $result = $stmt->fetch();
        return $result ? $result : null;
    }

    public function getAll(array $filters = [], int $limit = 0, int $offset = 0): array
    {
        $query = "SELECT * FROM lessons WHERE 1=1";
        $params = [];
        
        if (!empty($filters['category'])) {
            $query .= " AND category = ?";
            $params[] = $filters['category'];
        }
        
        if (!empty($filters['difficulty'])) {
            $query .= " AND difficulty = ?";
            $params[] = $filters['difficulty'];
        }
        
        if (isset($filters['is_published'])) {
            $query .= " AND is_published = ?";
            $params[] = (int)$filters['is_published'];
        }
        
        if (!empty($filters['search'])) {
            $query .= " AND (title LIKE ? OR description LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        $query .= " ORDER BY created_at DESC";
        
        if ($limit > 0) {
            $query .= " LIMIT " . (int)$limit . " OFFSET " . (int)$offset;
        }
        
        $stmt = $this->connexion->prepare($query);
        $stmt->execute($params);
        
        return $stmt->fetchAll();
    }

    public function getFeaturedLessons(int $limit = 3): array
    {
        $query = "SELECT * FROM lessons WHERE is_published = 1 AND is_featured = 1 
                  ORDER BY created_at DESC LIMIT :limit";
        $stmt = $this->connexion->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function getPopularLessons(int $limit = 5): array
    {
        $query = "SELECT l.*, COUNT(q.questionId) as question_count 
                  FROM lessons l 
                  LEFT JOIN questions q ON l.lessonId = q.lessonId
                  WHERE l.is_published = 1
                  GROUP BY l.lessonId 
                  ORDER BY question_count DESC, l.created_at DESC 
                  LIMIT :limit";
        $stmt = $this->connexion->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function getRelatedLessons(int $lessonId, int $limit = 3): array
    {
        $lesson = $this->getById($lessonId);
        if (!$lesson) {
            return [];
        }
        
        $query = "SELECT * FROM lessons 
                  WHERE is_published = 1 
                  AND (category = :category OR difficulty = :difficulty)
                  AND lessonId != :lessonId 
                  ORDER BY created_at DESC 
                  LIMIT :limit";
        
        $stmt = $this->connexion->prepare($query);
        $stmt->bindValue(':category', $lesson['category'] ?? 'General', PDO::PARAM_STR);
        $stmt->bindValue(':difficulty', $lesson['difficulty'] ?? 'beginner', PDO::PARAM_STR);
        $stmt->bindValue(':lessonId', $lessonId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function update(int $lessonId, array $data): bool
    {
        $updates = [];
        $params = [];
        
        foreach ($data as $key => $value) {
            if (in_array($key, ['title', 'description', 'content', 'ageRange', 'category', 'difficulty', 
                                 'duration', 'thumbnail_url', 'quiz_time_limit', 'average_age', 'is_published', 
                                 'is_featured', 'updated_by', 'updated_at'])) {
                $updates[] = "$key = ?";
                $params[] = $value;
            }
        }
        
        if (empty($updates)) {
            return false;
        }
        
        $params[] = $lessonId;
        $query = "UPDATE lessons SET " . implode(", ", $updates) . " WHERE lessonId = ?";
        
        $stmt = $this->connexion->prepare($query);
        return $stmt->execute($params);
    }

    public function delete(int $lessonId): bool
    {
        // Delete questions first (foreign key constraint)
        $queryDeleteQuestions = "DELETE FROM questions WHERE lessonId = ?";
        $stmtDeleteQuestions = $this->connexion->prepare($queryDeleteQuestions);
        $stmtDeleteQuestions->execute([$lessonId]);
        
        // Then delete lesson
        $query = "DELETE FROM lessons WHERE lessonId = ?";
        $stmt = $this->connexion->prepare($query);
        return $stmt->execute([$lessonId]);
    }

    public function countAll(array $filters = []): int
    {
        $query = "SELECT COUNT(*) as total FROM lessons WHERE 1=1";
        $params = [];
        
        if (!empty($filters['category'])) {
            $query .= " AND category = ?";
            $params[] = $filters['category'];
        }
        
        if (!empty($filters['difficulty'])) {
            $query .= " AND difficulty = ?";
            $params[] = $filters['difficulty'];
        }
        
        if (isset($filters['is_published'])) {
            $query .= " AND is_published = ?";
            $params[] = (int)$filters['is_published'];
        }
        
        if (!empty($filters['search'])) {
            $query .= " AND (title LIKE ? OR description LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        $stmt = $this->connexion->prepare($query);
        $stmt->execute($params);
        
        $result = $stmt->fetch();
        return (int)($result['total'] ?? 0);
    }

    public function getCategories(): array
    {
        $query = "SELECT DISTINCT category FROM lessons WHERE category IS NOT NULL AND category != '' ORDER BY category";
        $stmt = $this->connexion->prepare($query);
        $stmt->execute();
        
        $results = $stmt->fetchAll();
        return array_map(function($row) {
            return $row['category'];
        }, $results);
    }

    public function saveLessonMeta(int $lessonId, array $metadata): bool
    {
        $query = "UPDATE lessons SET ";
        $updates = [];
        $params = [];
        
        if (isset($metadata['average_age'])) {
            $updates[] = "average_age = ?";
            $params[] = $metadata['average_age'];
        }
        
        if (isset($metadata['content'])) {
            $updates[] = "content = ?";
            $params[] = $metadata['content'];
        }
        
        if (isset($metadata['category'])) {
            $updates[] = "category = ?";
            $params[] = $metadata['category'];
        }
        
        if (isset($metadata['difficulty'])) {
            $updates[] = "difficulty = ?";
            $params[] = $metadata['difficulty'];
        }
        
        if (isset($metadata['is_published'])) {
            $updates[] = "is_published = ?";
            $params[] = (int)$metadata['is_published'];
        }
        
        if (isset($metadata['is_featured'])) {
            $updates[] = "is_featured = ?";
            $params[] = (int)$metadata['is_featured'];
        }
        
        if (empty($updates)) {
            return true;
        }
        
        $query .= implode(", ", $updates) . ", updated_at = NOW() WHERE lessonId = ?";
        $params[] = $lessonId;
        
        $stmt = $this->connexion->prepare($query);
        return $stmt->execute($params);
    }
}

?>
