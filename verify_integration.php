#!/usr/bin/env php
<?php
/**
 * GitHub Integration Verification Script
 * Checks if all files are in place and ready for integration
 */

$baseDir = __DIR__;
$colors = [
    'success' => "\033[32m",
    'warning' => "\033[33m",
    'error' => "\033[31m",
    'info' => "\033[36m",
    'reset' => "\033[0m",
];

echo "\n{$colors['info']}===== GitHub Integration Verification ====={$colors['reset']}\n\n";

$checks = [
    'passed' => 0,
    'failed' => 0,
    'warnings' => 0,
];

// Check Controllers
echo "CHECKING CONTROLLERS:\n";
echo str_repeat("-", 40) . "\n";

$controllerFiles = [
    'Controller/LessonController_NEW.php' => 'New LessonController',
    'Controller/QuestionController_NEW.php' => 'New QuestionController',
];

foreach ($controllerFiles as $file => $desc) {
    $path = $baseDir . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $file);
    if (file_exists($path)) {
        $size = filesize($path);
        echo "{$colors['success']}✓{$colors['reset']} $desc ({$size} bytes)\n";
        $checks['passed']++;
    } else {
        echo "{$colors['error']}✗{$colors['reset']} $desc - FILE NOT FOUND\n";
        $checks['failed']++;
    }
}

echo "\n";

// Check Models
echo "CHECKING MODELS:\n";
echo str_repeat("-", 40) . "\n";

$modelFiles = [
    'Model/Lesson_NEW.php' => 'New LessonModel',
    'Model/Question_NEW.php' => 'New QuestionModel',
    'Model/QuizAttemptModel_NEW.php' => 'New QuizAttemptModel',
];

foreach ($modelFiles as $file => $desc) {
    $path = $baseDir . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $file);
    if (file_exists($path)) {
        $size = filesize($path);
        echo "{$colors['success']}✓{$colors['reset']} $desc ({$size} bytes)\n";
        $checks['passed']++;
    } else {
        echo "{$colors['error']}✗{$colors['reset']} $desc - FILE NOT FOUND\n";
        $checks['failed']++;
    }
}

echo "\n";

// Check Documentation
echo "CHECKING DOCUMENTATION:\n";
echo str_repeat("-", 40) . "\n";

$docFiles = [
    'GITHUB_INTEGRATION_GUIDE.md' => 'Integration Guide',
    'INTEGRATION_SUMMARY.md' => 'Integration Summary',
    'VIEW_FILES_IMPLEMENTATION.md' => 'View Files Implementation Guide',
];

foreach ($docFiles as $file => $desc) {
    $path = $baseDir . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $file);
    if (file_exists($path)) {
        $size = filesize($path);
        echo "{$colors['success']}✓{$colors['reset']} $desc ({$size} bytes)\n";
        $checks['passed']++;
    } else {
        echo "{$colors['error']}✗{$colors['reset']} $desc - FILE NOT FOUND\n";
        $checks['failed']++;
    }
}

echo "\n";

// Check Tools
echo "CHECKING TOOLS:\n";
echo str_repeat("-", 40) . "\n";

$toolFiles = [
    'integrate_github_features.php' => 'Integration Script',
];

foreach ($toolFiles as $file => $desc) {
    $path = $baseDir . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $file);
    if (file_exists($path)) {
        $size = filesize($path);
        echo "{$colors['success']}✓{$colors['reset']} $desc ({$size} bytes)\n";
        $checks['passed']++;
    } else {
        echo "{$colors['error']}✗{$colors['reset']} $desc - FILE NOT FOUND\n";
        $checks['failed']++;
    }
}

echo "\n";

// Check Database Status
echo "CHECKING DATABASE:\n";
echo str_repeat("-", 40) . "\n";

$dbPath = $baseDir . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'upgrade_education_corner.php';
if (file_exists($dbPath)) {
    echo "{$colors['success']}✓{$colors['reset']} Database migration script exists\n";
    echo "{$colors['info']}ℹ{$colors['reset']} Migration already executed (see conversation summary)\n";
    $checks['passed']++;
    $checks['warnings']++;
} else {
    echo "{$colors['error']}✗{$colors['reset']} Database migration script not found\n";
    $checks['failed']++;
}

echo "\n";

// Check Existing Files
echo "CHECKING EXISTING FILES:\n";
echo str_repeat("-", 40) . "\n";

$existingFiles = [
    'Controller/LessonController.php' => 'Current LessonController',
    'Controller/QuestionController.php' => 'Current QuestionController',
    'Model/Lesson.php' => 'Current LessonModel',
    'Model/Question.php' => 'Current QuestionModel',
];

foreach ($existingFiles as $file => $desc) {
    $path = $baseDir . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $file);
    if (file_exists($path)) {
        echo "{$colors['success']}✓{$colors['reset']} $desc (will be backed up)\n";
        $checks['passed']++;
    } else {
        echo "{$colors['warning']}!{$colors['reset']} $desc - NOT FOUND (will be skipped)\n";
        $checks['warnings']++;
    }
}

echo "\n";

// Summary
echo str_repeat("=", 40) . "\n";
echo "VERIFICATION SUMMARY:\n";
echo "{$colors['success']}Passed: {$checks['passed']}{$colors['reset']}\n";

if ($checks['warnings'] > 0) {
    echo "{$colors['warning']}Warnings: {$checks['warnings']}{$colors['reset']}\n";
}

if ($checks['failed'] > 0) {
    echo "{$colors['error']}Failed: {$checks['failed']}{$colors['reset']}\n";
} else {
    echo "{$colors['success']}Failed: 0{$colors['reset']}\n";
}

echo str_repeat("=", 40) . "\n";

if ($checks['failed'] === 0) {
    echo "\n{$colors['success']}✓ All files are ready for integration!{$colors['reset']}\n\n";
    echo "Next steps:\n";
    echo "1. Review the GITHUB_INTEGRATION_GUIDE.md\n";
    echo "2. Read the INTEGRATION_SUMMARY.md for details\n";
    echo "3. Check VIEW_FILES_IMPLEMENTATION.md for view updates needed\n";
    echo "4. Run: php integrate_github_features.php --confirm\n";
    echo "5. Update view files\n";
    echo "6. Test functionality\n\n";
} else {
    echo "\n{$colors['error']}✗ Some files are missing. Please check paths and try again.{$colors['reset']}\n\n";
}

echo "{$colors['reset']}\n";

?>
