<?php
// Database connection
require_once __DIR__ . '/config.php';

echo "<h2>Updating Database Structure</h2>";
echo "<pre>";

// Disable foreign key checks temporarily
$pdo->exec('SET FOREIGN_KEY_CHECKS=0');

$transactionActive = false;
try {
    // Start transaction only if not in a transaction
    if (!$pdo->inTransaction()) {
        $pdo->beginTransaction();
        $transactionActive = true;
    }

    // Add columns one by one with error handling
    $alterStatements = [
        "ALTER TABLE `lessons` ADD COLUMN IF NOT EXISTS `ageRange` VARCHAR(50) DEFAULT '5-18' AFTER `title`",
        "ALTER TABLE `lessons` ADD COLUMN IF NOT EXISTS `average_age` INT DEFAULT 12 AFTER `ageRange`",
        "ALTER TABLE `lessons` ADD COLUMN IF NOT EXISTS `content` LONGTEXT DEFAULT NULL AFTER `description`",
        "ALTER TABLE `lessons` ADD COLUMN IF NOT EXISTS `category` VARCHAR(100) DEFAULT NULL AFTER `content`",
        "ALTER TABLE `lessons` ADD COLUMN IF NOT EXISTS `difficulty` ENUM('beginner', 'intermediate', 'advanced') DEFAULT 'beginner' AFTER `category`",
        "ALTER TABLE `lessons` ADD COLUMN IF NOT EXISTS `duration` INT DEFAULT 0 AFTER `difficulty`",
        "ALTER TABLE `lessons` ADD COLUMN IF NOT EXISTS `thumbnail_url` VARCHAR(255) DEFAULT NULL AFTER `duration`",
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
            echo "✅ Successfully executed: " . htmlspecialchars(substr($sql, 0, 50)) . "...\n";
        } catch (PDOException $e) {
            // Skip duplicate column errors
            if (strpos($e->getMessage(), 'duplicate') === false) {
                throw $e;
            }
            echo "ℹ️ Skipped (column likely exists): " . htmlspecialchars(substr($sql, 0, 50)) . "...\n";
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
            echo "✅ Added constraint: $name\n";
        } catch (PDOException $e) {
            // Skip if constraint already exists
            if (strpos($e->getMessage(), 'already exists') === false) {
                if (strpos($e->getMessage(), 'Cannot add foreign key constraint') === false) {
                    throw $e;
                }
                echo "⚠️  Could not add foreign key (users table might not exist): $name\n";
            } else {
                echo "ℹ️ Skipped constraint (already exists): $name\n";
            }
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
            echo "✅ Created index: $name\n";
        } catch (PDOException $e) {
            // Skip if index already exists
            if (strpos($e->getMessage(), 'duplicate') === false) {
                throw $e;
            }
            echo "ℹ️ Skipped index (already exists): $name\n";
        }
    }

    if ($transactionActive) {
        $pdo->commit();
    }
    echo "\n✅ All updates completed successfully!\n";

} catch (PDOException $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        try {
            $pdo->rollBack();
        } catch (PDOException $rollbackException) {
            // If rollback fails, just log it and continue
            error_log("Rollback failed: " . $rollbackException->getMessage());
        }
    }
    echo "\n❌ Error: " . htmlspecialchars($e->getMessage()) . "\n";
    
    // Show more detailed error information
    echo "\nError details:\n";
    echo "Code: " . $e->getCode() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    
    // Show the SQL that caused the error if available
    if (isset($sql)) {
        echo "\nFailed SQL: " . htmlspecialchars($sql) . "\n";
    }
    
    exit(1);
}

// Re-enable foreign key checks
$pdo->exec('SET FOREIGN_KEY_CHECKS=1');

echo "</pre>";
echo "<p><a href='./'>Back to application</a></p>";

// Remove this file after successful execution
@unlink(__FILE__);
?>
