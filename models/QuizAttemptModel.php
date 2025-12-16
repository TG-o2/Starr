<?php
require_once __DIR__ . '/Database.php';

class QuizAttemptModel extends Database {

    public function __construct() {
        parent::__construct(); // $this->conn is initialized
    }

    public function hasAttempted(int $lessonId, string $studentId): bool {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM quiz_attempts WHERE lessonId = ? AND studentId = ?");
        $stmt->execute([$lessonId, $studentId]);
        return $stmt->fetchColumn() > 0;
    }

    public function getAttempt(int $lessonId, string $studentId) {
        $stmt = $this->conn->prepare("SELECT * FROM quiz_attempts WHERE lessonId = ? AND studentId = ? LIMIT 1");
        $stmt->execute([$lessonId, $studentId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function recordAttempt(int $lessonId, int $score, int $totalQuestions, float $scorePercentage, string $studentId) {
        $stmt = $this->conn->prepare("
            INSERT INTO quiz_attempts (lessonId, studentId, totalQuestions, score, scorePercentage, attemptedAt)
            VALUES (?, ?, ?, ?, ?, NOW())
        ");
            return $stmt->execute([$lessonId, $studentId, $totalQuestions, $score, $scorePercentage]);
    }

    /**
     * Saves a quiz attempt with answers
     * 
     * @param array $data Array containing attempt data:
     *   - lesson_id: int - The lesson ID
     *   - student_id: int|string - The student ID
     *   - score: int - Number of correct answers
     *   - total_questions: int - Total number of questions
     *   - answers: array - Array of answers with question IDs and selected options
     *   - attempt_data: array - Additional attempt metadata (optional)
     * 
     * @return int|bool The ID of the inserted attempt or false on failure
     */
    public function saveAttempt(array $data): int|bool {
    try {
        $this->conn->beginTransaction();

        // Insert the main attempt record
        $stmt = $this->conn->prepare("
            INSERT INTO quiz_attempts 
            (lessonId, studentId, score, totalQuestions, scorePercentage, attemptedAt, answers, attempt_data)
            VALUES (?, ?, ?, ?, ?, NOW(), ?, ?)");
        
        $scorePercentage = ($data['score'] / $data['total_questions']) * 100;
        $answersJson = json_encode($data['answers'] ?? []);
        $attemptDataJson = json_encode($data['attempt_data'] ?? []);
        
        $stmt->execute([
            $data['lesson_id'],
            $data['student_id'],
            $data['score'],
            $data['total_questions'],
            $scorePercentage,
            $answersJson,
            $attemptDataJson
        ]);

        $attemptId = $this->conn->lastInsertId();
        
        // If this is a registered user, update their progress
        if (is_numeric($data['student_id'])) {
            $this->updateStudentProgress(
                (int)$data['student_id'],
                (int)$data['lesson_id'],
                $scorePercentage >= 70 // Mark as completed if score is 70% or higher
            );
        }

        $this->conn->commit();
        return (int)$attemptId;
        
    } catch (PDOException $e) {
        $this->conn->rollBack();
        error_log("Error saving quiz attempt: " . $e->getMessage());
        return false;
    }
}

private function updateStudentProgress(int $studentId, int $lessonId, bool $isCompleted): void {
    try {
        // First check if progress record exists
        $stmt = $this->conn->prepare("
            SELECT id FROM student_lesson_progress 
            WHERE student_id = ? AND lesson_id = ?
        ");
        
        $stmt->execute([$studentId, $lessonId]);
        $exists = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$exists) {
            // Insert new progress record
            $stmt = $this->conn->prepare("
                INSERT INTO student_lesson_progress 
                (student_id, lesson_id, is_completed, last_accessed, completed_at)
                VALUES (?, ?, ?, NOW(), " . ($isCompleted ? "NOW()" : "NULL") . ")
            ");
            
            $stmt->execute([
                $studentId, 
                $lessonId, 
                $isCompleted ? 1 : 0
            ]);
        } else {
            // Update existing progress
            $stmt = $this->conn->prepare("
                UPDATE student_lesson_progress 
                SET last_accessed = NOW(), 
                    is_completed = GREATEST(is_completed, ?),
                    completed_at = IF(?, COALESCE(completed_at, NOW()), completed_at)
                WHERE student_id = ? AND lesson_id = ?
            ");
            
            $stmt->execute([
                $isCompleted ? 1 : 0, 
                $isCompleted ? 1 : 0, 
                $studentId, 
                $lessonId
            ]);
        }
    } catch (PDOException $e) {
        error_log("Error updating student progress: " . $e->getMessage());
    }
}

    public function getQuizStats(int $lessonId): array {
    $stmt = $this->conn->prepare("
        SELECT 
            COUNT(*) as total_attempts,
            AVG(scorePercentage) as average_score,
            MAX(scorePercentage) as highest_score,
            MIN(scorePercentage) as lowest_score
        FROM quiz_attempts 
        WHERE lessonId = ?
    ");
    $stmt->execute([$lessonId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return [
        'total_attempts' => (int)($result['total_attempts'] ?? 0),
        'average_score' => round((float)($result['average_score'] ?? 0), 2),
        'highest_score' => (float)($result['highest_score'] ?? 0),
        'lowest_score' => (float)($result['lowest_score'] ?? 0)
    ];
}
}
