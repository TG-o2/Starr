<?php
// config.php - Database configuration
$dbname = 'schooldb';
$username = 'root';
$password = '';

try {
    // Try localhost with socket first (Windows named pipe)
    $dsn = "mysql:host=localhost;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // If that fails, try 127.0.0.1 with explicit port
    try {
        $dsn = "mysql:host=127.0.0.1;port=3306;dbname=$dbname;charset=utf8mb4";
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e2) {
        die("Connection failed: " . $e2->getMessage());
    }
}
?>