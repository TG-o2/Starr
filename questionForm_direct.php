<?php
// Back-office: questionForm.php - Add/Edit question
require_once __DIR__ . '/../../init.php';

$controller = new QuestionController();
if (isset($_GET['questionId'])) {
    $questionId = (int)$_GET['questionId'];
    if ($questionId <= 0) {
        echo "Invalid question ID";
        exit;
    }
    $controller->edit($questionId, $_POST);
} else {
    $controller->add($_POST);
}
?>
