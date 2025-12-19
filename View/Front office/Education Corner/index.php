<?php
// Router for the Education Corner module
require_once __DIR__ . '/init.php';

$action = $_GET['action'] ?? 'lessonDisplay';

switch ($action) {
    case 'lessonList':
        (new LessonController())->list();
        break;
    case 'lessonAdd':
        (new LessonController())->add($_POST);
        break;
    case 'lessonEdit':
        $lid = (int) ($_GET['lessonId'] ?? $_GET['id'] ?? 0);
        (new LessonController())->edit($lid, $_POST);
        break;
    case 'lessonDelete':
        $lid = (int) ($_GET['lessonId'] ?? $_GET['id'] ?? 0);
        (new LessonController())->delete($lid);
        break;

    case 'questionList':
        (new QuestionController())->list();
        break;
    case 'questionAdd':
        (new QuestionController())->add($_POST);
        break;
    case 'questionDelete':
        $qid = (int) ($_GET['questionId'] ?? $_GET['id'] ?? 0);
        (new QuestionController())->delete($qid);
        break;

    case 'lessonDisplay':
        (new LessonController())->displayFront();
        break;
    case 'lessonDetails':
        $lid = (int) ($_GET['lessonId'] ?? $_GET['id'] ?? 0);
        (new LessonController())->details($lid);
        break;
    case 'lessonQuiz':
        $lid = (int) ($_GET['lessonId'] ?? $_GET['id'] ?? $_GET['lesson_id'] ?? 0);
        (new LessonController())->quiz($lid);
        break;

    default:
        (new LessonController())->displayFront();
        break;
}
