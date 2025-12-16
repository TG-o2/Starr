<?php
require_once __DIR__ . '/config.php';

try {
    $pdo = new PDO("mysql:host=localhost;dbname=schooldb", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Start transaction
    $pdo->beginTransaction();
    
    // Check if users table exists, if not create it
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `users` (
            `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `username` VARCHAR(50) NOT NULL,
            `email` VARCHAR(100) NOT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
    
    // Check if default admin exists, if not create it
    $stmt = $pdo->query("SELECT id FROM `users` WHERE id = 1");
    if ($stmt->rowCount() === 0) {
        $pdo->exec("
            INSERT INTO `users` (`id`, `username`, `email`) 
            VALUES (1, 'admin', 'admin@example.com')
        ");
    }
    
    echo "Making created_by and updated_by nullable...\n";
    $pdo->exec("ALTER TABLE `lessons` MODIFY `created_by` INT(11) NULL");
    $pdo->exec("ALTER TABLE `lessons` MODIFY `updated_by` INT(11) NULL");
    
    echo "Removing existing foreign key constraints...\n";
    $pdo->exec("ALTER TABLE `lessons` DROP FOREIGN KEY IF EXISTS `fk_lesson_creator`");
    $pdo->exec("ALTER TABLE `lessons` DROP FOREIGN KEY IF EXISTS `fk_lesson_updater`");
    
    // Make the columns NOT NULL with default value of 1
    echo "Updating columns with default values...\n";
    $pdo->exec("ALTER TABLE `lessons` MODIFY `created_by` INT(11) NOT NULL DEFAULT 1");
    $pdo->exec("ALTER TABLE `lessons` MODIFY `updated_by` INT(11) NOT NULL DEFAULT 1");
    
    // Update existing NULL values to 1
    $pdo->exec("UPDATE `lessons` SET `created_by` = 1 WHERE `created_by` IS NULL OR `created_by` = 0");
    $pdo->exec("UPDATE `lessons` SET `updated_by` = 1 WHERE `updated_by` IS NULL OR `updated_by` = 0");
    
    // Add the constraints back
    echo "Adding back foreign key constraints...\n";
    $pdo->exec("
        ALTER TABLE `lessons` 
        ADD CONSTRAINT `fk_lesson_creator` 
        FOREIGN KEY (`created_by`) 
        REFERENCES `users` (`id`) 
        ON DELETE RESTRICT 
        ON UPDATE CASCADE
    ");
    
    $pdo->exec("
        ALTER TABLE `lessons` 
        ADD CONSTRAINT `fk_lesson_updater` 
        FOREIGN KEY (`updated_by`) 
        REFERENCES `users` (`id`) 
        ON DELETE RESTRICT 
        ON UPDATE CASCADE
    ");
    
    $pdo->commit();
    echo "Successfully updated the database schema.\n";
    
    // Delete this file after successful execution
    unlink(__FILE__);
    
} catch (PDOException $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    die("Error: " . $e->getMessage() . "\n");
}