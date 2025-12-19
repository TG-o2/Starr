<?php

class StarrPoints {
    private $conn;
    private $table = 'STARR_POINTS';

    // Properties
    public $starr_id;
    public $total_points;
    public $last_login_date;
    public $login_streak;

    // Constructor
    public function __construct($db) {
        $this->conn = $db;
    }

    // Get user points by Starr ID
    public function getByStarrId($starr_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE starr_id = :starr_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':starr_id', $starr_id);
        
        if ($stmt->execute()) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }

    // Create new user points record
    public function createPoints() {
        $query = "INSERT INTO " . $this->table . "
                  (starr_id, total_points, last_login_date, login_streak)
                  VALUES (:starr_id, :total_points, :last_login_date, :login_streak)";

        $stmt = $this->conn->prepare($query);

        // Sanitize and bind
        $stmt->bindParam(':starr_id', $this->starr_id);
        $stmt->bindParam(':total_points', $this->total_points);
        $stmt->bindParam(':last_login_date', $this->last_login_date);
        $stmt->bindParam(':login_streak', $this->login_streak);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Update user points
    public function updatePoints() {
        $query = "UPDATE " . $this->table . "
                  SET total_points = :total_points,
                      last_login_date = :last_login_date,
                      login_streak = :login_streak
                  WHERE starr_id = :starr_id";

        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':starr_id', $this->starr_id);
        $stmt->bindParam(':total_points', $this->total_points);
        $stmt->bindParam(':last_login_date', $this->last_login_date);
        $stmt->bindParam(':login_streak', $this->login_streak);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Add points to user (transaction wrapper)
    public function addPoints($points_to_add) {
        $query = "UPDATE " . $this->table . "
                  SET total_points = total_points + :points
                  WHERE starr_id = :starr_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':points', $points_to_add);
        $stmt->bindParam(':starr_id', $this->starr_id);

        if ($stmt->execute()) {
            $this->total_points += $points_to_add;
            return true;
        }
        return false;
    }

    // Subtract points from user (transaction wrapper)
    public function subtractPoints($points_to_subtract) {
        $query = "UPDATE " . $this->table . "
                  SET total_points = total_points - :points
                  WHERE starr_id = :starr_id AND total_points >= :points";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':points', $points_to_subtract);
        $stmt->bindParam(':starr_id', $this->starr_id);

        if ($stmt->execute() && $stmt->rowCount() > 0) {
            $this->total_points -= $points_to_subtract;
            return true;
        }
        return false;
    }

    // Update login streak
    public function updateLoginStreak() {
        $query = "UPDATE " . $this->table . "
                  SET last_login_date = NOW(),
                      login_streak = login_streak + 1
                  WHERE starr_id = :starr_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':starr_id', $this->starr_id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Reset login streak (optional - for use when streak is broken)
    public function resetLoginStreak() {
        $query = "UPDATE " . $this->table . "
                  SET login_streak = 0
                  WHERE starr_id = :starr_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':starr_id', $this->starr_id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete user points record
    public function deletePoints() {
        $query = "DELETE FROM " . $this->table . " WHERE starr_id = :starr_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':starr_id', $this->starr_id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
