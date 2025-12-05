<?php
require_once __DIR__ . '/../models/QuestionModel.php';
require_once __DIR__ . '/../models/LessonModel.php';

class QuestionController {
    private QuestionModel $model;
    private LessonModel $lessonModel;

    public function __construct() {
        $this->model = new QuestionModel();
        $this->lessonModel = new LessonModel();
    }

    // List all questions
    public function list() {
        $questions = $this->model->getAll();
        require_once __DIR__ . '/../views/back/questionList.php';
    }

    // Add a question
    public function add(array $postData = []) {
        $lessons = $this->lessonModel->getAll();

        if (!empty($postData)) {
            $data = [
                'lessonId' => (int)($postData['lessonId'] ?? 0),
                'questionText' => trim($postData['questionText'] ?? ''),
                'option1' => trim($postData['option1'] ?? ''),
                'option2' => trim($postData['option2'] ?? ''),
                'option3' => trim($postData['option3'] ?? ''),
                'goodAnswer' => trim($postData['goodAnswer'] ?? '')
            ];

            if ($data['lessonId'] <= 0 || $data['questionText'] === '' || $data['goodAnswer'] === '') {
                $error = "Please fill all required fields.";
                require_once __DIR__ . '/../views/back/questionForm.php';
                return;
            }

            if ($data['option1'] === '' || $data['option2'] === '') {
                $error = "At least 2 options are required.";
                require_once __DIR__ . '/../views/back/questionForm.php';
                return;
            }

            $this->model->create($data);
            header("Location: /lessons_project/views/back/questionList_direct.php");
            exit;
        }

        require_once __DIR__ . '/../views/back/questionForm.php';
    }

    // Edit a question by ID
    public function edit(int $questionId, array $postData = []) {
        $question = $this->model->getOne($questionId);
        if (!$question) {
            echo "Question not found";
            exit;
        }

        $lessons = $this->lessonModel->getAll();

        if (!empty($postData)) {
            $data = [
                'questionText' => trim($postData['questionText'] ?? ''),
                'option1' => trim($postData['option1'] ?? ''),
                'option2' => trim($postData['option2'] ?? ''),
                'option3' => trim($postData['option3'] ?? ''),
                'goodAnswer' => trim($postData['goodAnswer'] ?? '')
            ];

            if ($data['questionText'] === '' || $data['goodAnswer'] === '') {
                $error = "Please fill all required fields.";
                require_once __DIR__ . '/../views/back/questionForm.php';
                return;
            }

            if ($data['option1'] === '' || $data['option2'] === '') {
                $error = "At least 2 options are required.";
                require_once __DIR__ . '/../views/back/questionForm.php';
                return;
            }

            $this->model->update($questionId, $data);
            header("Location: /lessons_project/views/back/questionList_direct.php");
            exit;
        }

        require_once __DIR__ . '/../views/back/questionForm.php';
    }

    // Delete a question by ID
    public function delete(int $questionId) {
        $this->model->delete($questionId);
        header("Location: /lessons_project/views/back/questionList_direct.php");
        exit;
    }

    // Get a question with its lesson
    public function getQuestionWithLesson(int $questionId) {
        $question = $this->model->getOne($questionId);
        if ($question) {
            $lesson = $this->lessonModel->getById($question['lessonId']);
            $question['lesson'] = $lesson;
        }
        return $question;
    }

    // Get all questions for a specific lesson
    public function getQuestionsByLesson(int $lessonId): array {
        return $this->model->getByLesson($lessonId) ?? [];
    }

    // Check a user's answer
    public function checkAnswer(int $questionId, string $userAnswer): array {
        $question = $this->model->getOne($questionId);
        if (!$question) {
            return ['success' => false, 'message' => 'Question not found', 'isCorrect' => false];
        }

        $isCorrect = strtolower(trim($userAnswer)) === strtolower(trim($question['goodAnswer']));
        return [
            'success' => true,
            'isCorrect' => $isCorrect,
            'correctAnswer' => $question['goodAnswer'],
            'points' => $isCorrect ? $question['points'] : 0
        ];
    }

    // Validate answer format
    public function validateAnswer(int $questionId, string $userAnswer): bool {
        $question = $this->model->getOne($questionId);
        if (!$question || empty($userAnswer)) return false;

        if ($question['questionType'] === 'true_false') {
            return in_array(strtolower($userAnswer), ['true', 'false', 't', 'f', '1', '0']);
        }

        return true;
    }

    // Get question statistics
    public function getQuestionStats(int $questionId): ?array {
        $question = $this->model->getOne($questionId);
        if (!$question) return null;

        return [
            'questionId' => $question['questionId'],
            'lessonId' => $question['lessonId'],
            'questionText' => $question['questionText'],
            'questionType' => $question['questionType'] ?? 'multiple_choice',
            'points' => $question['points'] ?? 1,
            'optionCount' => $this->countOptions($question)
        ];
    }

    // Count filled options for a question
    private function countOptions(array $question): int {
        $count = 0;
        for ($i = 1; $i <= 4; $i++) {
            if (!empty($question['option' . $i])) $count++;
        }
        return $count;
    }
}
?>
