<?php
// index.php - MAIN ENTRY POINT

// Include config first
require_once 'config.php';

// Include models and controllers
require_once 'models/LessonModel.php';
require_once 'models/QuestionModel.php';
require_once 'controllers/LessonController.php';
require_once 'controllers/QuestionController.php';

// Start session
session_start();

// Get action from URL
$action = $_GET['action'] ?? 'lessonDisplay';


// Route requests
switch($action) {
    // BackOffice Lessons
    case 'lessonList': 
        (new LessonController())->list(); 
        break;
    case 'lessonAdd': 
        (new LessonController())->add($_POST);
        break;
    case 'lessonEdit': 
        $lid = (int)($_GET['lessonId'] ?? $_GET['id'] ?? 0);
        (new LessonController())->edit($lid, $_POST);
        break;
    case 'lessonDelete': 
        $lid = (int)($_GET['lessonId'] ?? $_GET['id'] ?? 0);
        (new LessonController())->delete($lid);
        break;

    // BackOffice Questions
    case 'questionList': 
        (new QuestionController())->list(); 
        break;
    case 'questionAdd': 
        (new QuestionController())->add($_POST);
        break;
    case 'questionDelete': 
        $qid = (int)($_GET['questionId'] ?? $_GET['id'] ?? 0);
        (new QuestionController())->delete($qid);
        break;

    // Front Office
    case 'lessonDisplay': 
        (new LessonController())->displayFront(); 
        break;
    case 'lessonDetails': 
        $lid = (int)($_GET['lessonId'] ?? $_GET['id'] ?? 0);
        (new LessonController())->details($lid);
        break;
    case 'lessonQuiz': 
        $lid = (int)($_GET['lessonId'] ?? $_GET['id'] ?? $_GET['lesson_id'] ?? 0);
        (new LessonController())->quiz($lid);
        break;

    default: 
        // Default to lessons display
        (new LessonController())->displayFront();
        break;
}
?>