<?php
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Entities/LessonEntity.php';

class LessonModel {
    private $conn;
    private $table = 'lessons';

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function updateQuizTimeLimit(int $lessonId, int $timeLimit): bool {
        $stmt = $this->conn->prepare(
            "UPDATE {$this->table} SET quiz_time_limit = :timeLimit, updated_at = CURRENT_TIMESTAMP WHERE lessonId = :lessonId"
        );
        $stmt->bindValue(':timeLimit', $timeLimit, PDO::PARAM_INT);
        $stmt->bindValue(':lessonId', $lessonId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getAll(array $filters = [], int $limit = 10, int $offset = 0): array {
        $where = [];
        $params = [];
        
        // Apply filters
        if (!empty($filters['category'])) {
            $where[] = 'category = :category';
            $params[':category'] = $filters['category'];
        }
        
        if (!empty($filters['difficulty'])) {
            $where[] = 'difficulty = :difficulty';
            $params[':difficulty'] = $filters['difficulty'];
        }
        
        if (!empty($filters['search'])) {
            $where[] = '(title LIKE :search OR description LIKE :search)';
            $params[':search'] = "%{$filters['search']}%";
        }

        $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
        
        $query = "SELECT *,
                 (SELECT AVG(rating) FROM lesson_ratings WHERE lesson_id = {$this->table}.lessonId) as average_rating,
                 (SELECT COUNT(*) FROM user_lesson_progress WHERE lesson_id = {$this->table}.lessonId) as enrolled_students
                 FROM {$this->table}
                 $whereClause 
                 ORDER BY created_at DESC 
                 LIMIT :limit OFFSET :offset";
        
        $stmt = $this->conn->prepare($query);
        
        // Bind parameters
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function getById(int $id): ?array {
        $query = "SELECT *,
                 (SELECT AVG(rating) FROM lesson_ratings WHERE lesson_id = {$this->table}.lessonId) as average_rating,
                 (SELECT COUNT(*) FROM user_lesson_progress WHERE lesson_id = {$this->table}.lessonId) as enrolled_students
                 FROM {$this->table}
                 WHERE lessonId = :id 
                 LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':id' => $id]);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function create(array $data): int {
        $query = "INSERT INTO {$this->table} 
                 (title, ageRange, average_age, description, content, category, difficulty, duration, thumbnail_url, video_url, created_by) 
                 VALUES (:title, :ageRange, :average_age, :description, :content, :category, :difficulty, :duration, :thumbnail, :video, :created_by)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->execute([
            ':title' => $data['title'],
            ':ageRange' => $data['ageRange'] ?? '5-18',
            ':average_age' => $data['average_age'] ?? 12,
            ':description' => $data['description'],
            ':content' => $data['content'] ?? '',
            ':category' => $data['category'] ?? 'General',
            ':difficulty' => $data['difficulty'] ?? 'beginner',
            ':duration' => $data['duration'] ?? 0,
            ':thumbnail' => $data['thumbnail_url'] ?? null,
            ':video' => $data['video_url'] ?? null,
            ':created_by' => $data['created_by'] ?? 1 // Default to admin user
        ]);
        
        return (int)$this->conn->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $updates = [];
        $params = [':id' => $id];
        
        $allowedFields = [
            'title', 'description', 'content', 'category', 
            'difficulty', 'duration', 'thumbnail_url', 'video_url', 'is_published',
            'ageRange', 'average_age'
        ];
        
        foreach ($data as $key => $value) {
            if (in_array($key, $allowedFields)) {
                $updates[] = "$key = :$key";
                $params[":$key"] = $value;
            }
        }
        
        if (empty($updates)) {
            return false;
        }
        
        $query = "UPDATE {$this->table} SET " . implode(', ', $updates) . " WHERE lessonId = :id";
        $stmt = $this->conn->prepare($query);
        
        return $stmt->execute($params);
    }

    public function delete(int $id): bool {
        try {
            $this->conn->beginTransaction();
            
            // Delete related records first
            $tables = ['lesson_ratings', 'lesson_comments', 'lesson_resources', 'user_lesson_progress'];
            foreach ($tables as $table) {
                $stmt = $this->conn->prepare("DELETE FROM $table WHERE lesson_id = :id");
                $stmt->execute([':id' => $id]);
            }
            
            // Delete the lesson
            $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE lessonId = :id");
            $result = $stmt->execute([':id' => $id]);
            
            $this->conn->commit();
            return $result;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error deleting lesson $id: " . $e->getMessage());
            return false;
        }
    }

    public function countAll(array $filters = []): int {
        $where = [];
        $params = [];
        
        // Apply filters
        if (!empty($filters['category'])) {
            $where[] = 'category = :category';
            $params[':category'] = $filters['category'];
        }
        
        if (!empty($filters['difficulty'])) {
            $where[] = 'difficulty = :difficulty';
            $params[':difficulty'] = $filters['difficulty'];
        }
        
        $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
        
        $query = "SELECT COUNT(*) as count FROM {$this->table} $whereClause";
        $stmt = $this->conn->prepare($query);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($result['count'] ?? 0);
    }

    public function getCategories(): array {
        $stmt = $this->conn->query("SELECT DISTINCT category FROM {$this->table} WHERE category IS NOT NULL");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getPopularLessons(int $limit = 5): array {
        $query = "SELECT l.*, COUNT(ulp.user_id) as student_count 
                 FROM {$this->table} l
                 LEFT JOIN user_lesson_progress ulp ON l.lessonId = ulp.lesson_id
                 WHERE l.is_published = 1
                 GROUP BY l.lessonId
                 ORDER BY student_count DESC
                 LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFeaturedLessons(int $limit = 3): array {
        $query = "SELECT l.*, AVG(lr.rating) as avg_rating
                 FROM {$this->table} l
                 LEFT JOIN lesson_ratings lr ON l.lessonId = lr.lesson_id
                 WHERE l.is_featured = 1 AND l.is_published = 1
                 GROUP BY l.lessonId
                 ORDER BY l.created_at DESC
                 LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRelatedLessons(int $excludeId, string $category, int $limit = 3): array {
        $query = "SELECT l.*, 
                 (SELECT AVG(rating) FROM lesson_ratings WHERE lesson_id = l.lessonId) as average_rating
                 FROM {$this->table} l
                 WHERE l.lessonId != :excludeId 
                 AND l.category = :category
                 AND l.is_published = 1
                 ORDER BY RAND()
                 LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':excludeId', $excludeId, PDO::PARAM_INT);
        $stmt->bindValue(':category', $category);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function saveLessonMeta(int $lessonId, array $data): bool {
        try {
            $this->conn->beginTransaction();
            
            // Save prerequisites
            if (isset($data['prerequisites']) && is_array($data['prerequisites'])) {
                // Delete existing prerequisites
                $stmt = $this->conn->prepare("DELETE FROM lesson_prerequisites WHERE lesson_id = :lesson_id");
                $stmt->execute([':lesson_id' => $lessonId]);
                
                // Insert new prerequisites
                $stmt = $this->conn->prepare("
                    INSERT INTO lesson_prerequisites (lesson_id, prerequisite_text, display_order)
                    VALUES (:lesson_id, :text, :order)
                ");
                
                foreach ($data['prerequisites'] as $order => $text) {
                    if (!empty(trim($text))) {
                        $stmt->execute([
                            ':lesson_id' => $lessonId,
                            ':text' => $text,
                            ':order' => $order
                        ]);
                    }
                }
            }
            
            // Save learning objectives
            if (isset($data['learning_objectives']) && is_array($data['learning_objectives'])) {
                // Delete existing objectives
                $stmt = $this->conn->prepare("DELETE FROM learning_objectives WHERE lesson_id = :lesson_id");
                $stmt->execute([':lesson_id' => $lessonId]);
                
                // Insert new objectives
                $stmt = $this->conn->prepare("
                    INSERT INTO learning_objectives (lesson_id, objective_text, display_order)
                    VALUES (:lesson_id, :text, :order)
                ");
                
                foreach ($data['learning_objectives'] as $order => $text) {
                    if (!empty(trim($text))) {
                        $stmt->execute([
                            ':lesson_id' => $lessonId,
                            ':text' => $text,
                            ':order' => $order
                        ]);
                    }
                }
            }
            
            // Save sections
            if (isset($data['sections']) && is_array($data['sections'])) {
                // Delete existing sections
                $stmt = $this->conn->prepare("DELETE FROM lesson_sections WHERE lesson_id = :lesson_id");
                $stmt->execute([':lesson_id' => $lessonId]);
                
                // Insert new sections
                $stmt = $this->conn->prepare("
                    INSERT INTO lesson_sections (lesson_id, title, content, display_order, created_at)
                    VALUES (:lesson_id, :title, :content, :order, NOW())
                ");
                
                foreach ($data['sections'] as $section) {
                    if (!empty(trim($section['title']))) {
                        $stmt->execute([
                            ':lesson_id' => $lessonId,
                            ':title' => $section['title'],
                            ':content' => $section['content'] ?? '',
                            ':order' => $section['order'] ?? 0
                        ]);
                    }
                }
            }
            
            $this->conn->commit();
            return true;
            
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error saving lesson meta: " . $e->getMessage());
            return false;
        }
    }
}

?>
<?php

class Lesson
{
    private int $lessonId;
    private string $title;
    private string $ageRange;
    private int $duration;
    private string $description;
    private ?string $image;
    private ?int $teacherId;
    private string $created_at;

    public function __construct(
        int $lessonId = 0,
        string $title = '',
        string $ageRange = '',
        int $duration = 0,
        string $description = '',
        ?string $image = null,
        ?int $teacherId = null,
        string $created_at = ''
    ) {
        $this->lessonId = $lessonId;
        $this->title = $title;
        $this->ageRange = $ageRange;
        $this->duration = $duration;
        $this->description = $description;
        $this->image = $image;
        $this->teacherId = $teacherId;
        $this->created_at = $created_at;
    }



    // =================== GETTERS ===================

    public function getLessonId(): int {
        return $this->lessonId;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function getAgeRange(): string {
        return $this->ageRange;
    }

    public function getDuration(): int {
        return $this->duration;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getImage(): ?string {
        return $this->image;
    }

    public function getTeacherId(): ?int {
        return $this->teacherId;
    }

    public function getCreatedAt(): string {
        return $this->created_at;
    }

    // =================== SETTERS ===================

    public function setTitle(string $title): void {
        $this->title = $title;
    }

    public function setAgeRange(string $ageRange): void {
        $this->ageRange = $ageRange;
    }

    public function setDuration(int $duration): void {
        $this->duration = $duration;
    }

    public function setDescription(string $description): void {
        $this->description = $description;
    }

    public function setImage(?string $image): void {
        $this->image = $image;
    }

    public function setTeacherId(?int $teacherId): void {
        $this->teacherId = $teacherId;
    }
}

?>
