<?php
declare(strict_types=1);

require_once __DIR__ . '/../Model/Lesson.php';
require_once __DIR__ . '/../Model/Question.php';

class LessonController
{
    private LessonModel $model;
    private QuestionModel $questionModel;
    private array $allowedImageTypes = ['image/jpeg', 'image/png', 'image/gif'];
    private array $allowedVideoTypes = ['video/mp4', 'video/webm'];
    private int $maxFileSize = 5 * 1024 * 1024; // 5MB
    private string $uploadDir;

    public function __construct()
    {
        $this->model = new LessonModel();
        $this->questionModel = new QuestionModel();
        $this->uploadDir = __DIR__ . '/../View/Front office/assets/uploads/lessons/';
        
        // Create upload directory if it doesn't exist
        if (!is_dir($this->uploadDir)) {
            @mkdir($this->uploadDir, 0755, true);
        }
    }

    public function list(int $page = 1, array $filters = []): void
    {
        $perPage = 10;
        $offset = ($page - 1) * $perPage;
        
        // Get filtered lessons
        $lessons = $this->model->getAll($filters, $perPage, $offset);
        $totalLessons = $this->model->countAll($filters);
        $totalPages = ceil($totalLessons / $perPage);
        $categories = $this->model->getCategories();
        
        require __DIR__ . '/../View/Back office/Education Corner/lessonList.php';
    }

    public function add(array $postData = [], array $files = []): void
    {
        $error = null;
        $data = [
            'title' => '',
            'description' => '',
            'content' => '',
            'ageRange' => '5-18',
            'category' => 'General',
            'difficulty' => 'beginner',
            'duration' => 0,
            'quiz_time_limit' => 30,
            'average_age' => 12,
            'thumbnail_url' => null,
            'is_published' => false,
            'is_featured' => false,
        ];

        if (!empty($postData)) {
            try {
                $data['title'] = trim($postData['title'] ?? '');
                $data['ageRange'] = (string)($postData['ageRange'] ?? '5-18');
                $data['duration'] = (int)($postData['duration'] ?? 0);
                $data['description'] = trim($postData['description'] ?? '');
                $data['content'] = trim($postData['content'] ?? '');
                $data['category'] = $postData['category'] ?? 'General';
                $data['difficulty'] = $postData['difficulty'] ?? 'beginner';
                $data['quiz_time_limit'] = (int)($postData['quiz_time_limit'] ?? 30);
                $data['is_published'] = isset($postData['is_published']);
                $data['is_featured'] = isset($postData['is_featured']);
                $data['average_age'] = $this->computeAverageAge($data['ageRange']);
                $data['created_by'] = $_SESSION['user_id'] ?? 1;
                
                // Validation
                if ($data['title'] === '' || $data['duration'] <= 0 || $data['description'] === '') {
                    throw new Exception('All required fields must be filled. Duration must be positive.');
                }
                
                // Handle file upload
                if (!empty($files['image']['name'])) {
                    $data['thumbnail_url'] = $this->handleFileUpload($files['image'], false);
                }
                
                // Create lesson
                $lessonId = $this->model->create($data);
                
                $_SESSION['success'] = 'Lesson created successfully!';
                header('Location: lessonList_direct.php');
                exit;
                
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }
        
        $categories = $this->model->getCategories();
        require __DIR__ . '/../View/Back office/Education Corner/lessonAdd.php';
    }

    public function edit(int $lessonId, array $postData = [], array $files = []): void
    {
        $lesson = $this->model->getById($lessonId);
        if (!$lesson) {
            echo 'Lesson not found';
            exit;
        }

        $error = null;
        $success = $_SESSION['success'] ?? null;
        unset($_SESSION['success']);

        if (!empty($postData)) {
            try {
                $updateData = [
                    'title' => trim($postData['title'] ?? ''),
                    'description' => trim($postData['description'] ?? ''),
                    'content' => trim($postData['content'] ?? ''),
                    'ageRange' => (string)($postData['ageRange'] ?? $lesson['ageRange']),
                    'category' => $postData['category'] ?? 'General',
                    'difficulty' => $postData['difficulty'] ?? 'beginner',
                    'duration' => (int)($postData['duration'] ?? 0),
                    'quiz_time_limit' => (int)($postData['quiz_time_limit'] ?? 30),
                    'is_published' => isset($postData['is_published']) ? 1 : 0,
                    'is_featured' => isset($postData['is_featured']) ? 1 : 0,
                    'average_age' => $this->computeAverageAge((string)($postData['ageRange'] ?? $lesson['ageRange'])),
                    'updated_by' => $_SESSION['user_id'] ?? 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                
                // Validation
                if ($updateData['title'] === '' || $updateData['duration'] <= 0 || $updateData['description'] === '') {
                    throw new Exception('All required fields must be filled. Duration must be positive.');
                }
                
                // Handle file upload
                if (!empty($files['image']['name'])) {
                    // Delete old thumbnail if exists
                    if (!empty($lesson['thumbnail_url'])) {
                        $this->deleteFile($lesson['thumbnail_url']);
                    }
                    $updateData['thumbnail_url'] = $this->handleFileUpload($files['image'], false);
                }
                
                // Update lesson
                $this->model->update($lessonId, $updateData);
                
                // Refresh lesson data
                $lesson = $this->model->getById($lessonId);
                $success = 'Lesson updated successfully!';
                
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }
        
        $categories = $this->model->getCategories();
        require __DIR__ . '/../View/Back office/Education Corner/lessonEdit.php';
    }

    public function delete(int $lessonId): void
    {
        try {
            $lesson = $this->model->getById($lessonId);
            if (!$lesson) {
                throw new Exception('Lesson not found');
            }
            
            // Delete associated files
            if (!empty($lesson['thumbnail_url'])) {
                $this->deleteFile($lesson['thumbnail_url']);
            }
            
            // Delete lesson (cascades to questions via foreign key)
            $this->model->delete($lessonId);
            
            $_SESSION['success'] = 'Lesson deleted successfully';
            header('Location: lessonList_direct.php');
            exit;
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function displayFront(): void
    {
        $lessons = $this->model->getAll();
        $featuredLessons = $this->model->getFeaturedLessons(3);
        $categories = $this->model->getCategories();
        $studentId = $_SESSION['user_id'] ?? 'guest';
        
        require __DIR__ . '/../View/Front office/Education Corner/lessonDisplay.php';
    }

    public function details(int $lessonId): void
    {
        $lesson = $this->model->getById($lessonId);
        if (!$lesson) {
            echo "Lesson not found";
            exit;
        }
        
        $questions = $this->questionModel->getByLesson($lessonId);
        require __DIR__ . '/../View/Front office/Education Corner/lessonDetails.php';
    }

    public function quiz(int $lessonId): void
    {
        $lesson = $this->model->getById($lessonId);
        if (!$lesson) {
            echo "Lesson not found";
            exit;
        }
        
        $questions = $this->questionModel->getByLesson($lessonId);
        
        // Check if form was submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitQuiz'])) {
            $this->handleQuizSubmission($lessonId, $lesson, $questions);
            return;
        }
        
        // Show quiz form
        $showResults = false;
        require __DIR__ . '/../View/Front office/Education Corner/lessonQuiz.php';
    }

    private function handleQuizSubmission(int $lessonId, array $lesson, array $questions): void
    {
        $score = 0;
        $total = 0;
        $results = [];
        $studentId = $_SESSION['user_id'] ?? 'guest';
        
        $startTime = (int)($_POST['start_time'] ?? time());
        $timeTaken = time() - $startTime;
        $timeLimit = ($lesson['quiz_time_limit'] ?? 0) * 60;
        $isTimedOut = $timeLimit > 0 && $timeTaken > $timeLimit;
        
        // Calculate score
        foreach ($questions as $q) {
            $qid = $q['questionId'];
            $rawAnswer = $_POST['q' . $qid] ?? '';
            $userAnswers = is_array($rawAnswer) ? $rawAnswer : [$rawAnswer];
            $userAnswers = array_values(array_filter(array_map(function($v) {
                return trim((string)$v);
            }, $userAnswers)));
            
            $correctOptions = [];
            if (isset($q['goodAnswer']) && !empty($q['goodAnswer'])) {
                $correctOptions = [(string)$q['goodAnswer']];
            }
            
            $isCorrect = !$isTimedOut && ($userAnswers === $correctOptions);
            $points = (int)($q['points'] ?? 5);
            $total += $points;
            
            $results[] = [
                'question' => $q,
                'selected' => is_array($rawAnswer) ? $userAnswers : (string)($rawAnswer ?? ''),
                'is_correct' => $isCorrect,
                'timed_out' => $isTimedOut
            ];
            
            if ($isCorrect) {
                $score += $points;
            }
        }
        
        if ($isTimedOut) {
            $score = 0;
        }
        
        $scorePercentage = $total > 0 ? ($score / $total) * 100 : 0;
        
        // Show results
        $showResults = true;
        require __DIR__ . '/../View/Front office/Education Corner/lessonQuiz.php';
    }

    private function handleFileUpload(array $file, bool $isVideo = false): string
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception($this->getUploadErrorMessage($file['error']));
        }
        
        if ($file['size'] > $this->maxFileSize) {
            throw new Exception('File is too large. Maximum size is ' . ($this->maxFileSize / 1024 / 1024) . 'MB');
        }
        
        $allowedTypes = $isVideo ? $this->allowedVideoTypes : $this->allowedImageTypes;
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $targetDir = $this->uploadDir;
        
        if (!in_array(mime_content_type($file['tmp_name']), $allowedTypes)) {
            throw new Exception('Invalid file type. Allowed types: ' . implode(', ', $allowedTypes));
        }
        
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        
        $filename = uniqid() . '.' . $ext;
        $targetPath = $targetDir . $filename;
        
        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            throw new Exception('Failed to move uploaded file');
        }
        
        return $filename;
    }

    private function deleteFile(string $path): bool
    {
        $fullPath = $this->uploadDir . $path;
        if (file_exists($fullPath)) {
            return unlink($fullPath);
        }
        return false;
    }

    private function getUploadErrorMessage(int $errorCode): string
    {
        $messages = [
            UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the upload_max_filesize directive',
            UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the MAX_FILE_SIZE directive',
            UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
            UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload',
        ];
        return $messages[$errorCode] ?? 'Unknown upload error';
    }

    private function computeAverageAge(string $ageRange): int
    {
        $ageRange = trim($ageRange);
        if (preg_match('/^(\d{1,3})\s*-\s*(\d{1,3})$/', $ageRange, $m) !== 1) {
            return 12;
        }
        
        $min = (int)$m[1];
        $max = (int)$m[2];
        if ($min > $max) {
            [$min, $max] = [$max, $min];
        }
        
        $min = max(0, min(120, $min));
        $max = max(0, min(120, $max));
        
        return (int)round(($min + $max) / 2);
    }
}
?>
