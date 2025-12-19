<?php

class PointTransaction {
    private $conn;
    private $table = 'POINT_TRANSACTIONS';

    // Properties
    public $transaction_id;
    public $starr_id;
    public $points_change;
    public $reason;
    public $created_by;
    public $created_at;

    // Constructor
    public function __construct($db) {
        $this->conn = $db;
    }

    // Get transaction by ID
    public function getByTransactionId($transaction_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE transaction_id = :transaction_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':transaction_id', $transaction_id);
        
        if ($stmt->execute()) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }

    // Get all transactions for a user
    public function getByStarrId($starr_id, $limit = 50, $offset = 0) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE starr_id = :starr_id 
                  ORDER BY created_at DESC 
                  LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':starr_id', $starr_id);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return false;
    }

    // Get all transactions (for admin)
    public function getAll($limit = 100, $offset = 0) {
        $query = "SELECT * FROM " . $this->table . " 
                  ORDER BY created_at DESC 
                  LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return false;
    }

    // Create new transaction
    public function createTransaction() {
        $query = "INSERT INTO " . $this->table . "
                  (starr_id, points_change, reason, created_by, created_at)
                  VALUES (:starr_id, :points_change, :reason, :created_by, :created_at)";

        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':starr_id', $this->starr_id);
        $stmt->bindParam(':points_change', $this->points_change);
        $stmt->bindParam(':reason', $this->reason);
        $stmt->bindParam(':created_by', $this->created_by);
        $stmt->bindParam(':created_at', $this->created_at);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Update transaction
    public function updateTransaction() {
        $query = "UPDATE " . $this->table . "
                  SET starr_id = :starr_id,
                      points_change = :points_change,
                      reason = :reason,
                      created_by = :created_by
                  WHERE transaction_id = :transaction_id";

        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':transaction_id', $this->transaction_id);
        $stmt->bindParam(':starr_id', $this->starr_id);
        $stmt->bindParam(':points_change', $this->points_change);
        $stmt->bindParam(':reason', $this->reason);
        $stmt->bindParam(':created_by', $this->created_by);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete transaction
    public function deleteTransaction() {
        $query = "DELETE FROM " . $this->table . " WHERE transaction_id = :transaction_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':transaction_id', $this->transaction_id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Get transaction summary for a user (total points from transactions)
    public function getTransactionSummary($starr_id) {
        $query = "SELECT 
                    SUM(points_change) as total_points_from_transactions,
                    COUNT(*) as total_transactions,
                    SUM(CASE WHEN points_change > 0 THEN 1 ELSE 0 END) as positive_transactions,
                    SUM(CASE WHEN points_change < 0 THEN 1 ELSE 0 END) as negative_transactions
                  FROM " . $this->table . " 
                  WHERE starr_id = :starr_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':starr_id', $starr_id);
        
        if ($stmt->execute()) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }
}
?>
