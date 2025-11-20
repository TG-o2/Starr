<?php

require_once __DIR__ . '/../Model/StarrPoints.php';
require_once __DIR__ . '/../config/config.php';

class StarrPointsController {
    private $model;
    private $conn;

    public function __construct($db) {
        $this->model = new StarrPoints($db);
        $this->conn = $db;
    }

    // CREATE - Insert new points record
    public function create($starr_id, $total_points, $last_login_date, $login_streak) {
        $this->model->starr_id = $starr_id;
        $this->model->total_points = (int)$total_points;
        $this->model->last_login_date = $last_login_date;
        $this->model->login_streak = (int)$login_streak;

        if ($this->model->createPoints()) {
            return [
                'success' => true,
                'message' => 'Points record created successfully',
                'starr_id' => $starr_id
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Error creating points record'
            ];
        }
    }

    // READ - Get points by user ID
    public function getById($starr_id) {
        $result = $this->model->getByStarrId($starr_id);
        
        if ($result) {
            return [
                'success' => true,
                'data' => $result
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Points record not found'
            ];
        }
    }

    // READ - Get all points records (for admin)
    public function getAll($limit = 50, $offset = 0) {
        $query = "SELECT * FROM STARR_POINTS ORDER BY total_points DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return [
                'success' => true,
                'data' => $results,
                'count' => count($results)
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Error retrieving records'
            ];
        }
    }

    // UPDATE - Modify points record
    public function update($starr_id, $total_points, $last_login_date, $login_streak) {
        $this->model->starr_id = $starr_id;
        $this->model->total_points = (int)$total_points;
        $this->model->last_login_date = $last_login_date;
        $this->model->login_streak = (int)$login_streak;

        if ($this->model->updatePoints()) {
            return [
                'success' => true,
                'message' => 'Points record updated successfully'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Error updating points record'
            ];
        }
    }

    // DELETE - Remove points record
    public function delete($starr_id) {
        $this->model->starr_id = $starr_id;

        if ($this->model->deletePoints()) {
            return [
                'success' => true,
                'message' => 'Points record deleted successfully'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Error deleting points record'
            ];
        }
    }

    // ADD POINTS - Wrapper for adding points
    public function addPoints($starr_id, $amount) {
        $this->model->starr_id = $starr_id;
        
        if ($this->model->addPoints($amount)) {
            return [
                'success' => true,
                'message' => "Added {$amount} points successfully"
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Error adding points'
            ];
        }
    }

    // SUBTRACT POINTS - Wrapper for subtracting points
    public function subtractPoints($starr_id, $amount) {
        $this->model->starr_id = $starr_id;
        
        if ($this->model->subtractPoints($amount)) {
            return [
                'success' => true,
                'message' => "Subtracted {$amount} points successfully"
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Insufficient points or error occurred'
            ];
        }
    }

    // UPDATE LOGIN STREAK
    public function updateLoginStreak($starr_id) {
        $this->model->starr_id = $starr_id;
        
        if ($this->model->updateLoginStreak()) {
            return [
                'success' => true,
                'message' => 'Login streak updated'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Error updating login streak'
            ];
        }
    }

    // RESET LOGIN STREAK
    public function resetLoginStreak($starr_id) {
        $this->model->starr_id = $starr_id;
        
        if ($this->model->resetLoginStreak()) {
            return [
                'success' => true,
                'message' => 'Login streak reset'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Error resetting login streak'
            ];
        }
    }

    // Get leaderboard (top users by points)
    public function getLeaderboard($limit = 10) {
        $query = "SELECT starr_id, total_points, login_streak FROM STARR_POINTS ORDER BY total_points DESC LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return [
                'success' => true,
                'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Error fetching leaderboard'
            ];
        }
    }

    // Validate input data
    public function validateInput($starr_id, $total_points, $login_streak) {
        $errors = [];

        if (empty($starr_id) || !is_numeric($starr_id)) {
            $errors[] = 'Valid Starr ID is required';
        }

        if (empty($total_points) || !is_numeric($total_points) || $total_points < 0) {
            $errors[] = 'Total points must be a non-negative number';
        }

        if (!is_numeric($login_streak) || $login_streak < 0) {
            $errors[] = 'Login streak must be a non-negative number';
        }

        return $errors;
    }
}

?>
