<?php
require_once __DIR__ . '/../../../init.php';

$lessonId = (int)($_GET['lessonId'] ?? 0);
if ($lessonId <= 0) {
    echo "Invalid lesson ID";
    exit;
}

// Get lesson data directly from model
$lessonModel = new LessonModel();
$viewLesson = $lessonModel->getById($lessonId);

if (!$viewLesson) {
    echo "Lesson not found";
    exit;
}

$questionModel = new QuestionModel();
$questions = $questionModel->getByLesson($lessonId);

if (empty($questions)) {
    echo "No quiz available for this lesson";
    exit;
}

// Handle quiz submission
$showResults = false;
$results = [];
$totalScore = 0;
$totalPoints = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $showResults = true;
    
    foreach ($questions as $question) {
        $questionId = $question['questionId'];
        $points = (int)($question['points'] ?? 1);
        $totalPoints += $points;
        
        // Get user answers
        $userAnswers = $_POST['answers'][$questionId] ?? [];
        if (!is_array($userAnswers)) {
            $userAnswers = [$userAnswers];
        }
        $userAnswers = array_filter($userAnswers, 'strlen');
        $userAnswers = array_values($userAnswers);
        
        // Get correct answers
        $correctIndices = $question['correctIndices'] ?? [];
        
        // Check if answers match exactly
        $isCorrect = count($userAnswers) === count($correctIndices) && 
                    array_diff($userAnswers, $correctIndices) === [] && 
                    array_diff($correctIndices, $userAnswers) === [];
        
        if ($isCorrect) {
            $totalScore += $points;
        }
        
        $results[] = [
            'question' => $question,
            'userAnswers' => $userAnswers,
            'correctIndices' => $correctIndices,
            'isCorrect' => $isCorrect,
            'points' => $points
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Quiz - <?= htmlspecialchars($viewLesson['title']) ?></title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="Take interactive quiz for <?= htmlspecialchars($viewLesson['title']) ?>" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@600&family=Lobster+Two:wght@700&display=swap" rel="stylesheet">
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    
    <style>
        .quiz-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-bottom: 30px;
        }
        
        .quiz-header {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .quiz-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .quiz-meta {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-top: 20px;
            flex-wrap: wrap;
        }
        
        .quiz-meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.95rem;
        }
        
        .quiz-meta-item i {
            font-size: 1.1rem;
        }
        
        .quiz-content {
            padding: 40px;
        }
        
        .timer-container {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            margin-bottom: 30px;
            border: 2px solid #e9ecef;
        }
        
        .timer-display {
            font-size: 2rem;
            font-weight: 700;
            color: #2c3e50;
            font-family: 'Courier New', monospace;
        }
        
        .question-card {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            border-left: 4px solid #4e73df;
        }
        
        .question-number {
            background: #4e73df;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-right: 10px;
        }
        
        .question-text {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 20px;
            line-height: 1.4;
        }
        
        .question-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }
        
        .question-points {
            background: #4e73df;
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .question-timer {
            background: #f6c23e;
            color: #856404;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .options-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .option-item {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 15px 20px;
            margin-bottom: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }
        
        .option-item:hover {
            border-color: #4e73df;
            background: #f8f9ff;
        }
        
        .option-item input[type="radio"],
        .option-item input[type="checkbox"] {
            margin-right: 15px;
            transform: scale(1.2);
        }
        
        .option-item label {
            cursor: pointer;
            margin: 0;
            flex-grow: 1;
            color: #495057;
        }
        
        .quiz-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        
        .btn-quiz {
            padding: 12px 40px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        
        .btn-primary-quiz {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            color: white;
        }
        
        .btn-primary-quiz:hover {
            background: linear-gradient(135deg, #224abe 0%, #1a3a8a 100%);
            color: white;
            transform: translateY(-2px);
        }
        
        .btn-success-quiz {
            background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
            color: white;
        }
        
        .btn-success-quiz:hover {
            background: linear-gradient(135deg, #13855c 0%, #0e5b4a 100%);
            color: white;
            transform: translateY(-2px);
        }
        
        .results-container {
            background: white;
            border-radius: 15px;
            padding: 40px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .score-display {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .score-circle {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 20px;
        }
        
        .score-excellent {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }
        
        .score-good {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        }
        
        .score-average {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
        }
        
        .score-poor {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        }
        
        .result-question {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            border-left: 4px solid #dee2e6;
        }
        
        .result-question.correct {
            border-left-color: #28a745;
            background: #f8fff9;
        }
        
        .result-question.incorrect {
            border-left-color: #dc3545;
            background: #fff8f8;
        }
        
        .result-status {
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            margin-left: 10px;
        }
        
        .result-status.correct {
            background: #d4edda;
            color: #155724;
        }
        
        .result-status.incorrect {
            background: #f8d7da;
            color: #721c24;
        }
        
        @media (max-width:768px) {
            .quiz-content {
                padding: 20px;
            }
            
            .quiz-title {
                font-size: 1.5rem;
            }
            
            .quiz-meta {
                gap: 15px;
            }
            
            .quiz-actions {
                flex-direction: column;
            }
            
            .btn-quiz {
                width: 100%;
            }
        }
    </style>
    
    <script>
    // Per-question timer functionality
    let currentQuestionIndex = 0;
    let timerIntervals = [];
    let questionTimers = [];
    
    // Initialize question timers from PHP data
    <?php foreach ($questions as $index => $question): ?>
        questionTimers[<?= $index ?>] = <?= (int)($question['timeLimit'] ?? 60) ?>;
    <?php endforeach; ?>
    
    function startQuestionTimer(questionIndex) {
        // Clear any existing timer for this question
        if (timerIntervals[questionIndex]) {
            clearInterval(timerIntervals[questionIndex]);
        }
        
        let timeLeft = questionTimers[questionIndex];
        const display = document.querySelector('#timer-display');
        
        if (!display) return;
        
        timerIntervals[questionIndex] = setInterval(function () {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            
            display.textContent = minutes.toString().padStart(2, '0') + ':' + seconds.toString().padStart(2, '0');
            
            if (--timeLeft < 0) {
                clearInterval(timerIntervals[questionIndex]);
                // Auto-advance to next question or submit if last question
                moveToNextQuestion();
            }
            
            // Update stored time
            questionTimers[questionIndex] = timeLeft;
        }, 1000);
    }
    
    function stopCurrentTimer() {
        if (currentQuestionIndex >= 0 && timerIntervals[currentQuestionIndex]) {
            clearInterval(timerIntervals[currentQuestionIndex]);
        }
    }
    
    function showQuestion(questionIndex) {
        // Hide all questions
        document.querySelectorAll('.question-card').forEach(card => {
            card.style.display = 'none';
        });
        
        // Show current question
        const currentCard = document.querySelectorAll('.question-card')[questionIndex];
        if (currentCard) {
            currentCard.style.display = 'block';
        }
        
        // Update question navigation
        updateQuestionNavigation(questionIndex);
    }
    
    function moveToNextQuestion() {
        stopCurrentTimer();
        
        if (currentQuestionIndex < questionTimers.length - 1) {
            currentQuestionIndex++;
            showQuestion(currentQuestionIndex);
            startQuestionTimer(currentQuestionIndex);
        } else {
            // Last question, submit the quiz
            document.getElementById('quiz-form').submit();
        }
    }
    
    function moveToPreviousQuestion() {
        if (currentQuestionIndex > 0) {
            stopCurrentTimer();
            currentQuestionIndex--;
            showQuestion(currentQuestionIndex);
            startQuestionTimer(currentQuestionIndex);
        }
    }
    
    function updateQuestionNavigation(questionIndex) {
        // Update progress indicator
        const progress = ((questionIndex + 1) / questionTimers.length) * 100;
        const progressBar = document.querySelector('.progress-bar');
        if (progressBar) {
            progressBar.style.width = progress + '%';
        }
        
        // Update current question indicator
        const currentIndicator = document.querySelector('.current-question');
        if (currentIndicator) {
            currentIndicator.textContent = 'Question ' + (questionIndex + 1) + ' / ' + questionTimers.length;
        }
        
        // Update navigation buttons
        const prevBtn = document.querySelector('#prev-question');
        const nextBtn = document.querySelector('#next-question');
        
        if (prevBtn) {
            prevBtn.style.display = questionIndex === 0 ? 'none' : 'inline-block';
        }
        
        if (nextBtn) {
            if (questionIndex === questionTimers.length - 1) {
                // Last question - change to submit button
                nextBtn.textContent = 'Submit Quiz';
                nextBtn.className = 'btn btn-success btn-sm';
                nextBtn.setAttribute('onclick', 'document.getElementById("quiz-form").submit()');
            } else {
                // Not last question - show next button
                nextBtn.textContent = 'Next →';
                nextBtn.className = 'btn btn-primary btn-sm';
                nextBtn.setAttribute('onclick', 'moveToNextQuestion()');
            }
        }
    }
    
    window.onload = function () {
        if (!<?php echo $showResults ? 'true' : 'false'; ?>) {
            // Show first question and start its timer
            showQuestion(0);
            startQuestionTimer(0);
            updateQuestionNavigation(0);
        }
    };
    </script>
</head>

<body>
    <div class="container-xxl bg-white p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->

        <!-- Navbar Start -->
        <nav class="navbar navbar-expand-lg bg-white navbar-light sticky-top px-4 px-lg-5 py-lg-0">
            <a href="index.php" class="navbar-brand">
                <h1 class="m-0 text-primary"><i class="fa fa-book-reader me-3"></i>Kider</h1>
            </a>
            <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav mx-auto">
                    <a href="index.php" class="nav-item nav-link">Home</a>
                    <a href="about.html" class="nav-item nav-link">About Us</a>
                    <a href="classes.html" class="nav-item nav-link">Classes</a>
                    <a href="lessons.php" class="nav-item nav-link active">Lessons</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
                        <div class="dropdown-menu rounded-0 rounded-bottom border-0 shadow-sm m-0">
                            <a href="facility.html" class="dropdown-item">School Facilities</a>
                            <a href="team.html" class="dropdown-item">Popular Teachers</a>
                            <a href="call-to-action.html" class="dropdown-item">Become A Teachers</a>
                            <a href="appointment.html" class="dropdown-item">Make Appointment</a>
                            <a href="testimonial.html" class="dropdown-item">Testimonial</a>
                            <a href="404.html" class="dropdown-item">404 Error</a>
                        </div>
                    </div>
                    <a href="contact.html" class="nav-item nav-link">Contact Us</a>
                </div>
                <a href="../back/lessonList_direct.php" class="btn btn-primary rounded-pill px-3 d-none d-lg-block">Admin<i class="fa fa-arrow-right ms-3"></i></a>
            </div>
        </nav>
        <!-- Navbar End -->

        <!-- Page Header Start -->
        <div class="container-xxl py-5 bg-primary hero-header mb-5">
            <div class="container my-5 py-5 px-lg-5">
                <div class="row g-5 py-5">
                    <div class="col-lg-12 text-center">
                        <h1 class="display-2 text-white animated slideInDown mb-4">Quiz Time</h1>
                        <nav aria-label="breadcrumb animated slideInDown">
                            <ol class="breadcrumb justify-content-center">
                                <li class="breadcrumb-item"><a href="index.php" class="text-white">Home</a></li>
                                <li class="breadcrumb-item"><a href="lessons.php" class="text-white">Lessons</a></li>
                                <li class="breadcrumb-item"><a href="lesson-details.php?id=<?= $lessonId ?>" class="text-white"><?= htmlspecialchars($viewLesson['title']) ?></a></li>
                                <li class="breadcrumb-item text-white active" aria-current="page">Quiz</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page Header End -->

        <?php if ($showResults): ?>
            <!-- Results Start -->
            <div class="container-xxl py-5">
                <div class="container px-lg-5">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="results-container">
                                <div class="score-display">
                                    <h2 class="mb-4">Quiz Results</h2>
                                    <?php 
                                    $percentage = $totalPoints > 0 ? ($totalScore / $totalPoints) * 100 : 0;
                                    $scoreClass = 'score-poor';
                                    if ($percentage >= 90) $scoreClass = 'score-excellent';
                                    elseif ($percentage >= 70) $scoreClass = 'score-good';
                                    elseif ($percentage >= 50) $scoreClass = 'score-average';
                                    ?>
                                    <div class="score-circle <?= $scoreClass ?>">
                                        <?= round($percentage) ?>%
                                    </div>
                                    <h3>You scored <?= $totalScore ?> out of <?= $totalPoints ?> points</h3>
                                    <p class="text-muted"><?= round($percentage) ?>% - 
                                        <?php 
                                        if ($percentage >= 90) echo 'Excellent work!';
                                        elseif ($percentage >= 70) echo 'Good job!';
                                        elseif ($percentage >= 50) echo 'Nice try!';
                                        else echo 'Keep practicing!';
                                        ?>
                                    </p>
                                </div>
                                
                                <h4 class="mb-3">Question Review</h4>
                                <?php foreach ($results as $result): ?>
                                    <div class="result-question <?= $result['isCorrect'] ? 'correct' : 'incorrect' ?>">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div class="question-text">
                                                <span class="question-number"><?= array_search($result['question'], $questions) + 1 ?></span>
                                                <?= htmlspecialchars($result['question']['questionText']) ?>
                                                <span class="question-points"><?= $result['points'] ?> pts</span>
                                            </div>
                                            <span class="result-status <?= $result['isCorrect'] ? 'correct' : 'incorrect' ?>">
                                                <?= $result['isCorrect'] ? 'Correct' : 'Incorrect' ?>
                                            </span>
                                        </div>
                                        
                                        <div class="mt-3">
                                            <strong>Your answer(s):</strong> 
                                            <?php 
                                            $userOptions = [];
                                            foreach ($result['userAnswers'] as $answerIndex) {
                                                $userOptions[] = $result['question']['options'][$answerIndex] ?? 'Option ' . ($answerIndex + 1);
                                            }
                                            echo htmlspecialchars(implode(', ', $userOptions));
                                            ?>
                                        </div>
                                        
                                        <div class="mt-2">
                                            <strong>Correct answer(s):</strong> 
                                            <?= htmlspecialchars(implode(', ', (array)($result['question']['correctOptions'] ?? []))) ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                                
                                <div class="quiz-actions">
                                    <a href="quiz.php?lessonId=<?= $lessonId ?>" class="btn-quiz btn-primary-quiz">
                                        <i class="fas fa-redo"></i> Retake Quiz
                                    </a>
                                    <a href="lesson-details.php?id=<?= $lessonId ?>" class="btn-quiz btn-success-quiz">
                                        <i class="fas fa-arrow-left"></i> Back to Lesson
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Results End -->
        <?php else: ?>
            <!-- Quiz Start -->
            <div class="container-xxl py-5">
                <div class="container px-lg-5">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="quiz-container">
                                <div class="quiz-header">
                                    <h2 class="quiz-title"><?= htmlspecialchars($viewLesson['title']) ?> Quiz</h2>
                                    <div class="quiz-meta">
                                        <div class="quiz-meta-item">
                                            <i class="fas fa-question-circle"></i>
                                            <span><?= count($questions) ?> Questions</span>
                                        </div>
                                        <div class="quiz-meta-item">
                                            <i class="fas fa-trophy"></i>
                                            <span><?= array_sum(array_column($questions, 'points')) ?> Points</span>
                                        </div>
                                        <div class="quiz-meta-item">
                                            <i class="fas fa-clock"></i>
                                            <span><?= $viewLesson['quiz_time_limit'] ?? 60 ?> Minutes</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="quiz-content">
                                    <div class="timer-container">
                                        <div class="timer-display" id="timer-display">00:00</div>
                                        <small class="text-muted">Time remaining for current question</small>
                                    </div>
                                    
                                    <!-- Progress Bar -->
                                    <div class="progress mb-3" style="height: 8px;">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 0%"></div>
                                    </div>
                                    
                                    <!-- Question Navigation -->
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="current-question">Question 1 / <?= count($questions) ?></div>
                                        <div class="d-flex gap-2">
                                            <button type="button" id="prev-question" class="btn btn-outline-secondary btn-sm" onclick="moveToPreviousQuestion()" style="display: none;">← Previous</button>
                                            <button type="button" id="next-question" class="btn btn-primary btn-sm" onclick="moveToNextQuestion()">Next →</button>
                                        </div>
                                    </div>
                                    
                                    <form method="post" id="quiz-form">
                                        <?php foreach ($questions as $index => $question): ?>
                                            <div class="question-card" style="display: none;">
                                                <div class="d-flex align-items-center mb-3">
                                                    <span class="question-number"><?= $index + 1 ?></span>
                                                    <div class="question-text flex-grow-1"><?= htmlspecialchars($question['questionText']) ?></div>
                                                </div>
                                                
                                                <div class="question-meta">
                                                    <span class="question-points"><?= $question['points'] ?> points</span>
                                                    <span class="question-timer"><?= (int)($question['timeLimit'] ?? 60) ?> seconds</span>
                                                </div>
                                                
                                                <ul class="options-list">
                                                    <?php 
                                                    $correctIndices = $question['correctIndices'] ?? [];
                                                    $isMultiCorrect = count($correctIndices) > 1;
                                                    
                                                    foreach ($question['options'] as $optionIndex => $option): 
                                                        $inputType = $isMultiCorrect ? 'checkbox' : 'radio';
                                                        $inputName = "answers[{$question['questionId']}]" . ($isMultiCorrect ? "[]" : "");
                                                    ?>
                                                        <li class="option-item">
                                                            <input type="<?= $inputType ?>" 
                                                                   name="<?= $inputName ?>" 
                                                                   value="<?= $optionIndex ?>" 
                                                                   id="q<?= $question['questionId'] ?>_opt<?= $optionIndex ?>"
                                                                   <?= $isMultiCorrect ? '' : 'required' ?>>
                                                            <label for="q<?= $question['questionId'] ?>_opt<?= $optionIndex ?>">
                                                                <?= htmlspecialchars($option) ?>
                                                            </label>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        <?php endforeach; ?>
                                        
                                        <div class="quiz-actions">
                                            <button type="submit" class="btn-quiz btn-success-quiz">
                                                <i class="fas fa-check"></i> Submit Quiz
                                            </button>
                                            <a href="lesson-details.php?id=<?= $lessonId ?>" class="btn-quiz btn-primary-quiz">
                                                <i class="fas fa-times"></i> Cancel
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Quiz End -->
        <?php endif; ?>

        <!-- Footer Start -->
        <div class="container-fluid bg-dark text-white-50 py-4 px-lg-5">
            <div class="container px-lg-5">
                <div class="row gx-5">
                    <div class="col-md-6 text-center text-md-start">
                        <p class="mb-0">&copy; <a href="#" class="text-white">Kider</a>. All Rights Reserved.</p>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <p class="mb-0">Designed with <i class="fas fa-heart text-danger"></i> by <a href="https://htmlcodex.com" class="text-white">HTML Codex</a></p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>
</html>
