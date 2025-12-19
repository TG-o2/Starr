<?php
require_once __DIR__ . '/init.php';

if (isset($_GET['delete'], $_GET['questionId'])) {
    $questionId = (int) $_GET['questionId'];
    (new QuestionController())->delete($questionId);
}

(new QuestionController())->list();
