<?php
declare(strict_types=1);

require_once __DIR__ . '/../Model/Question.php';

class QuestionController
{
    private QuestionModel $model;

    public function __construct()
    {
        $this->model = new QuestionModel();
    }

    public function list(int $page = 1, array $filters = []): void
    {
        $perPage = 10;
        $offset = ($page - 1) * $perPage;
        
        $questions = $this->model->getAll($filters, $perPage, $offset);
        $totalQuestions = $this->model->countAll($filters);
        $totalPages = ceil($totalQuestions / $perPage);
        
        require __DIR__ . '/../View/Back office/Education Corner/questionList.php';
    }

    public function add(array $postData = []): void
    {
        $error = null;
        $success = $_SESSION['success'] ?? null;
        unset($_SESSION['success']);

        if (!empty($postData)) {
            try {
                $this->validateQuestionData($postData);
                
                $data = [
                    'lessonId' => (int)$postData['lessonId'],
                    'question' => trim($postData['question']),
                    'optionA' => trim($postData['optionA']),
                    'optionB' => trim($postData['optionB']),
                    'optionC' => trim($postData['optionC']),
                    'optionD' => trim($postData['optionD']),
                    'goodAnswer' => trim($postData['goodAnswer']),
                    'points' => (int)($postData['points'] ?? 5),
                    'difficulty' => $postData['difficulty'] ?? 'easy',
                    'time_limit' => (int)($postData['time_limit'] ?? 0),
                    'explanation' => trim($postData['explanation'] ?? ''),
                ];
                
                $this->model->create($data);
                
                $_SESSION['success'] = 'Question added successfully!';
                header('Location: questionList_direct.php?lessonId=' . $data['lessonId']);
                exit;
                
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }

        require __DIR__ . '/../View/Back office/Education Corner/questionAdd.php';
    }

    public function edit(int $questionId, array $postData = []): void
    {
        $question = $this->model->getById($questionId);
        if (!$question) {
            echo 'Question not found';
            exit;
        }

        $error = null;
        $success = $_SESSION['success'] ?? null;
        unset($_SESSION['success']);

        if (!empty($postData)) {
            try {
                $this->validateQuestionData($postData);
                
                $data = [
                    'question' => trim($postData['question']),
                    'optionA' => trim($postData['optionA']),
                    'optionB' => trim($postData['optionB']),
                    'optionC' => trim($postData['optionC']),
                    'optionD' => trim($postData['optionD']),
                    'goodAnswer' => trim($postData['goodAnswer']),
                    'points' => (int)($postData['points'] ?? 5),
                    'difficulty' => $postData['difficulty'] ?? 'easy',
                    'time_limit' => (int)($postData['time_limit'] ?? 0),
                    'explanation' => trim($postData['explanation'] ?? ''),
                ];
                
                $this->model->update($questionId, $data);
                
                $question = $this->model->getById($questionId);
                $success = 'Question updated successfully!';
                
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }

        require __DIR__ . '/../View/Back office/Education Corner/questionEdit.php';
    }

    public function delete(int $questionId): void
    {
        try {
            $question = $this->model->getById($questionId);
            if (!$question) {
                throw new Exception('Question not found');
            }
            
            $lessonId = $question['lessonId'];
            $this->model->delete($questionId);
            
            $_SESSION['success'] = 'Question deleted successfully';
            header('Location: questionList_direct.php?lessonId=' . $lessonId);
            exit;
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function getByLesson(int $lessonId): array
    {
        return $this->model->getByLesson($lessonId);
    }

    private function validateQuestionData(array $data): void
    {
        if (empty($data['lessonId']) || empty($data['question'])) {
            throw new Exception('Lesson ID and question text are required');
        }
        
        foreach (['optionA', 'optionB', 'optionC', 'optionD'] as $option) {
            if (empty($data[$option])) {
                throw new Exception('All four options are required');
            }
        }
        
        if (empty($data['goodAnswer'])) {
            throw new Exception('Correct answer must be selected');
        }
        
        $validAnswers = ['A', 'B', 'C', 'D'];
        if (!in_array($data['goodAnswer'], $validAnswers)) {
            throw new Exception('Invalid correct answer selection');
        }
        
        $points = (int)($data['points'] ?? 5);
        if ($points < 1 || $points > 100) {
            throw new Exception('Points must be between 1 and 100');
        }
        
        $difficulty = $data['difficulty'] ?? 'easy';
        $validDifficulties = ['easy', 'medium', 'hard'];
        if (!in_array($difficulty, $validDifficulties)) {
            throw new Exception('Invalid difficulty level');
        }
        
        $timeLimit = (int)($data['time_limit'] ?? 0);
        if ($timeLimit < 0 || $timeLimit > 600) {
            throw new Exception('Time limit must be between 0 and 600 seconds');
        }
    }
}
?>
