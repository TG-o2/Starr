<?php
require_once __DIR__ . '/../teacher_admin_guard.php';
// Shared initialization for the Education Corner module
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../Model/Lesson.php';
require_once __DIR__ . '/../../../Model/Question.php';
require_once __DIR__ . '/../../../Controller/LessonController.php';
require_once __DIR__ . '/../../../Controller/QuestionController.php';

// Session already started by guard file
