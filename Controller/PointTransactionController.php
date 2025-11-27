<?php

require_once __DIR__ . '/../Model/PointTransaction.php';
require_once __DIR__ . '/../Model/StarrPoints.php';
require_once __DIR__ . '/../config/config.php';

class PointTransactionController {
    private $model;
    private $starr_points_model;
    private $conn;

    public function __construct($db) {
        $this->model = new PointTransaction($db);
        $this->starr_points_model = new StarrPoints($db);
        $this->conn = $db;
    }

    // CREATE - Add new transaction and update user points
    public function create($starr_id, $points_change, $reason, $created_by) {
        $this->model->starr_id = $starr_id;
        $this->model->points_change = (int)$points_change;
        $this->model->reason = $reason;
        $this->model->created_by = $created_by;
        $this->model->created_at = date('Y-m-d H:i:s');

        // Validate input
        $errors = $this->validateInput($starr_id, $points_change, $reason);
        if (count($errors) > 0) {
            return [
                'success' => false,
                'message' => 'Validation error: ' . implode(', ', $errors)
            ];
        }

        // Create transaction
        if ($this->model->createTransaction()) {
            // Update user's total points
            $user_points = $this->starr_points_model->getByStarrId($starr_id);
            if ($user_points) {
                $this->starr_points_model->starr_id = $starr_id;
                $this->starr_points_model->total_points = $user_points['total_points'] + $points_change;
                $this->starr_points_model->last_login_date = $user_points['last_login_date'];
                $this->starr_points_model->login_streak = $user_points['login_streak'];
                $this->starr_points_model->updatePoints();
            }

            return [
                'success' => true,
                'message' => 'Transaction created successfully'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Error creating transaction'
            ];
        }
    }

    // READ - Get transaction by ID
    public function getById($transaction_id) {
        $result = $this->model->getByTransactionId($transaction_id);
        if ($result) {
            return [
                'success' => true,
                'data' => $result
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Transaction not found'
            ];
        }
    }

    // READ - Get all transactions for a user
    public function getByStarrId($starr_id, $limit = 50, $offset = 0) {
        $result = $this->model->getByStarrId($starr_id, $limit, $offset);
        if ($result) {
            return [
                'success' => true,
                'data' => $result,
                'count' => count($result)
            ];
        } else {
            return [
                'success' => false,
                'message' => 'No transactions found'
            ];
        }
    }

    // READ - Get all transactions (admin)
    public function getAll($limit = 100, $offset = 0) {
        $result = $this->model->getAll($limit, $offset);
        if ($result !== false) {
            return [
                'success' => true,
                'data' => $result,
                'count' => count($result)
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Error retrieving transactions'
            ];
        }
    }

    // UPDATE - Modify transaction
    public function update($transaction_id, $starr_id, $points_change, $reason, $created_by) {
        // Get old transaction to reverse points
        $old_transaction = $this->model->getByTransactionId($transaction_id);
        if (!$old_transaction) {
            return [
                'success' => false,
                'message' => 'Transaction not found'
            ];
        }

        // Validate input
        $errors = $this->validateInput($starr_id, $points_change, $reason);
        if (count($errors) > 0) {
            return [
                'success' => false,
                'message' => 'Validation error: ' . implode(', ', $errors)
            ];
        }

        $this->model->transaction_id = $transaction_id;
        $this->model->starr_id = $starr_id;
        $this->model->points_change = (int)$points_change;
        $this->model->reason = $reason;
        $this->model->created_by = $created_by;

        if ($this->model->updateTransaction()) {
            // Reverse old points change and apply new one
            $user_points = $this->starr_points_model->getByStarrId($starr_id);
            if ($user_points) {
                $this->starr_points_model->starr_id = $starr_id;
                $new_total = $user_points['total_points'] - $old_transaction['points_change'] + $points_change;
                $this->starr_points_model->total_points = $new_total;
                $this->starr_points_model->last_login_date = $user_points['last_login_date'];
                $this->starr_points_model->login_streak = $user_points['login_streak'];
                $this->starr_points_model->updatePoints();
            }

            return [
                'success' => true,
                'message' => 'Transaction updated successfully'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Error updating transaction'
            ];
        }
    }

    // DELETE - Remove transaction and adjust points
    public function delete($transaction_id) {
        $transaction = $this->model->getByTransactionId($transaction_id);
        if (!$transaction) {
            return [
                'success' => false,
                'message' => 'Transaction not found'
            ];
        }

        $this->model->transaction_id = $transaction_id;
        
        if ($this->model->deleteTransaction()) {
            // Reverse the points change
            $user_points = $this->starr_points_model->getByStarrId($transaction['starr_id']);
            if ($user_points) {
                $this->starr_points_model->starr_id = $transaction['starr_id'];
                $this->starr_points_model->total_points = $user_points['total_points'] - $transaction['points_change'];
                $this->starr_points_model->last_login_date = $user_points['last_login_date'];
                $this->starr_points_model->login_streak = $user_points['login_streak'];
                $this->starr_points_model->updatePoints();
            }

            return [
                'success' => true,
                'message' => 'Transaction deleted and points adjusted'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Error deleting transaction'
            ];
        }
    }

    // Get transaction summary for a user
    public function getTransactionSummary($starr_id) {
        $result = $this->model->getTransactionSummary($starr_id);
        if ($result) {
            return [
                'success' => true,
                'data' => $result
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Error retrieving transaction summary'
            ];
        }
    }

    // Validate input
    public function validateInput($starr_id, $points_change, $reason) {
        $errors = [];
        if (empty($starr_id) || !is_numeric($starr_id)) {
            $errors[] = 'Valid Starr ID is required';
        }
        if (!is_numeric($points_change)) {
            $errors[] = 'Points change must be a number (positive or negative)';
        }
        if (empty($reason) || strlen($reason) < 3) {
            $errors[] = 'Reason must be at least 3 characters';
        }
        return $errors;
    }
}
?>
