<?php
require_once 'config.php';

try {
    $pdo = new PDO("mysql:host=localhost;dbname=schooldb", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get columns from lessons table
    $stmt = $pdo->query("SHOW COLUMNS FROM lessons");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "Columns in lessons table:\n";
    print_r($columns);
    
    // Get first row to check actual data
    $stmt = $pdo->query("SELECT * FROM lessons LIMIT 1");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "\nFirst row data:\n";
    print_r($row);
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
