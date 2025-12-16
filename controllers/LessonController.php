<?php
declare(strict_types=1);

require_once __DIR__ . '/../models/LessonModel.php';
require_once __DIR__ . '/../models/QuestionModel.php';
require_once __DIR__ . '/../models/QuizAttemptModel.php';
require_once __DIR__ . '/../models/Entities/LessonEntity.php';

class LessonController {
    private LessonModel $model;
    private QuestionModel $questionModel;
    private QuizAttemptModel $quizAttemptModel;
    private array $allowedImageTypes = ['image/jpeg', 'image/png', 'image/gif'];
    private string $uploadDir = __DIR__ . '/../public/uploads/lessons/';
    private int $maxFileSize = 5 * 1024 * 1024; // 5MB

    public function __construct() {
        $this->model = new LessonModel();
        $this->questionModel = new QuestionModel();
        $this->quizAttemptModel = new QuizAttemptModel();
        
        // Create upload directory if it doesn't exist
        if (!file_exists($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }

    public function list(int $page = 1, array $filters = []) {
        $perPage = 10;
        $offset = ($page - 1) * $perPage;
        
        // Get filtered lessons
        $lessons = $this->model->getAll($filters, $perPage, $offset);
        $totalLessons = $this->model->countAll($filters);
        $totalPages = ceil($totalLessons / $perPage);
        $categories = $this->model->getCategories();
        
        // Get featured lessons for the sidebar
        $featuredLessons = $this->model->getFeaturedLessons(3);
        
        require_once __DIR__ . '/../views/back/lessonList.php';
    }

    public function show(int $id) {
        $lesson = $this->model->getById($id);
        if (!$lesson) {
            $this->notFound();
            return;
        }
        
        // Get related lessons
        $relatedLessons = $this->model->getRelatedLessons($id, $lesson['category'], 3);
        
        require_once __DIR__ . '/../views/front/lessonDetails.php';
    }

    public function add(array $postData = [], array $files = []) {
        $error = null;
        $data = [
            'title' => '',
            'description' => '',
            'content' => '',
            'category' => 'General',
            'difficulty' => 'beginner',
            'duration' => 0,
            'ageRange' => '5-18',
            'average_age' => 12,
            'thumbnail_url' => null,
            'video_url' => null,
            'is_published' => false,
            'is_featured' => false,
            'prerequisites' => [],
            'learning_objectives' => [],
            'sections' => []
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Basic validation
                $data = array_merge($data, $postData);
                $data['duration'] = (int)($postData['duration'] ?? 0);
                $data['quiz_time_limit'] = (int)($postData['quiz_time_limit'] ?? 30);
                $data['is_published'] = isset($postData['is_published']);
                $data['is_featured'] = isset($postData['is_featured']);
                $data['ageRange'] = (string)($postData['ageRange'] ?? $data['ageRange']);
                $data['average_age'] = $this->computeAverageAge($data['ageRange']);
                $data['thumbnail_url'] = null;
                
                // Set default user ID for created_by
                $data['created_by'] = $_SESSION['user_id'] ?? 1; // Default to user ID 1 if not logged in
                
                // Handle file upload
                if (!empty($files['thumbnail']['name'])) {
                    $thumbnailPath = $this->handleFileUpload($files['thumbnail']);
                    $data['thumbnail_url'] = $thumbnailPath;
                }
                
                if (!empty($files['video']['name'])) {
                    $videoPath = $this->handleFileUpload($files['video'], true);
                    $data['video_url'] = $videoPath;
                }
                
                // Create lesson
                $lessonId = $this->model->create($data);
                
                // Add prerequisites and learning objectives
                $this->saveLessonMeta($lessonId, $postData);
                
                // Redirect to lessons list with success message
                $_SESSION['success'] = 'Lesson created successfully!';
                header("Location: /lessons_project/views/back/lessonList_direct.php");
                exit;
                
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }
        
        $categories = $this->model->getCategories();
        require_once __DIR__ . '/../views/back/lessonAdd.php';
    }

    public function edit(int $id, array $postData = [], array $files = []) {
        $lesson = $this->model->getById($id);
        if (!$lesson) {
            $this->notFound();
            return;
        }
        
        $error = null;
        $success = $_SESSION['success'] ?? null;
        unset($_SESSION['success']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Update basic fields
                $updateData = [
                    'title' => $postData['title'] ?? '',
                    'description' => $postData['description'] ?? '',
                    'content' => $postData['content'] ?? '',
                    'category' => $postData['category'] ?? 'General',
                    'difficulty' => $postData['difficulty'] ?? 'beginner',
                    'duration' => (int)($postData['duration'] ?? 0),
                    'quiz_time_limit' => (int)($postData['quiz_time_limit'] ?? 30),
                    'ageRange' => (string)($postData['ageRange'] ?? ($lesson['ageRange'] ?? '5-18')),
                    'average_age' => $this->computeAverageAge((string)($postData['ageRange'] ?? ($lesson['ageRange'] ?? '5-18'))),
                    'is_published' => isset($postData['is_published']) ? 1 : 0,
                    'is_featured' => isset($postData['is_featured']) ? 1 : 0,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => $_SESSION['user_id'] ?? 1, // Default to user ID 1 if not logged in
                ];
                
                // Handle file uploads
                if (!empty($files['thumbnail']['name'])) {
                    // Delete old thumbnail if exists
                    if (!empty($lesson['thumbnail_url'])) {
                        $this->deleteFile($lesson['thumbnail_url']);
                    }
                    $updateData['thumbnail_url'] = $this->handleFileUpload($files['thumbnail']);
                }
                
                if (!empty($files['video']['name'])) {
                    // Delete old video if exists
                    if (!empty($lesson['video_url'])) {
                        $this->deleteFile($lesson['video_url']);
                    }
                    $updateData['video_url'] = $this->handleFileUpload($files['video'], true);
                }
                
                // Update lesson
                $this->model->update($id, $updateData);
                
                // Update prerequisites and learning objectives
                $this->saveLessonMeta($id, $postData);
                
                // Refresh lesson data
                $lesson = $this->model->getById($id);
                $success = 'Lesson updated successfully!';
                
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }
        
        $categories = $this->model->getCategories();
        require_once __DIR__ . '/../views/back/lessonEdit.php';
    }

    private function computeAverageAge(string $ageRange): int {
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
    
    public function delete(int $id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }
        
        try {
            $lesson = $this->model->getById($id);
            if (!$lesson) {
                throw new Exception('Lesson not found');
            }
            
            // Delete associated files
            if (!empty($lesson['thumbnail_url'])) {
                $this->deleteFile($lesson['thumbnail_url']);
            }
            
            if (!empty($lesson['video_url'])) {
                $this->deleteFile($lesson['video_url']);
            }
            
            // Delete lesson (this will handle related records via foreign key constraints)
            $this->model->delete($id);
            
            $_SESSION['success'] = 'Lesson deleted successfully';
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    
    private function handleFileUpload(array $file, bool $isVideo = false): string {
        // Check for errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception($this->getUploadErrorMessage($file['error']));
        }
        
        // Check file size
        if ($file['size'] > $this->maxFileSize) {
            throw new Exception('File is too large. Maximum size is ' . ($this->maxFileSize / 1024 / 1024) . 'MB');
        }
        
        // Validate file type
        $fileType = mime_content_type($file['tmp_name']);
        
        if ($isVideo) {
            $allowedTypes = ['video/mp4', 'video/webm', 'video/ogg'];
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $targetDir = $this->uploadDir . 'videos/';
        } else {
            $allowedTypes = $this->allowedImageTypes;
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $targetDir = $this->uploadDir . 'thumbnails/';
        }
        
        if (!in_array($fileType, $allowedTypes)) {
            throw new Exception('Invalid file type. Allowed types: ' . implode(', ', $allowedTypes));
        }
        
        // Create directory if it doesn't exist
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        
        // Generate unique filename
        $filename = uniqid() . '.' . $ext;
        $targetPath = $targetDir . $filename;
        
        // Move uploaded file
        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            throw new Exception('Failed to move uploaded file');
        }

        $publicDir = realpath(__DIR__ . '/../public');
        $targetReal = realpath($targetPath);
        if ($publicDir === false || $targetReal === false) {
            throw new Exception('Public directory not found');
        }

        $publicDir = str_replace('\\', '/', $publicDir);
        $targetReal = str_replace('\\', '/', $targetReal);

        if (stripos($targetReal, $publicDir) !== 0) {
            throw new Exception('Upload path is outside of public directory');
        }

        $relative = substr($targetReal, strlen($publicDir));
        return '/public' . $relative;
    }
    
    private function deleteFile(string $path): bool {
        $projectRoot = dirname(__DIR__);
        $fullPath = $projectRoot . $path;
        if (file_exists($fullPath)) {
            return unlink($fullPath);
        }
        return false;
    }
    
    private function getUploadErrorMessage(int $errorCode): string {
        switch ($errorCode) {
            case UPLOAD_ERR_INI_SIZE:
                return 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
            case UPLOAD_ERR_FORM_SIZE:
                return 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
            case UPLOAD_ERR_PARTIAL:
                return 'The uploaded file was only partially uploaded';
            case UPLOAD_ERR_NO_FILE:
                return 'No file was uploaded';
            case UPLOAD_ERR_NO_TMP_DIR:
                return 'Missing a temporary folder';
            case UPLOAD_ERR_CANT_WRITE:
                return 'Failed to write file to disk';
            case UPLOAD_ERR_EXTENSION:
                return 'A PHP extension stopped the file upload';
            default:
                return 'Unknown upload error';
        }
    }
    
    private function saveLessonMeta(int $lessonId, array $data): void {
        // Save prerequisites
        $prerequisites = array_filter(
            array_map('trim', explode("\n", $data['prerequisites'] ?? ''))
        );
        
        // Save learning objectives
        $objectives = array_filter(
            array_map('trim', explode("\n", $data['learning_objectives'] ?? ''))
        );
        
        // Save sections
        $sections = [];
        if (!empty($data['section_titles']) && is_array($data['section_titles'])) {
            foreach ($data['section_titles'] as $index => $title) {
                if (!empty(trim($title))) {
                    $sections[] = [
                        'title' => $title,
                        'content' => $data['section_contents'][$index] ?? '',
                        'order' => $index
                    ];
                }
            }
        }
        
        // Save to database (implementation depends on your database structure)
        $this->model->saveLessonMeta($lessonId, [
            'prerequisites' => $prerequisites,
            'learning_objectives' => $objectives,
            'sections' => $sections
        ]);
    }
    
    private function notFound(): void {
        http_response_code(404);
        require_once __DIR__ . '/../views/errors/404.php';
        exit;
    }

    public function displayFront() {
        $lessons = $this->model->getAll();
        $quizAttemptModel = $this->quizAttemptModel;
        
        // Get featured lessons for the homepage
        $featuredLessons = $this->model->getFeaturedLessons(3);
        $popularLessons = $this->model->getPopularLessons(6);
        
        // Get categories for filtering
        $categories = $this->model->getCategories();
        
        // Set the student ID, fallback to 'guest' if not logged in
    $studentId = $_SESSION['studentId'] ?? 'guest';

    require_once __DIR__ . '/../views/front/lessonDisplay.php';
}

  public function quiz(int $lessonId) {
    // Get lesson data
    $lesson = $this->model->getById($lessonId);
    if (!$lesson) {
        $this->notFound();
        return;
    }

    // Get quiz questions
    $questions = $this->questionModel->getByLesson($lessonId);
    
    // Check if form was submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitQuiz'])) {
        $this->handleQuizSubmission($lessonId, $lesson, $questions);
        return;
    }
    
    // If no submission, show the quiz form
    $this->renderQuizView($lesson, $questions);
}

private function handleQuizSubmission(int $lessonId, array $lesson, array $questions): void {
    $score = 0;
    $total = 0;
    $results = [];
    $studentId = $_SESSION['studentId'] ?? 'guest';
    
    // Calculate time taken
    $startTime = $_POST['start_time'] ?? time();
    $timeTaken = time() - (int)$startTime;
    $timeLimit = ($lesson['quiz_time_limit'] ?? 0) * 60; // Convert minutes to seconds
    $isTimedOut = $timeLimit > 0 && $timeTaken > $timeLimit;
    
    // Calculate score
    foreach($questions as $q) {
        $qid = $q['questionId'];
        $rawAnswer = $_POST['q'.$qid] ?? '';
        $userAnswers = is_array($rawAnswer) ? $rawAnswer : [$rawAnswer];
        $userAnswers = array_values(array_filter(array_map(static function ($v) {
            return trim((string)$v);
        }, $userAnswers), static function ($v) {
            return $v !== '';
        }));

        $correctOptions = [];
        if (isset($q['correctOptions']) && is_array($q['correctOptions']) && !empty($q['correctOptions'])) {
            $correctOptions = $q['correctOptions'];
        } elseif (!empty($q['goodAnswer'])) {
            $correctOptions = [(string)$q['goodAnswer']];
        }
        $correctOptions = array_values(array_filter(array_map('strval', $correctOptions), static function ($v) {
            return trim($v) !== '';
        }));

        sort($userAnswers);
        $normalizedCorrect = array_map('strval', $correctOptions);
        sort($normalizedCorrect);

        $isCorrect = !$isTimedOut && ($userAnswers === $normalizedCorrect);
        $points = (int)($q['points'] ?? 1);
        if ($points <= 0) {
            $points = 1;
        }
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
    
    // If timed out, set score to 0
    if ($isTimedOut) {
        $score = 0;
    }
    
    // Calculate score percentage
    $scorePercentage = $total > 0 ? ($score / $total) * 100 : 0;
    
    // Save attempt if user is logged in
    if (isset($_SESSION['studentId'])) {
        $attemptData = [
            'time_taken' => $timeTaken,
            'timed_out' => $isTimedOut,
            'completed_at' => date('Y-m-d H:i:s')
        ];
        
        $this->quizAttemptModel->saveAttempt([
            'lesson_id' => $lessonId,
            'student_id' => $_SESSION['studentId'],
            'score' => $score,
            'total_questions' => $total,
            'answers' => $results,
            'attempt_data' => $attemptData
        ]);
    }
    
    // Show results with timer information
    $this->renderQuizResults($lesson, $questions, $results, $score, $total);
}

private function renderQuizView(array $lesson, array $questions): void {
    // Make variables available to the view
    $viewLesson = $lesson;
    $viewQuestions = $questions;
    
    // Include the view file
    require_once __DIR__ . '/../views/front/quiz.php';
}

private function renderQuizResults(array $lesson, array $questions, array $results, int $score, int $total): void {
    // Make variables available to the view
    $viewLesson = $lesson;
    $viewQuestions = $questions;
    $viewScore = $score;
    $viewTotal = $total;
    $viewResults = $results;
    $isTimedOut = $results[0]['timed_out'] ?? false;
    $viewResults = $results;
    $viewScore = $score;
    $viewTotal = $total;
    $showResults = true;
    
    // Include the view file
    require_once __DIR__ . '/../views/front/quiz.php';
}

    public function details(int $lessonId) {
        $lesson = $this->model->getById($lessonId);
        if (!$lesson) {
            echo "Lesson not found";
            exit;
        }

        $questions = $this->questionModel->getByLesson($lessonId);
        require_once __DIR__ . '/../views/front/lessonDetails.php';
    }
}
?>
