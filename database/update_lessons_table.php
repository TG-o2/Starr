<?php
// Database connection
require_once __DIR__ . '/../config.php';

// Reuse the existing PDO connection from config.php
try {
    global $pdo;

    // Start transaction
    $pdo->beginTransaction();

    // Add columns one by one with error handling
    $alterStatements = [
        "ALTER TABLE `lessons` ADD COLUMN IF NOT EXISTS `ageRange` VARCHAR(50) DEFAULT '5-18' AFTER `title`",
        "ALTER TABLE `lessons` ADD COLUMN IF NOT EXISTS `average_age` INT DEFAULT 12 AFTER `ageRange`",
        "ALTER TABLE `lessons` ADD COLUMN IF NOT EXISTS `content` LONGTEXT DEFAULT NULL AFTER `description`",
        "ALTER TABLE `lessons` ADD COLUMN IF NOT EXISTS `category` VARCHAR(100) DEFAULT NULL AFTER `content`",
        "ALTER TABLE `lessons` ADD COLUMN IF NOT EXISTS `difficulty` ENUM('beginner', 'intermediate', 'advanced') DEFAULT 'beginner' AFTER `category`",
        "ALTER TABLE `lessons` ADD COLUMN IF NOT EXISTS `duration` INT DEFAULT 0 AFTER `difficulty`",
        "ALTER TABLE `lessons` ADD COLUMN IF NOT EXISTS `thumbnail_url` VARCHAR(255) DEFAULT NULL AFTER `image`",
        "ALTER TABLE `lessons` ADD COLUMN IF NOT EXISTS `video_url` VARCHAR(255) DEFAULT NULL AFTER `thumbnail_url`",
        "ALTER TABLE `lessons` ADD COLUMN IF NOT EXISTS `is_published` BOOLEAN DEFAULT 0 AFTER `video_url`",
        "ALTER TABLE `lessons` ADD COLUMN IF NOT EXISTS `is_featured` BOOLEAN DEFAULT 0 AFTER `is_published`",
        "ALTER TABLE `lessons` ADD COLUMN IF NOT EXISTS `created_by` INT DEFAULT NULL AFTER `is_featured`",
        "ALTER TABLE `lessons` ADD COLUMN IF NOT EXISTS `updated_by` INT DEFAULT NULL AFTER `created_by`",
        "ALTER TABLE `lessons` ADD COLUMN IF NOT EXISTS `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP AFTER `created_at`"
    ];

    foreach ($alterStatements as $sql) {
        try {
            $pdo->exec($sql);
            echo "Successfully executed: " . substr($sql, 0, 50) . "...\n";
        } catch (PDOException $e) {
            // Skip duplicate column errors
            if (strpos($e->getMessage(), 'duplicate') === false) {
                throw $e;
            }
            echo "Skipped (column likely exists): " . substr($sql, 0, 50) . "...\n";
        }
    }

    // Add foreign key constraints if they don't exist
    $constraints = [
        "fk_lesson_creator" => "ALTER TABLE `lessons` ADD CONSTRAINT `fk_lesson_creator` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL",
        "fk_lesson_updater" => "ALTER TABLE `lessons` ADD CONSTRAINT `fk_lesson_updater` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL"
    ];

    foreach ($constraints as $name => $sql) {
        try {
            $pdo->exec($sql);
            echo "Added constraint: $name\n";
        } catch (PDOException $e) {
            // Skip if constraint already exists
            if (strpos($e->getMessage(), 'already exists') === false) {
                throw $e;
            }
            echo "Skipped constraint (likely exists): $name\n";
        }
    }

    // Add indexes
    $indexes = [
        "idx_lesson_published" => "CREATE INDEX IF NOT EXISTS `idx_lesson_published` ON `lessons` (`is_published`)",
        "idx_lesson_featured" => "CREATE INDEX IF NOT EXISTS `idx_lesson_featured` ON `lessons` (`is_featured`)",
        "idx_lesson_category" => "CREATE INDEX IF NOT EXISTS `idx_lesson_category` ON `lessons` (`category`)",
        "idx_lesson_difficulty" => "CREATE INDEX IF NOT EXISTS `idx_lesson_difficulty` ON `lessons` (`difficulty`)"
    ];

    foreach ($indexes as $name => $sql) {
        try {
            $pdo->exec($sql);
            echo "Created index: $name\n";
        } catch (PDOException $e) {
            // Skip if index already exists
            if (strpos($e->getMessage(), 'duplicate') === false) {
                throw $e;
            }
            echo "Skipped index (likely exists): $name\n";
        }
    }

    $pdo->commit();
    echo "\nAll updates completed successfully!\n";

} catch (PDOException $e) {
    if (isset($pdo)) {
        $pdo->rollBack();
    }
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
