<?php

require_once __DIR__ . '/../config/config.php';

class QuestionModel
{
    private $connexion;

    public function __construct()
    {
        $this->connexion = Config::getConnexion();
    }

    public function create(array $data): int
    {
        $query = "INSERT INTO questions (lessonId, question, optionA, optionB, optionC, optionD, 
                  goodAnswer, points, difficulty, time_limit, explanation) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->connexion->prepare($query);
        
        $stmt->execute([
            $data['lessonId'],
            $data['question'],
            $data['optionA'],
            $data['optionB'],
            $data['optionC'],
            $data['optionD'],
            $data['goodAnswer'],
            $data['points'] ?? 5,
            $data['difficulty'] ?? 'easy',
            $data['time_limit'] ?? 0,
            $data['explanation'] ?? ''
        ]);

        return (int)$this->connexion->lastInsertId();
    }

    public function getById(int $questionId): ?array
    {
        $query = "SELECT * FROM questions WHERE questionId = ?";
        $stmt = $this->connexion->prepare($query);
        $stmt->execute([$questionId]);
        
        $result = $stmt->fetch();
        return $result ? $result : null;
    }

    public function getByLesson(int $lessonId): array
    {
        $query = "SELECT * FROM questions WHERE lessonId = ? ORDER BY questionId";
        $stmt = $this->connexion->prepare($query);
        $stmt->execute([$lessonId]);
        
        return $stmt->fetchAll();
    }

    public function getAll(array $filters = [], int $limit = 0, int $offset = 0): array
    {
        $query = "SELECT q.*, l.title as lesson_title FROM questions q 
                  LEFT JOIN lessons l ON q.lessonId = l.lessonId 
                  WHERE 1=1";
        $params = [];
        
        if (!empty($filters['lessonId'])) {
            $query .= " AND q.lessonId = ?";
            $params[] = $filters['lessonId'];
        }
        
        if (!empty($filters['difficulty'])) {
            $query .= " AND q.difficulty = ?";
            $params[] = $filters['difficulty'];
        }
        
        if (!empty($filters['search'])) {
            $query .= " AND (q.question LIKE ? OR l.title LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        $query .= " ORDER BY q.questionId DESC";
        
        if ($limit > 0) {
            $query .= " LIMIT " . (int)$limit . " OFFSET " . (int)$offset;
        }
        
        $stmt = $this->connexion->prepare($query);
        $stmt->execute($params);
        
        return $stmt->fetchAll();
    }

    public function update(int $questionId, array $data): bool
    {
        $updates = [];
        $params = [];
        
        foreach ($data as $key => $value) {
            if (in_array($key, ['question', 'optionA', 'optionB', 'optionC', 'optionD', 
                                 'goodAnswer', 'points', 'difficulty', 'time_limit', 'explanation'])) {
                $updates[] = "$key = ?";
                $params[] = $value;
            }
        }
        
        if (empty($updates)) {
            return false;
        }
        
        $params[] = $questionId;
        $query = "UPDATE questions SET " . implode(", ", $updates) . " WHERE questionId = ?";
        
        $stmt = $this->connexion->prepare($query);
        return $stmt->execute($params);
    }

    public function delete(int $questionId): bool
    {
        $query = "DELETE FROM questions WHERE questionId = ?";
        $stmt = $this->connexion->prepare($query);
        return $stmt->execute([$questionId]);
    }

    public function countAll(array $filters = []): int
    {
        $query = "SELECT COUNT(*) as total FROM questions q 
                  LEFT JOIN lessons l ON q.lessonId = l.lessonId 
                  WHERE 1=1";
        $params = [];
        
        if (!empty($filters['lessonId'])) {
            $query .= " AND q.lessonId = ?";
            $params[] = $filters['lessonId'];
        }
        
        if (!empty($filters['difficulty'])) {
            $query .= " AND q.difficulty = ?";
            $params[] = $filters['difficulty'];
        }
        
        if (!empty($filters['search'])) {
            $query .= " AND (q.question LIKE ? OR l.title LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        $stmt = $this->connexion->prepare($query);
        $stmt->execute($params);
        
        $result = $stmt->fetch();
        return (int)($result['total'] ?? 0);
    }

    public function getDifficultySummary(): array
    {
        $query = "SELECT difficulty, COUNT(*) as count FROM questions GROUP BY difficulty";
        $stmt = $this->connexion->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function getQuestionsWithTimeLimit(): array
    {
        $query = "SELECT * FROM questions WHERE time_limit > 0 ORDER BY lessonId";
        $stmt = $this->connexion->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
}

?>
