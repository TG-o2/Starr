<?php
/**
 * Database Migration Script for Education Corner
 * Upgrades the database schema to support enhanced lesson and question features
 * Including: categories, difficulty levels, multimedia support, user tracking, quiz timing
 */

require_once __DIR__ . '/../config/config.php';

try {
    $pdo = Config::getConnexion();
    
    echo "=== Education Corner Database Migration ===\n\n";
    
    // Start transaction
    $pdo->beginTransaction();
    
    // Add columns to lessons table
    $alterStatements = [
        "ALTER TABLE `lessons` ADD COLUMN IF NOT EXISTS `average_age` INT DEFAULT 12 AFTER `ageRange`",
        "ALTER TABLE `lessons` ADD COLUMN IF NOT EXISTS `content` LONGTEXT DEFAULT NULL AFTER `description`",
        "ALTER TABLE `lessons` ADD COLUMN IF NOT EXISTS `category` VARCHAR(100) DEFAULT 'General' AFTER `content`",
        "ALTER TABLE `lessons` ADD COLUMN IF NOT EXISTS `difficulty` ENUM('beginner', 'intermediate', 'advanced') DEFAULT 'beginner' AFTER `category`",
        "ALTER TABLE `lessons` ADD COLUMN IF NOT EXISTS `thumbnail_url` VARCHAR(255) DEFAULT NULL AFTER `image`",
        "ALTER TABLE `lessons` ADD COLUMN IF NOT EXISTS `video_url` VARCHAR(255) DEFAULT NULL AFTER `thumbnail_url`",
        "ALTER TABLE `lessons` ADD COLUMN IF NOT EXISTS `is_published` BOOLEAN DEFAULT 0 AFTER `video_url`",
        "ALTER TABLE `lessons` ADD COLUMN IF NOT EXISTS `is_featured` BOOLEAN DEFAULT 0 AFTER `is_published`",
        "ALTER TABLE `lessons` ADD COLUMN IF NOT EXISTS `quiz_time_limit` INT DEFAULT 30 AFTER `is_featured`",
        "ALTER TABLE `lessons` ADD COLUMN IF NOT EXISTS `created_by` INT DEFAULT NULL AFTER `quiz_time_limit`",
        "ALTER TABLE `lessons` ADD COLUMN IF NOT EXISTS `updated_by` INT DEFAULT NULL AFTER `created_by`",
        "ALTER TABLE `lessons` ADD COLUMN IF NOT EXISTS `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP AFTER `created_at`"
    ];
    
    foreach ($alterStatements as $sql) {
        try {
            $pdo->exec($sql);
            echo "✓ " . substr($sql, 0, 60) . "...\n";
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'duplicate') === false) {
                echo "⚠ " . substr($sql, 0, 60) . "... (Column likely exists)\n";
            } else {
                throw $e;
            }
        }
    }
    
    echo "\n--- Adding columns to questions table ---\n";
    
    // Add columns to questions table
    $questionStatements = [
        "ALTER TABLE `questions` ADD COLUMN IF NOT EXISTS `time_limit` INT DEFAULT 60 COMMENT 'Time limit in seconds for this question' AFTER `points`",
        "ALTER TABLE `questions` ADD COLUMN IF NOT EXISTS `difficulty` ENUM('easy', 'medium', 'hard') DEFAULT 'medium' AFTER `time_limit`",
        "ALTER TABLE `questions` ADD COLUMN IF NOT EXISTS `explanation` LONGTEXT DEFAULT NULL AFTER `difficulty`"
    ];
    
    foreach ($questionStatements as $sql) {
        try {
            $pdo->exec($sql);
            echo "✓ " . substr($sql, 0, 60) . "...\n";
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'duplicate') === false) {
                echo "⚠ " . substr($sql, 0, 60) . "... (Column likely exists)\n";
            } else {
                throw $e;
            }
        }
    }
    
    // Create quiz_attempts table if it doesn't exist
    echo "\n--- Creating quiz attempts tracking table ---\n";
    
    $createQuizAttemptsSQL = "
    CREATE TABLE IF NOT EXISTS `quiz_attempts` (
        `attempt_id` INT AUTO_INCREMENT PRIMARY KEY,
        `lesson_id` INT NOT NULL,
        `student_id` INT,
        `score` INT DEFAULT 0,
        `total_questions` INT DEFAULT 0,
        `time_taken` INT DEFAULT 0,
        `timed_out` BOOLEAN DEFAULT FALSE,
        `answers` LONGTEXT,
        `completed_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (`lesson_id`) REFERENCES `lessons`(`lessonId`) ON DELETE CASCADE,
        INDEX `idx_lesson_id` (`lesson_id`),
        INDEX `idx_student_id` (`student_id`),
        INDEX `idx_completed_at` (`completed_at`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    try {
        $pdo->exec($createQuizAttemptsSQL);
        echo "✓ Created quiz_attempts table\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'already exists') === false) {
            echo "⚠ quiz_attempts table likely already exists\n";
        } else {
            throw $e;
        }
    }
    
    // Add indexes
    echo "\n--- Creating indexes ---\n";
    
    $indexes = [
        "idx_lesson_published" => "CREATE INDEX IF NOT EXISTS `idx_lesson_published` ON `lessons` (`is_published`)",
        "idx_lesson_featured" => "CREATE INDEX IF NOT EXISTS `idx_lesson_featured` ON `lessons` (`is_featured`)",
        "idx_lesson_category" => "CREATE INDEX IF NOT EXISTS `idx_lesson_category` ON `lessons` (`category`)",
        "idx_lesson_difficulty" => "CREATE INDEX IF NOT EXISTS `idx_lesson_difficulty` ON `lessons` (`difficulty`)",
        "idx_question_difficulty" => "CREATE INDEX IF NOT EXISTS `idx_question_difficulty` ON `questions` (`difficulty`)"
    ];
    
    foreach ($indexes as $name => $sql) {
        try {
            $pdo->exec($sql);
            echo "✓ Created index: $name\n";
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'duplicate') === false) {
                echo "⚠ Index $name likely already exists\n";
            } else {
                throw $e;
            }
        }
    }
    
    $pdo->commit();
    echo "\n✅ Database migration completed successfully!\n";
    
} catch (PDOException $e) {
    if (isset($pdo)) {
        $pdo->rollBack();
    }
    echo "\n❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
