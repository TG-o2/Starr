<?php

class PointsHistory {
    private $conn;
    private $table = 'points_history';

    // Properties
    public $id;
    public $user_id;
    public $source_type;
    public $source_id;
    public $points_change;
    public $description;
    public $created_at;

    // Constructor
    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Create a new points history record
     * @return bool
     */
    public function create() {
        $query = "INSERT INTO " . $this->table . "
                  (user_id, source_type, source_id, points_change, description)
                  VALUES (:user_id, :source_type, :source_id, :points_change, :description)";

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->source_type = htmlspecialchars(strip_tags($this->source_type));
        $this->source_id = isset($this->source_id) ? (int)$this->source_id : null;
        $this->points_change = (int)$this->points_change;
        $this->description = htmlspecialchars(strip_tags($this->description));

        // Bind parameters
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':source_type', $this->source_type);
        $stmt->bindParam(':source_id', $this->source_id);
        $stmt->bindParam(':points_change', $this->points_change);
        $stmt->bindParam(':description', $this->description);

        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    /**
     * Get points history for a user
     * @param int $user_id
     * @param int $limit
     * @return array
     */
    public function getByUserId($user_id, $limit = 50) {
        $query = "SELECT * FROM " . $this->table . "
                  WHERE user_id = :user_id
                  ORDER BY created_at DESC
                  LIMIT :limit";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get total points earned by source type
     * @param int $user_id
     * @param string $source_type
     * @return int
     */
    public function getTotalBySourceType($user_id, $source_type) {
        $query = "SELECT SUM(points_change) as total FROM " . $this->table . "
                  WHERE user_id = :user_id AND source_type = :source_type";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':source_type', $source_type);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($result['total'] ?? 0);
    }

    /**
     * Get breakdown of points by source type
     * @param int $user_id
     * @return array
     */
    public function getPointsBreakdown($user_id) {
        $query = "SELECT 
                    source_type,
                    SUM(points_change) as total_points,
                    COUNT(*) as transaction_count
                  FROM " . $this->table . "
                  WHERE user_id = :user_id
                  GROUP BY source_type
                  ORDER BY total_points DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get recent activity
     * @param int $limit
     * @return array
     */
    public function getRecentActivity($limit = 20) {
        $query = "SELECT ph.*, u.username
                  FROM " . $this->table . " ph
                  LEFT JOIN user u ON ph.user_id = u.user_id
                  ORDER BY ph.created_at DESC
                  LIMIT :limit";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Delete old history records (for maintenance)
     * @param int $days_to_keep
     * @return bool
     */
    public function cleanOldRecords($days_to_keep = 365) {
        $query = "DELETE FROM " . $this->table . "
                  WHERE created_at < DATE_SUB(NOW(), INTERVAL :days DAY)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':days', $days_to_keep, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
