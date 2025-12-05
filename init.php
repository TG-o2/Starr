<?php
// init.php - Shared initialization for all entry points

// Include config first
require_once __DIR__ . '/config.php';

// Include models
require_once __DIR__ . '/models/LessonModel.php';
require_once __DIR__ . '/models/QuestionModel.php';

// Include controllers
require_once __DIR__ . '/controllers/LessonController.php';
require_once __DIR__ . '/controllers/QuestionController.php';

// Start session
session_start();
?>
