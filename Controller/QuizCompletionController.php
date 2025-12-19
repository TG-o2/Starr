<?php

require_once __DIR__ . '/../Model/QuizCompletion.php';
require_once __DIR__ . '/../Model/PointsHistory.php';
require_once __DIR__ . '/../Model/StarrPoints.php';
require_once __DIR__ . '/../config/config.php';

class QuizCompletionController {
    private $db;
    private $quizCompletionModel;
    private $pointsHistoryModel;
    private $starrPointsModel;

    // Configuration
    private $passingScore = 60; // 60% is passing
    private $retakeMultiplier = 0.5; // 50% points on retakes

    public function __construct() {
        $database = Config::getConnexion();
        $this->db = $database;
        $this->quizCompletionModel = new QuizCompletion($this->db);
        $this->pointsHistoryModel = new PointsHistory($this->db);
        $this->starrPointsModel = new StarrPoints($this->db);
    }

    /**
     * Process quiz completion and award points
     * @param int $user_id
     * @param int $lesson_id
     * @param int $score - Percentage score (0-100)
     * @param int $time_spent - Time in seconds (optional)
     * @return array - Result with success status and details
     */
    public function processQuizCompletion($user_id, $lesson_id, $score, $time_spent = null) {
        try {
            // Start transaction
            $this->db->beginTransaction();

            // Step 1: Get quiz details
            $quizDetails = $this->getQuizDetails($lesson_id);
            if (!$quizDetails) {
                throw new Exception("Quiz/Lesson not found");
            }

            // Step 2: Check if first attempt
            $isFirstAttempt = !$this->quizCompletionModel->hasCompletedBefore($user_id, $lesson_id);

            // Step 3: Calculate points to award
            $pointsToAward = $this->calculatePoints($quizDetails, $score, $isFirstAttempt);

            // Step 4: Determine if passed
            $passed = $score >= $this->passingScore;

            // Step 5: Create quiz completion record
            $this->quizCompletionModel->user_id = $user_id;
            $this->quizCompletionModel->lesson_id = $lesson_id;
            $this->quizCompletionModel->score = $score;
            $this->quizCompletionModel->points_awarded = $pointsToAward;
            $this->quizCompletionModel->passed = $passed;
            $this->quizCompletionModel->is_first_attempt = $isFirstAttempt;
            $this->quizCompletionModel->time_spent = $time_spent;

            if (!$this->quizCompletionModel->create()) {
                throw new Exception("Failed to record quiz completion");
            }

            $completionId = $this->quizCompletionModel->id;

            // Step 6: Award points (only if passed or if first attempt)
            if ($passed || $isFirstAttempt) {
                $this->awardPoints($user_id, $lesson_id, $pointsToAward, $quizDetails['title'], $isFirstAttempt);
            }

            // Commit transaction
            $this->db->commit();

            // Step 7: Get updated user points
            $userPoints = $this->getUserTotalPoints($user_id);

            return [
                'success' => true,
                'completion_id' => $completionId,
                'points_awarded' => $pointsToAward,
                'total_points' => $userPoints,
                'passed' => $passed,
                'is_first_attempt' => $isFirstAttempt,
                'message' => $this->generateMessage($passed, $pointsToAward, $isFirstAttempt)
            ];

        } catch (Exception $e) {
            // Rollback on error
            $this->db->rollBack();
            return [
                'success' => false,
                'message' => 'Error processing quiz: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Calculate points based on quiz difficulty, score, and attempt number
     * @param array $quizDetails
     * @param int $score
     * @param bool $isFirstAttempt
     * @return int
     */
    private function calculatePoints($quizDetails, $score, $isFirstAttempt) {
        $basePoints = (int)$quizDetails['points_value'];

        // For first attempt: full points if passed, proportional if failed
        if ($isFirstAttempt) {
            if ($score >= $this->passingScore) {
                return $basePoints; // Full points
            } else {
                // Proportional points even on failed first attempt (but reduced)
                return (int)($basePoints * ($score / 100) * 0.5);
            }
        }

        // For retakes: 50% of points proportional to score
        return (int)($basePoints * ($score / 100) * $this->retakeMultiplier);
    }

    /**
     * Award points to user
     * @param int $user_id
     * @param int $lesson_id
     * @param int $points
     * @param string $quizTitle
     * @param bool $isFirstAttempt
     */
    private function awardPoints($user_id, $lesson_id, $points, $quizTitle, $isFirstAttempt) {
        // Update user's total points
        $this->starrPointsModel->starr_id = $user_id;
        $this->starrPointsModel->addPoints($points);

        // Record in points history
        $attemptText = $isFirstAttempt ? 'first attempt' : 'retake';
        $this->pointsHistoryModel->user_id = $user_id;
        $this->pointsHistoryModel->source_type = 'quiz';
        $this->pointsHistoryModel->source_id = $lesson_id;
        $this->pointsHistoryModel->points_change = $points;
        $this->pointsHistoryModel->description = "Completed quiz '{$quizTitle}' ({$attemptText})";
        $this->pointsHistoryModel->create();
    }

    /**
     * Get quiz/lesson details including points value
     * @param int $lesson_id
     * @return array|null
     */
    private function getQuizDetails($lesson_id) {
        $query = "SELECT lessonId as id, title, difficulty, points_value FROM lessons WHERE lessonId = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $lesson_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get user's total points
     * @param int $user_id
     * @return int
     */
    private function getUserTotalPoints($user_id) {
        $points = $this->starrPointsModel->getByStarrId($user_id);
        return $points ? (int)$points['total_points'] : 0;
    }

    /**
     * Generate user-friendly message
     * @param bool $passed
     * @param int $points
     * @param bool $isFirstAttempt
     * @return string
     */
    private function generateMessage($passed, $points, $isFirstAttempt) {
        if ($passed && $isFirstAttempt) {
            return "🎉 Congratulations! You passed and earned {$points} Starr points!";
        } elseif ($passed && !$isFirstAttempt) {
            return "✅ Well done! You earned {$points} Starr points on this retake!";
        } elseif (!$passed && $isFirstAttempt) {
            return "Keep trying! You earned {$points} Starr points for your effort.";
        } else {
            return "You earned {$points} Starr points. Keep practicing to improve!";
        }
    }

    /**
     * Get user's quiz history
     * @param int $user_id
     * @return array
     */
    public function getUserQuizHistory($user_id) {
        return $this->quizCompletionModel->getByUserId($user_id);
    }

    /**
     * Get user's quiz statistics
     * @param int $user_id
     * @return array
     */
    public function getUserQuizStats($user_id) {
        return $this->quizCompletionModel->getUserStats($user_id);
    }

    /**
     * Check if user has completed a specific quiz/lesson
     * @param int $user_id
     * @param int $lesson_id
     * @return bool
     */
    public function hasUserCompletedQuiz($user_id, $lesson_id) {
        return $this->quizCompletionModel->hasCompletedBefore($user_id, $lesson_id);
    }

    /**
     * Get user's best score for a quiz/lesson
     * @param int $user_id
     * @param int $lesson_id
     * @return int|null
     */
    public function getUserBestScore($user_id, $lesson_id) {
        return $this->quizCompletionModel->getBestScore($user_id, $lesson_id);
    }

    /**
     * Get leaderboard for a specific quiz/lesson
     * @param int $lesson_id
     * @param int $limit
     * @return array
     */
    public function getQuizLeaderboard($lesson_id, $limit = 10) {
        $query = "SELECT 
                    qc.user_id,
                    u.username,
                    qc.score,
                    qc.points_awarded,
                    qc.completion_date
                  FROM quiz_completions qc
                  LEFT JOIN user u ON qc.user_id = u.user_id
                  WHERE qc.lesson_id = :lesson_id
                  ORDER BY qc.score DESC, qc.completion_date ASC
                  LIMIT :limit";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':lesson_id', $lesson_id);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
