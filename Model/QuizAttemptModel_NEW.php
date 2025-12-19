<?php

require_once __DIR__ . '/../config/config.php';

class QuizAttemptModel
{
    private $connexion;

    public function __construct()
    {
        $this->connexion = Config::getConnexion();
    }

    public function recordAttempt(int $lessonId, int $studentId, int $score, int $totalQuestions, 
                                  int $timeTaken, bool $timedOut = false, array $answers = []): int
    {
        $query = "INSERT INTO quiz_attempts (lesson_id, student_id, score, total_questions, 
                  time_taken, timed_out, answers, completed_at) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        
        $stmt = $this->connexion->prepare($query);
        
        $stmt->execute([
            $lessonId,
            $studentId,
            $score,
            $totalQuestions,
            $timeTaken,
            (int)$timedOut,
            json_encode($answers)
        ]);

        return (int)$this->connexion->lastInsertId();
    }

    public function getAttemptById(int $attemptId): ?array
    {
        $query = "SELECT * FROM quiz_attempts WHERE attempt_id = ?";
        $stmt = $this->connexion->prepare($query);
        $stmt->execute([$attemptId]);
        
        $result = $stmt->fetch();
        if ($result) {
            $result['answers'] = json_decode($result['answers'], true);
        }
        return $result ? $result : null;
    }

    public function getStudentAttempts(int $studentId, int $lessonId = 0): array
    {
        $query = "SELECT * FROM quiz_attempts WHERE student_id = ?";
        $params = [$studentId];
        
        if ($lessonId > 0) {
            $query .= " AND lesson_id = ?";
            $params[] = $lessonId;
        }
        
        $query .= " ORDER BY completed_at DESC";
        
        $stmt = $this->connexion->prepare($query);
        $stmt->execute($params);
        
        $results = $stmt->fetchAll();
        foreach ($results as &$result) {
            $result['answers'] = json_decode($result['answers'], true);
        }
        
        return $results;
    }

    public function getLessonAttempts(int $lessonId): array
    {
        $query = "SELECT * FROM quiz_attempts WHERE lesson_id = ? ORDER BY completed_at DESC";
        $stmt = $this->connexion->prepare($query);
        $stmt->execute([$lessonId]);
        
        $results = $stmt->fetchAll();
        foreach ($results as &$result) {
            $result['answers'] = json_decode($result['answers'], true);
        }
        
        return $results;
    }

    public function getStudentBestAttempt(int $studentId, int $lessonId): ?array
    {
        $query = "SELECT * FROM quiz_attempts 
                  WHERE student_id = ? AND lesson_id = ? 
                  ORDER BY score DESC, completed_at DESC 
                  LIMIT 1";
        
        $stmt = $this->connexion->prepare($query);
        $stmt->execute([$studentId, $lessonId]);
        
        $result = $stmt->fetch();
        if ($result) {
            $result['answers'] = json_decode($result['answers'], true);
        }
        
        return $result ? $result : null;
    }

    public function getStudentAverageScore(int $studentId): float
    {
        $query = "SELECT AVG(CASE WHEN total_questions > 0 THEN (score / total_questions) * 100 ELSE 0 END) as avg_score 
                  FROM quiz_attempts WHERE student_id = ?";
        
        $stmt = $this->connexion->prepare($query);
        $stmt->execute([$studentId]);
        
        $result = $stmt->fetch();
        return (float)($result['avg_score'] ?? 0);
    }

    public function getLessonStatistics(int $lessonId): array
    {
        $query = "SELECT 
                    COUNT(*) as total_attempts,
                    AVG(CASE WHEN total_questions > 0 THEN (score / total_questions) * 100 ELSE 0 END) as avg_score,
                    MAX(CASE WHEN total_questions > 0 THEN (score / total_questions) * 100 ELSE 0 END) as max_score,
                    MIN(CASE WHEN total_questions > 0 THEN (score / total_questions) * 100 ELSE 0 END) as min_score,
                    SUM(CASE WHEN timed_out = 1 THEN 1 ELSE 0 END) as timed_out_count,
                    AVG(time_taken) as avg_time_taken
                  FROM quiz_attempts 
                  WHERE lesson_id = ?";
        
        $stmt = $this->connexion->prepare($query);
        $stmt->execute([$lessonId]);
        
        return $stmt->fetch() ?: [];
    }

    public function deleteAttempt(int $attemptId): bool
    {
        $query = "DELETE FROM quiz_attempts WHERE attempt_id = ?";
        $stmt = $this->connexion->prepare($query);
        return $stmt->execute([$attemptId]);
    }

    public function deleteStudentAttempts(int $studentId, int $lessonId = 0): bool
    {
        $query = "DELETE FROM quiz_attempts WHERE student_id = ?";
        $params = [$studentId];
        
        if ($lessonId > 0) {
            $query .= " AND lesson_id = ?";
            $params[] = $lessonId;
        }
        
        $stmt = $this->connexion->prepare($query);
        return $stmt->execute($params);
    }
}

?>
