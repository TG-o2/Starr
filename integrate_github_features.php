#!/usr/bin/env php
<?php
/**
 * Education Corner Integration Script
 * This script helps replace old files with new enhanced versions
 * 
 * Usage: php integrate_github_features.php [--confirm]
 * Without --confirm, it will show what would be done
 */

$confirm = in_array('--confirm', $argv);

// Define file replacements
$replacements = [
    'Controller' => [
        'LessonController.php' => 'LessonController_NEW.php',
        'QuestionController.php' => 'QuestionController_NEW.php',
    ],
    'Model' => [
        'Lesson.php' => 'Lesson_NEW.php',
        'Question.php' => 'Question_NEW.php',
    ]
];

$newFiles = [
    'Model' => [
        'QuizAttemptModel_NEW.php' => 'QuizAttemptModel.php',
    ]
];

$baseDir = __DIR__;
$colors = [
    'success' => "\033[32m",    // Green
    'warning' => "\033[33m",    // Yellow
    'error' => "\033[31m",      // Red
    'info' => "\033[36m",       // Cyan
    'reset' => "\033[0m",       // Reset
];

echo "{$colors['info']}===== Education Corner GitHub Integration ====={$colors['reset']}\n\n";

if (!$confirm) {
    echo "{$colors['warning']}DRY RUN MODE - No changes will be made{$colors['reset']}\n";
    echo "Run with --confirm flag to apply changes\n\n";
}

$totalChanges = 0;
$successCount = 0;

// Process replacements
echo "FILE REPLACEMENTS:\n";
echo str_repeat("-", 50) . "\n";

foreach ($replacements as $dir => $files) {
    $dirPath = $baseDir . DIRECTORY_SEPARATOR . $dir;
    
    if (!is_dir($dirPath)) {
        echo "{$colors['error']}✗ Directory not found: $dir{$colors['reset']}\n";
        continue;
    }
    
    foreach ($files as $oldFile => $newFile) {
        $oldPath = $dirPath . DIRECTORY_SEPARATOR . $oldFile;
        $newPath = $dirPath . DIRECTORY_SEPARATOR . $newFile;
        $backupPath = $dirPath . DIRECTORY_SEPARATOR . $oldFile . '.backup';
        
        $totalChanges++;
        
        if (!file_exists($newPath)) {
            echo "{$colors['error']}✗ $dir/$oldFile ← $newFile (NEW FILE NOT FOUND){$colors['reset']}\n";
            continue;
        }
        
        if (!file_exists($oldPath)) {
            echo "{$colors['warning']}? $dir/$oldFile ← $newFile (OLD FILE NOT FOUND){$colors['reset']}\n";
            continue;
        }
        
        if ($confirm) {
            // Create backup
            if (copy($oldPath, $backupPath)) {
                // Replace with new version
                if (copy($newPath, $oldPath)) {
                    echo "{$colors['success']}✓ $dir/$oldFile ← $newFile{$colors['reset']}\n";
                    echo "  Backup saved: $oldFile.backup\n";
                    $successCount++;
                } else {
                    echo "{$colors['error']}✗ Failed to copy new file{$colors['reset']}\n";
                }
            } else {
                echo "{$colors['error']}✗ Failed to create backup{$colors['reset']}\n";
            }
        } else {
            echo "{$colors['info']}→ $dir/$oldFile ← $newFile (would replace){$colors['reset']}\n";
            $successCount++;
        }
    }
}

echo "\n";

// Process new files
echo "NEW FILES:\n";
echo str_repeat("-", 50) . "\n";

foreach ($newFiles as $dir => $files) {
    $dirPath = $baseDir . DIRECTORY_SEPARATOR . $dir;
    
    if (!is_dir($dirPath)) {
        echo "{$colors['error']}✗ Directory not found: $dir{$colors['reset']}\n";
        continue;
    }
    
    foreach ($files as $sourceFile => $targetFile) {
        $sourcePath = $dirPath . DIRECTORY_SEPARATOR . $sourceFile;
        $targetPath = $dirPath . DIRECTORY_SEPARATOR . $targetFile;
        
        $totalChanges++;
        
        if (!file_exists($sourcePath)) {
            echo "{$colors['error']}✗ $dir/$sourceFile → $targetFile (SOURCE NOT FOUND){$colors['reset']}\n";
            continue;
        }
        
        if ($confirm) {
            if (copy($sourcePath, $targetPath)) {
                echo "{$colors['success']}✓ $dir/$sourceFile → $targetFile{$colors['reset']}\n";
                $successCount++;
            } else {
                echo "{$colors['error']}✗ Failed to create $targetFile{$colors['reset']}\n";
            }
        } else {
            echo "{$colors['info']}→ $dir/$sourceFile → $targetFile (would create){$colors['reset']}\n";
            $successCount++;
        }
    }
}

echo "\n";
echo str_repeat("=", 50) . "\n";

if ($confirm) {
    echo "{$colors['success']}✓ Integration complete! $successCount/$totalChanges changes applied{$colors['reset']}\n";
    echo "\nNext steps:\n";
    echo "1. Update view files to use new controller methods\n";
    echo "2. Create lessonQuiz.php view file\n";
    echo "3. Test all functionality\n";
    echo "4. Remove *_NEW.php files when satisfied\n";
} else {
    echo "{$colors['info']}Ready to apply $successCount/$totalChanges changes{$colors['reset']}\n";
    echo "Run with --confirm flag to proceed\n";
}

echo "{$colors['reset']}\n";

?>
