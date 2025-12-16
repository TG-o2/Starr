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
    
    // Get current lesson ID from POST, GET, or question data
    $currentLessonId = (int)($_POST['lessonId'] ?? $_GET['lessonId'] ?? 0);
    
    // Only show questions for the current lesson
    $questions = $currentLessonId > 0 ? $this->model->getByLesson($currentLessonId) : [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($postData)) {
        $lessonId = (int)($postData['lessonId'] ?? 0);
        $questions = $postData['questions'] ?? [];
        $quizTimeLimit = (int)($postData['quiz_time_limit'] ?? 30);

        if ($lessonId <= 0 || empty($questions)) {
            $error = "Please add at least one question.";
            require __DIR__ . '/../views/back/questionForm.php';
            return;
        }

        // Update the lesson's quiz time limit
        if ($quizTimeLimit > 0) {
            $this->lessonModel->updateQuizTimeLimit($lessonId, $quizTimeLimit);
        }

        foreach ($questions as $q) {
            $questionText = trim((string)($q['questionText'] ?? ''));
            if ($questionText === '') {
                continue;
            }

            $points = (int)($q['points'] ?? 1);
            if ($points <= 0) {
                $points = 1;
            }

            $timeLimit = (int)($q['time_limit'] ?? 60); // Default 60 seconds per question
            if ($timeLimit <= 0) {
                $timeLimit = 60;
            }

            $options = $q['options'] ?? null;
            if (!is_array($options)) {
                $options = [
                    $q['option1'] ?? null,
                    $q['option2'] ?? null,
                    $q['option3'] ?? null,
                    $q['option4'] ?? null,
                ];
            }

            $options = array_filter($options, static function ($v) {
                return trim((string)$v) !== '';
            });
            $options = array_map(static function ($v) {
                return trim((string)$v);
            }, $options);
            if (!empty($options)) {
                ksort($options);
            }

            if (count($options) < 2) {
                continue;
            }

            $correctKeys = $q['correct'] ?? [];
            if (!is_array($correctKeys)) {
                $correctKeys = [$correctKeys];
            }
            $correctKeys = array_values(array_filter(array_map('intval', $correctKeys), static function ($v) {
                return is_int($v) || is_numeric($v);
            }));

            $keys = array_keys($options);
            $correctIndices = [];
            foreach ($correctKeys as $ck) {
                $pos = array_search((int)$ck, $keys, true);
                if ($pos !== false) {
                    $correctIndices[] = (int)$pos;
                }
            }
            $correctIndices = array_values(array_unique($correctIndices));

            $goodAnswer = '';
            if (!empty($correctIndices)) {
                $optList = array_values($options);
                $goodAnswer = (string)($optList[$correctIndices[0]] ?? '');
            } elseif (!empty($q['goodAnswer'])) {
                $goodAnswer = trim((string)$q['goodAnswer']);
            }

            if (empty($correctIndices) || $goodAnswer === '') {
                continue;
            }

            $optionsList = array_values($options);

            $this->model->create([
                'lessonId' => $lessonId,
                'questionText' => $questionText,
                'options' => $optionsList,
                'goodAnswer' => $goodAnswer,
                'correctIndices' => $correctIndices,
                'points' => $points,
                'time_limit' => $timeLimit,
            ]);
        }

        header("Location: /lessons_project/views/back/questionList_direct.php");
        exit;
    }

    require __DIR__ . '/../views/back/questionForm.php';
}

    // Edit a question by ID
    public function edit(int $questionId, array $postData = []) {
        $question = $this->model->getOne($questionId);
        if (!$question) {
            echo "Question not found";
            exit;
        }

        $lessons = $this->lessonModel->getAll();
        
        // Get the lesson ID from the current question
        $currentLessonId = (int)($question['lessonId'] ?? 0);
        
        // Only show questions for the current lesson
        $questions = $currentLessonId > 0 ? $this->model->getByLesson($currentLessonId) : [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($postData)) {
            $questionText = trim((string)($postData['questionText'] ?? ''));
            $points = (int)($postData['points'] ?? 1);
            $timeLimit = (int)($postData['time_limit'] ?? 60); // Add timer handling
            
            if ($points <= 0) {
                $points = 1;
            }
            
            if ($timeLimit <= 0) {
                $timeLimit = 60;
            }

            $options = $postData['options'] ?? null;
            if (!is_array($options)) {
                $options = [
                    $postData['option1'] ?? null,
                    $postData['option2'] ?? null,
                    $postData['option3'] ?? null,
                    $postData['option4'] ?? null,
                ];
            }

            // Keep original indices so the posted "correct" value stays valid even if options were removed.
            $options = array_filter($options, static function ($v) {
                return trim((string)$v) !== '';
            });
            $options = array_map(static function ($v) {
                return trim((string)$v);
            }, $options);
            if (!empty($options)) {
                ksort($options);
            }

            $correctKeys = $postData['correct'] ?? [];
            if (!is_array($correctKeys)) {
                $correctKeys = [$correctKeys];
            }
            $correctKeys = array_values(array_filter(array_map('intval', $correctKeys), static function ($v) {
                return is_int($v) || is_numeric($v);
            }));

            $keys = array_keys($options);
            $correctIndices = [];
            foreach ($correctKeys as $ck) {
                $pos = array_search((int)$ck, $keys, true);
                if ($pos !== false) {
                    $correctIndices[] = (int)$pos;
                }
            }
            $correctIndices = array_values(array_unique($correctIndices));

            $goodAnswer = '';
            if (!empty($correctIndices)) {
                $optList = array_values($options);
                $goodAnswer = (string)($optList[$correctIndices[0]] ?? '');
            } elseif (!empty($postData['goodAnswer'])) {
                $goodAnswer = trim((string)$postData['goodAnswer']);
            }

            if ($questionText === '' || count($options) < 2 || empty($correctIndices) || $goodAnswer === '') {
                $error = 'Please fill all required fields.';
                require_once __DIR__ . '/../views/back/questionForm.php';
                return;
            }
            $optionsList = array_values($options);

            $this->model->update($questionId, [
                'questionText' => $questionText,
                'options' => $optionsList,
                'goodAnswer' => $goodAnswer,
                'correctIndices' => $correctIndices,
                'points' => $points,
                'time_limit' => $timeLimit,
            ]);
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
