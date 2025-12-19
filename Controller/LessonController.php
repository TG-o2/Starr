<?php
require_once __DIR__ . '/../Model/Lesson.php';
require_once __DIR__ . '/../Model/Question.php';

class LessonController
{
    private LessonModel $model;
    private QuestionModel $questionModel;

    public function __construct()
    {
        $this->model = new LessonModel();
        $this->questionModel = new QuestionModel();
    }

    public function list(): void
    {
        $lessons = $this->model->getAll();
         require __DIR__ . '/../View/Back office/Education Corner/lessonList.php';
    }

    public function add(array $postData = []): void
    {
        if (!empty($postData)) {
            $title = trim($postData['title'] ?? '');
            $ageRange = trim($postData['ageRange'] ?? '');
            $duration = (int) ($postData['duration'] ?? 0);
            $description = trim($postData['description'] ?? '');
            
            // Handle file upload
            $image = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../View/Front office/assets/uploads/lessons/';
                
                // Create directory if it doesn't exist
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $imageName = time() . '_' . basename($_FILES['image']['name']);
                $imagePath = $uploadDir . $imageName;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                    $image = '../assets/uploads/lessons/' . $imageName;
                }
            }

            if ($title === '' || $ageRange === '' || $duration <= 0 || $description === '') {
                $error = 'All fields required. Duration must be positive.';
                 require __DIR__ . '/../View/Back office/Education Corner/lessonAdd.php';
                return;
            }

            $this->model->create([
                'title' => $title,
                'ageRange' => $ageRange,
                'duration' => $duration,
                'description' => $description,
                'image' => $image,
            ]);

            header('Location: lessonList_direct.php');
            exit;
        }

        require __DIR__ . '/../View/Back office/Education Corner/lessonAdd.php';
    }

    public function edit(int $lessonId, array $postData = []): void
    {
        $lesson = $this->model->getById($lessonId);
        if (!$lesson) {
            echo 'Lesson not found';
            exit;
        }

        if (!empty($postData)) {
            $title = trim($postData['title'] ?? '');
            $ageRange = trim($postData['ageRange'] ?? '');
            $duration = (int) ($postData['duration'] ?? 0);
            $description = trim($postData['description'] ?? '');
            
            // Keep existing image by default
            $image = $lesson['image'];
            
            // Handle file upload if new image provided
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../View/Front office/assets/uploads/lessons/';
                
                // Create directory if it doesn't exist
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $imageName = time() . '_' . basename($_FILES['image']['name']);
                $imagePath = $uploadDir . $imageName;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                    $image = '../assets/uploads/lessons/' . $imageName;
                    
                    // Delete old image if exists
                    if (!empty($lesson['image'])) {
                        $oldImagePath = __DIR__ . '/../View/Front office/' . $lesson['image'];
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                        }
                    }
                }
            }

            if ($title === '' || $ageRange === '' || $duration <= 0 || $description === '') {
                $error = 'All fields required. Duration must be positive.';
                require __DIR__ . '/../View/Back office/Education Corner/lessonEdit.php';
                return;
            }

            $this->model->update($lessonId, [
                'title' => $title,
                'ageRange' => $ageRange,
                'duration' => $duration,
                'description' => $description,
                'image' => $image,
            ]);

            header('Location: lessonList_direct.php');
            exit;
        }

        require __DIR__ . '/../View/Back office/Education Corner/lessonEdit.php';
    }

    public function delete(int $lessonId): void
    {
        $questions = $this->questionModel->getByLesson($lessonId);
        foreach ($questions as $q) {
            $qId = $q['questionId'] ?? null;
            if ($qId) {
                $this->questionModel->delete((int) $qId);
            }
        }

        $this->model->delete($lessonId);
        header('Location: lessonList_direct.php');
        exit;
    }

    public function displayFront(): void
    {
        $lessons = $this->model->getAll();
        require __DIR__ . '/../View/Front office/Education Corner/lessonDisplay.php';
    }

    public function quiz(int $lessonId): void
    {
        $lesson = $this->model->getById($lessonId);
        if (!$lesson) {
            echo 'Lesson not found';
            exit;
        }

        $questions = $this->questionModel->getByLesson($lessonId);
        require __DIR__ . '/../View/Front office/Education Corner/lessonQuiz.php';
    }

    public function details(int $lessonId): void
    {
        $lesson = $this->model->getById($lessonId);
        if (!$lesson) {
            echo 'Lesson not found';
            exit;
        }

        $questions = $this->questionModel->getByLesson($lessonId);
        require __DIR__ . '/../View/Front office/Education Corner/lessonDetails.php';
    }
}
