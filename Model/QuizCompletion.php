<?php

class QuizCompletion {
    private $conn;
    private $table = 'quiz_completions';

    // Properties
    public $id;
    public $user_id;
    public $lesson_id;
    public $score;
    public $points_awarded;
    public $completion_date;
    public $passed;
    public $is_first_attempt;
    public $time_spent;

    // Constructor
    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Create a new quiz completion record
     * @return bool
     */
    public function create() {
        $query = "INSERT INTO " . $this->table . "
                  (user_id, lesson_id, score, points_awarded, passed, is_first_attempt, time_spent)
                  VALUES (:user_id, :lesson_id, :score, :points_awarded, :passed, :is_first_attempt, :time_spent)";

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->lesson_id = htmlspecialchars(strip_tags($this->lesson_id));
        $this->score = (int)$this->score;
        $this->points_awarded = (int)$this->points_awarded;
        $this->passed = (bool)$this->passed;
        $this->is_first_attempt = (bool)$this->is_first_attempt;
        $this->time_spent = isset($this->time_spent) ? (int)$this->time_spent : null;

        // Bind parameters
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':lesson_id', $this->lesson_id);
        $stmt->bindParam(':score', $this->score);
        $stmt->bindParam(':points_awarded', $this->points_awarded);
        $stmt->bindParam(':passed', $this->passed, PDO::PARAM_BOOL);
        $stmt->bindParam(':is_first_attempt', $this->is_first_attempt, PDO::PARAM_BOOL);
        $stmt->bindParam(':time_spent', $this->time_spent);

        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    /**
     * Check if user has completed this quiz before
     * @param int $user_id
     * @param int $lesson_id
     * @return bool
     */
    public function hasCompletedBefore($user_id, $lesson_id) {
        $query = "SELECT COUNT(*) as count FROM " . $this->table . "
                  WHERE user_id = :user_id AND lesson_id = :lesson_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':lesson_id', $lesson_id);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    /**
     * Get all completions for a specific user
     * @param int $user_id
     * @return array
     */
    public function getByUserId($user_id) {
        $query = "SELECT qc.*, l.title as quiz_title, l.difficulty
                  FROM " . $this->table . " qc
                  LEFT JOIN lessons l ON qc.lesson_id = l.lessonId
                  WHERE qc.user_id = :user_id
                  ORDER BY qc.completion_date DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get all completions for a specific lesson/quiz
     * @param int $lesson_id
     * @return array
     */
    public function getByLessonId($lesson_id) {
        $query = "SELECT qc.*, u.username
                  FROM " . $this->table . " qc
                  LEFT JOIN user u ON qc.user_id = u.user_id
                  WHERE qc.lesson_id = :lesson_id
                  ORDER BY qc.score DESC, qc.completion_date ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':lesson_id', $lesson_id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get user's best score for a lesson/quiz
     * @param int $user_id
     * @param int $lesson_id
     * @return int|null
     */
    public function getBestScore($user_id, $lesson_id) {
        $query = "SELECT MAX(score) as best_score FROM " . $this->table . "
                  WHERE user_id = :user_id AND lesson_id = :lesson_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':lesson_id', $lesson_id);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['best_score'] ?? null;
    }

    /**
     * Get user statistics
     * @param int $user_id
     * @return array
     */
    public function getUserStats($user_id) {
        $query = "SELECT 
                    COUNT(*) as total_quizzes,
                    SUM(points_awarded) as total_points_from_quizzes,
                    AVG(score) as average_score,
                    SUM(CASE WHEN passed = 1 THEN 1 ELSE 0 END) as passed_count,
                    MAX(completion_date) as last_quiz_date
                  FROM " . $this->table . "
                  WHERE user_id = :user_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get completion count for a specific lesson/quiz
     * @param int $lesson_id
     * @return int
     */
    public function getCompletionCount($lesson_id) {
        $query = "SELECT COUNT(*) as count FROM " . $this->table . "
                  WHERE lesson_id = :lesson_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':lesson_id', $lesson_id);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['count'];
    }
}
