<?php
require_once __DIR__ . '/../models/LessonModel.php';
require_once __DIR__ . '/../models/QuestionModel.php';

class LessonController {
    private LessonModel $model;
    private QuestionModel $questionModel;


    public function __construct() {
        $this->model = new LessonModel();      // model, not entity
        $this->questionModel = new QuestionModel(); // model, not entity
    }

    public function list() {
        $lessons = $this->model->getAll();
        require_once __DIR__ . '/../views/back/lessonList.php';
    }

    public function add(array $postData = []) {
        if (!empty($postData)) {
            $title = trim($postData['title'] ?? '');
            $ageRange = trim($postData['ageRange'] ?? '');
            $duration = intval($postData['duration'] ?? 0);
            $description = trim($postData['description'] ?? '');
            $image = trim($postData['image'] ?? null);

            if ($title === '' || $ageRange === '' || $duration <= 0 || $description === '') {
                $error = "All fields required. Duration must be positive.";
                require_once __DIR__ . '/../views/back/lessonAdd.php';
                return;
            }

            $this->model->create([
                'title' => $title,
                'ageRange' => $ageRange,
                'duration' => $duration,
                'description' => $description,
                'image' => $image
            ]);

            header("Location: /lessons_project/views/back/lessonList_direct.php");
            exit;
        }

        require_once __DIR__ . '/../views/back/lessonAdd.php';
    }

    public function edit(int $lessonId, array $postData = []) {
        $lesson = $this->model->getById($lessonId);
        if (!$lesson) {
            echo "Lesson not found";
            exit;
        }

        if (!empty($postData)) {
            $title = trim($postData['title'] ?? '');
            $ageRange = trim($postData['ageRange'] ?? '');
            $duration = intval($postData['duration'] ?? 0);
            $description = trim($postData['description'] ?? '');
            $image = trim($postData['image'] ?? null);

            if ($title === '' || $ageRange === '' || $duration <= 0 || $description === '') {
                $error = "All fields required. Duration must be positive.";
                require_once __DIR__ . '/../views/back/lessonEdit.php';
                return;
            }

            $this->model->update($lessonId, [
                'title' => $title,
                'ageRange' => $ageRange,
                'duration' => $duration,
                'description' => $description,
                'image' => $image
            ]);

            header("Location: /lessons_project/views/back/lessonList_direct.php");
            exit;
        }

        require_once __DIR__ . '/../views/back/lessonEdit.php';
    }

    public function delete(int $lessonId) {
        // Delete all questions for this lesson first
        $questions = $this->questionModel->getByLesson($lessonId);
        foreach ($questions as $q) {
            $qId = $q['questionId'] ?? null;
            if ($qId) {
                $this->questionModel->delete($qId);
            }
        }

        // Delete lesson
        $this->model->delete($lessonId);

        header("Location: /lessons_project/views/back/lessonList_direct.php");
        exit;
    }

    public function displayFront() {
        $lessons = $this->model->getAll();
        require_once __DIR__ . '/../views/front/lessonDisplay.php';
    }

    public function quiz(int $lessonId) {
        $lesson = $this->model->getById($lessonId);
        if (!$lesson) {
            echo "Lesson not found";
            exit;
        }

        $questions = $this->questionModel->getByLesson($lessonId);
        require_once __DIR__ . '/../views/front/lessonQuiz.php';
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
