<?php

class Badge {
    private $conn;
    private $badges_table = 'BADGE_DEFINITIONS';
    private $user_badges_table = 'USER_BADGES';

    public $badge_id;
    public $badge_name;
    public $tier_level;
    public $min_points;
    public $icon;
    public $color;
    public $description;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all badge definitions
    public function getAllBadges() {
        $query = "SELECT * FROM " . $this->badges_table . " ORDER BY min_points ASC";
        $stmt = $this->conn->prepare($query);
        
        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }

    // Get badges earned by a user
    public function getUserBadges($starr_id) {
        $query = "SELECT b.*, ub.earned_date 
                  FROM " . $this->badges_table . " b
                  INNER JOIN " . $this->user_badges_table . " ub ON b.badge_id = ub.badge_id
                  WHERE ub.starr_id = :starr_id AND ub.is_active = 1
                  ORDER BY b.min_points ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':starr_id', $starr_id);
        
        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }

    // Get achievable badges for a user (based on points)
    public function getAchievableBadges($total_points) {
        $query = "SELECT * FROM " . $this->badges_table . " 
                  WHERE min_points <= :points 
                  ORDER BY min_points DESC LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':points', $total_points);
        
        if ($stmt->execute()) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return null;
    }

    // Award badge to user
    public function awardBadge($starr_id, $badge_id) {
        // Check if already awarded
        $check_query = "SELECT badge_id FROM " . $this->user_badges_table . " 
                        WHERE starr_id = :starr_id AND badge_id = :badge_id";
        $check_stmt = $this->conn->prepare($check_query);
        $check_stmt->bindParam(':starr_id', $starr_id);
        $check_stmt->bindParam(':badge_id', $badge_id);
        $check_stmt->execute();
        
        if ($check_stmt->rowCount() > 0) {
            return false; // Already awarded
        }

        $query = "INSERT INTO " . $this->user_badges_table . " 
                  (starr_id, badge_id, earned_date, is_active) 
                  VALUES (:starr_id, :badge_id, NOW(), 1)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':starr_id', $starr_id);
        $stmt->bindParam(':badge_id', $badge_id);
        
        return $stmt->execute();
    }

    // Get next achievable badge
    public function getNextBadge($total_points) {
        $query = "SELECT * FROM " . $this->badges_table . " 
                  WHERE min_points > :points 
                  ORDER BY min_points ASC LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':points', $total_points);
        
        if ($stmt->execute()) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return null;
    }
}
?>
