<?php
require_once __DIR__ . '/../Model/Question.php';
require_once __DIR__ . '/../Model/Lesson.php';

class QuestionController
{
    private QuestionModel $model;
    private LessonModel $lessonModel;

    public function __construct()
    {
        $this->model = new QuestionModel();
        $this->lessonModel = new LessonModel();
    }

    public function list(): void
    {
        $questions = $this->model->getAll();
        require __DIR__ . '/../View/Back office/Education Corner/questionList.php';
    }

    public function add(array $postData = []): void
    {
        $lessons = $this->lessonModel->getAll();

        if (!empty($postData)) {
            $data = [
                'lessonId' => (int) ($postData['lessonId'] ?? 0),
                'questionText' => trim($postData['questionText'] ?? ''),
                'option1' => trim($postData['option1'] ?? ''),
                'option2' => trim($postData['option2'] ?? ''),
                'option3' => trim($postData['option3'] ?? ''),
                'goodAnswer' => trim($postData['goodAnswer'] ?? ''),
                'points' => max(1, (int) ($postData['points'] ?? 5)),
            ];

            if ($data['lessonId'] <= 0 || $data['questionText'] === '' || $data['goodAnswer'] === '') {
                $error = 'Please fill all required fields.';
                require __DIR__ . '/../View/Back office/Education Corner/questionForm.php';
                return;
            }

            if ($data['option1'] === '' || $data['option2'] === '') {
                $error = 'At least 2 options are required.';
                require __DIR__ . '/../View/Back office/Education Corner/questionForm.php';
                return;
            }

            $this->model->create($data);
            header('Location: questionList_direct.php');
            exit;
        }

                require __DIR__ . '/../View/Back office/Education Corner/questionForm.php';
    }

    public function edit(int $questionId, array $postData = []): void
    {
        $question = $this->model->getOne($questionId);
        if (!$question) {
            echo 'Question not found';
            exit;
        }

        $lessons = $this->lessonModel->getAll();

        if (!empty($postData)) {
            $data = [
                'questionText' => trim($postData['questionText'] ?? ''),
                'option1' => trim($postData['option1'] ?? ''),
                'option2' => trim($postData['option2'] ?? ''),
                'option3' => trim($postData['option3'] ?? ''),
                'goodAnswer' => trim($postData['goodAnswer'] ?? ''),
                'points' => max(1, (int) ($postData['points'] ?? 5)),
            ];

            if ($data['questionText'] === '' || $data['goodAnswer'] === '') {
                $error = 'Please fill all required fields.';
                require __DIR__ . '/../View/Back office/Education Corner/questionForm.php';
                return;
            }

            if ($data['option1'] === '' || $data['option2'] === '') {
                $error = 'At least 2 options are required.';
                require __DIR__ . '/../View/Back office/Education Corner/questionForm.php';
                return;
            }

            $this->model->update($questionId, $data);
            header('Location: questionList_direct.php');
            exit;
        }

                require __DIR__ . '/../View/Back office/Education Corner/questionForm.php';
    }

    public function delete(int $questionId): void
    {
        $this->model->delete($questionId);
        header('Location: questionList_direct.php');
        exit;
    }

    public function getQuestionWithLesson(int $questionId): ?array
    {
        $question = $this->model->getOne($questionId);
        if ($question) {
            $lesson = $this->lessonModel->getById($question['lessonId']);
            $question['lesson'] = $lesson;
        }
        return $question ?: null;
    }

    public function getQuestionsByLesson(int $lessonId): array
    {
        return $this->model->getByLesson($lessonId) ?? [];
    }

    public function checkAnswer(int $questionId, string $userAnswer): array
    {
        $question = $this->model->getOne($questionId);
        if (!$question) {
            return ['success' => false, 'message' => 'Question not found', 'isCorrect' => false];
        }

        $isCorrect = strtolower(trim($userAnswer)) === strtolower(trim($question['goodAnswer']));
        return [
            'success' => true,
            'isCorrect' => $isCorrect,
            'correctAnswer' => $question['goodAnswer'],
            'points' => $isCorrect ? ($question['points'] ?? 0) : 0,
        ];
    }

    public function validateAnswer(int $questionId, string $userAnswer): bool
    {
        $question = $this->model->getOne($questionId);
        if (!$question || $userAnswer === '') {
            return false;
        }

        if (($question['questionType'] ?? '') === 'true_false') {
            return in_array(strtolower($userAnswer), ['true', 'false', 't', 'f', '1', '0'], true);
        }

        return true;
    }

    public function getQuestionStats(int $questionId): ?array
    {
        $question = $this->model->getOne($questionId);
        if (!$question) {
            return null;
        }

        return [
            'questionId' => $question['questionId'],
            'lessonId' => $question['lessonId'],
            'questionText' => $question['questionText'],
            'questionType' => $question['questionType'] ?? 'multiple_choice',
            'points' => $question['points'] ?? 1,
            'optionCount' => $this->countOptions($question),
        ];
    }

    private function countOptions(array $question): int
    {
        $count = 0;
        for ($i = 1; $i <= 4; $i++) {
            if (!empty($question['option' . $i])) {
                $count++;
            }
        }
        return $count;
    }
}
