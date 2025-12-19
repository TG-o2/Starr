<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../../Controller/QuizCompletionController.php';

$quizResults = null;
$quizTimedOut = false;
$totalPointsEarned = 0;
$maxPossiblePoints = 0;
$starrPointsAwarded = 0;
$starrPointsMessage = '';
$quizCompletionResult = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $totalQuestions = count($questions);
    $correctAnswers = 0;
    $quizResults = [];
    $quizTimedOut = isset($_POST['quiz_timeout']) && $_POST['quiz_timeout'] === '1';
    $timeSpent = isset($_POST['time_spent']) ? (int)$_POST['time_spent'] : null;

    foreach ($questions as $question) {
        $questionId = $question['questionId'];
        $options = [
            'A' => $question['optionA'] ?? '',
            'B' => $question['optionB'] ?? '',
            'C' => $question['optionC'] ?? '',
            'D' => $question['optionD'] ?? '',
        ];

        $userAnswer = $_POST['q' . $questionId] ?? '';
        $correctAnswer = $question['goodAnswer'];
        $questionPoints = (int) ($question['points'] ?? 5);
        $maxPossiblePoints += $questionPoints;

        $isCorrect = strtoupper(trim($userAnswer)) === strtoupper(trim($correctAnswer));
        if ($isCorrect) {
            $correctAnswers++;
            $totalPointsEarned += $questionPoints;
        }

        $quizResults[] = [
            'questionText' => $question['question'] ?? '',
            'userAnswer' => $userAnswer,
            'userAnswerText' => $options[$userAnswer] ?? '',
            'correctAnswer' => $correctAnswer,
            'correctAnswerText' => $options[$correctAnswer] ?? '',
            'isCorrect' => $isCorrect,
            'options' => $options,
            'points' => $questionPoints,
        ];
    }

    $scorePercentage = ($totalQuestions > 0) ? round(($correctAnswers / $totalQuestions) * 100) : 0;

    // AWARD STARR POINTS
    if (isset($_SESSION['user_id']) && isset($lesson['lessonId'])) {
        try {
            $quizCompletionController = new QuizCompletionController();
            $quizCompletionResult = $quizCompletionController->processQuizCompletion(
                $_SESSION['user_id'],
                $lesson['lessonId'],
                $scorePercentage,
                $timeSpent
            );

            if ($quizCompletionResult['success']) {
                $starrPointsAwarded = $quizCompletionResult['points_awarded'];
                $starrPointsMessage = $quizCompletionResult['message'];
            }
        } catch (Exception $e) {
            error_log("Quiz points award error: " . $e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo htmlspecialchars($lesson['title']); ?> Quiz | Starr</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="../logo.jpeg" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Handlee&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="../assets/lib/animate/animate.min.css" rel="stylesheet">
    <link href="../assets/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Template Stylesheet -->
    <link href="../assets/css/style.css" rel="stylesheet">

    <style>
        .quiz-container { max-width: 900px; margin: 0 auto; padding: 20px; }
        .timer-container { position: sticky; top: 20px; z-index: 100; background: white; padding: 20px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1); margin-bottom: 30px; text-align: center; }
        .timer-label { font-size: 0.9rem; font-weight: 600; color: #666; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 1px; }
        .timer-display { font-size: 3rem; font-weight: 900; font-family: 'Courier New', monospace; color: #FE6B8B; transition: color 0.3s ease; }
        .timer-display.warning { color: #FF8E53; animation: pulse 1s infinite; }
        .timer-display.critical { color: #f44336; animation: pulse 0.5s infinite; }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.7; } }
        .timer-message { font-size: 0.95rem; color: #666; margin-top: 10px; font-weight: 500; }
        .timer-message.timeout { color: #f44336; font-weight: 700; }
        .question-card { background: white; padding: 30px; margin-bottom: 25px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1); }
        .question-number { display: inline-block; background: linear-gradient(135deg, #FE6B8B 0%, #FF8E53 100%); color: white; padding: 8px 20px; border-radius: 25px; font-weight: 700; margin-bottom: 20px; font-size: 1rem; }
        .question-text { font-size: 1.2rem; font-weight: 600; margin-bottom: 25px; color: #2c3e50; line-height: 1.6; }
        .options { display: flex; flex-direction: column; gap: 15px; }
        .option-item { position: relative; }
        .option-item input[type="radio"] { position: absolute; left: 0; top: 0; opacity: 0; cursor: pointer; }
        .option-label { display: block; padding: 18px 20px; border: 3px solid #e9ecef; border-radius: 12px; cursor: pointer; transition: all 0.3s ease; background: #f8f9fa; font-size: 1.05rem; font-weight: 500; }
        .option-item input[type="radio"]:checked + .option-label { border-color: #FE6B8B; background: linear-gradient(135deg, rgba(254, 107, 139, 0.1) 0%, rgba(255, 142, 83, 0.1) 100%); font-weight: 700; color: #FE6B8B; }
        .option-label:hover { border-color: #FE6B8B; background: #fff5f7; }
        .results-container { background: white; padding: 40px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1); }
        .score-display { text-align: center; margin-bottom: 40px; padding: 50px; background: linear-gradient(135deg, #FE6B8B 0%, #FF8E53 100%); color: white; border-radius: 15px; box-shadow: 0 8px 30px rgba(254, 107, 139, 0.3); }
        .score-number { font-size: 4rem; font-weight: 900; margin-bottom: 15px; }
        .score-label { font-size: 1.3rem; opacity: 0.95; font-weight: 600; }
        .result-item { padding: 25px; margin-bottom: 20px; border-radius: 12px; border-left: 5px solid #ccc; }
        .result-item.correct { background: linear-gradient(135deg, #e8f5e9 0%, #f1f8f4 100%); border-left-color: #4caf50; }
        .result-item.incorrect { background: linear-gradient(135deg, #ffebee 0%, #fef1f1 100%); border-left-color: #f44336; }
        .result-item.timeout { background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%); border-left-color: #ff9800; }
        .result-question { font-weight: 700; margin-bottom: 15px; color: #2c3e50; font-size: 1.1rem; }
        .result-answer { font-size: 1rem; margin: 10px 0; color: #555; }
        .result-icon { font-weight: 700; margin-right: 10px; font-size: 1.1rem; }
        .result-icon.correct::before { content: "✓ "; color: #4caf50; }
        .result-icon.incorrect::before { content: "✗ "; color: #f44336; }
        .empty-state { text-align: center; padding: 80px 40px; background: white; border-radius: 15px; }
        .empty-state i { font-size: 5rem; color: #ddd; margin-bottom: 25px; }
        .empty-state p { font-size: 1.2rem; color: #666; }
        .btn-submit-quiz { background: linear-gradient(135deg, #FE6B8B 0%, #FF8E53 100%); color: white; padding: 15px 40px; border-radius: 30px; font-weight: 700; font-size: 1.1rem; border: none; transition: all 0.3s ease; }
        .btn-submit-quiz:hover { transform: translateY(-3px); box-shadow: 0 10px 30px rgba(254, 107, 139, 0.4); color: white; }
        .button-group { display: flex; gap: 20px; margin-top: 30px; flex-wrap: wrap; }
        @media (max-width: 768px) { .score-number { font-size: 2.5rem; } .button-group { flex-direction: column; } .button-group a, .button-group button { width: 100%; text-align: center; } .timer-display { font-size: 2rem; } }
    </style>
</head>

<body>
    <div class="container-xxl bg-white p-0">
        
        <!-- Navbar Start -->
        <nav class="navbar navbar-expand-lg bg-white navbar-light sticky-top px-4 px-lg-5 py-lg-0">
            <a class="navbar-brand">
                <h1 class="m-0 text-primary">
                    <img src="../logo.jpeg" alt="Starr Logo" style="height: 60px; vertical-align: middle; margin-right: 8px;">
                    Starr
                </h1>
            </a>
            <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav mx-auto">
                    <a href="../index.html" class="nav-item nav-link">Home</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Posts</a>
                        <div class="dropdown-menu rounded-0 rounded-bottom border-0 m-0">
                            <a href="../posts and comments/addpost.php" class="dropdown-item">Create a post</a>
                            <a href="../posts and comments/posts.html" class="dropdown-item">View posts</a>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Report</a>
                        <div class="dropdown-menu rounded-0 rounded-bottom border-0 m-0">
                            <a href="../Reports/Make-report.html" class="dropdown-item">Make a report</a>
                            <a href="../Reports/Messages.php" class="dropdown-item">Check response</a>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Starrs</a>
                        <div class="dropdown-menu rounded-0 rounded-bottom border-0 m-0">
                            <a href="../point system/my-points.php" class="dropdown-item">My Starrs</a>
                            <a href="../point system/badges.php" class="dropdown-item">Badges</a>
                        </div>
                    </div>
                    <a href="../News/gestionnews.php" class="nav-item nav-link">Articles</a>
                    <a href="lessonDisplay_direct.php" class="nav-item nav-link active">Education Corner</a>
                    <a href="../User-signup/viewProfile.php" class="nav-item nav-link">Profile</a>
                    
                </div>
            </div>
        </nav>
        <!-- Navbar End -->

        <!-- Page Header Start -->
        <div class="container-xxl py-5 page-header position-relative mb-5">
            <div class="container py-5">
                <h1 class="display-2 text-white animated slideInDown mb-4">Take the Quiz</h1>
                <nav aria-label="breadcrumb animated slideInDown">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="../index.html">Home</a></li>
                        <li class="breadcrumb-item"><a href="lessonDisplay_direct.php">Lessons</a></li>
                        <li class="breadcrumb-item text-white active" aria-current="page"><?php echo htmlspecialchars($lesson['title']); ?> Quiz</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header End -->

        <!-- Quiz Content Start -->
        <div class="container-xxl py-5">
            <div class="quiz-container">
                <?php if ($quizResults === null): ?>
                    <?php if (empty($questions)): ?>
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <p>No quiz questions available for this lesson.</p>
                            <a href="lessonDisplay_direct.php" class="btn btn-primary btn-lg mt-3">Back to Lessons</a>
                        </div>
                    <?php else: ?>
                        <!-- Timer Display -->
                        <div class="timer-container">
                            <div class="timer-label"><i class="fas fa-hourglass-start"></i> Time Remaining</div>
                            <div class="timer-display" id="timerDisplay">00:30</div>
                            <div class="timer-message" id="timerMessage">Complete the quiz before time runs out!</div>
                        </div>

                        <form method="POST" class="quiz-form" id="quizForm">
                    <?php foreach ($questions as $index => $question): ?>
                        <?php 
                            $options = [
                                'A' => $question['optionA'] ?? '',
                                'B' => $question['optionB'] ?? '',
                                'C' => $question['optionC'] ?? '',
                                'D' => $question['optionD'] ?? '',
                            ];
                        ?>
                        <div class="question-card">
                            <span class="question-number">Question <?php echo $index + 1; ?> of <?php echo count($questions); ?></span>
                            <div class="question-text"><?php echo htmlspecialchars($question['question'] ?? ''); ?></div>

                            <div class="options">
                                <?php foreach ($options as $label => $text): ?>
                                    <?php if (!empty($text)): ?>
                                        <div class="option-item">
                                            <input type="radio" id="q<?php echo $question['questionId']; ?>_opt<?php echo $label; ?>" name="q<?php echo $question['questionId']; ?>" value="<?php echo $label; ?>" required>
                                            <label class="option-label" for="q<?php echo $question['questionId']; ?>_opt<?php echo $label; ?>">
                                                <strong><?php echo $label; ?>.</strong> <?php echo htmlspecialchars($text); ?>
                                            </label>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>

                        <input type="hidden" name="time_spent" id="timeSpentInput" value="0">
                        <div class="button-group">
                            <button type="submit" class="btn-submit-quiz"><i class="fas fa-check-circle"></i> Submit Quiz</button>
                            <a href="lessonDisplay_direct.php" class="btn btn-outline-secondary btn-lg"><i class="fas fa-arrow-left"></i> Back to Lessons</a>
                        </div>
                    </form>
                <?php endif; ?>

            <?php else: ?>
                <div class="results-container">
                    <?php if ($quizTimedOut): ?>
                        <div class="alert alert-warning alert-dismissible fade show" role="alert" style="margin-bottom: 30px; border-radius: 12px; padding: 20px; font-size: 1.05rem;">
                            <i class="fas fa-exclamation-triangle"></i> <strong>Time's Up!</strong> Your quiz was automatically submitted because time ran out. Unanswered questions are counted as incorrect.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <div class="score-display">
                        <div class="score-number"><?php echo $correctAnswers; ?> / <?php echo count($quizResults); ?></div>
                        <div class="score-label">You scored <?php echo $scorePercentage; ?>% 🎉</div>
                        <div class="score-label" style="font-size: 1.5rem; margin-top: 15px; border-top: 2px solid rgba(255,255,255,0.3); padding-top: 15px;">
                            <i class="fas fa-star"></i> <?php echo $totalPointsEarned; ?> / <?php echo $maxPossiblePoints; ?> Points Earned
                        </div>
                    </div>

                    <?php if ($starrPointsAwarded > 0): ?>
                        <div class="alert alert-success d-flex align-items-center" style="margin: 20px 0; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);">
                            <div style="flex: 1;">
                                <h4 class="alert-heading mb-2" style="color: white;">
                                    <i class="fas fa-trophy"></i> Starr Points Awarded!
                                </h4>
                                <p class="mb-0" style="color: white; font-size: 1.1rem;">
                                    <?php echo htmlspecialchars($starrPointsMessage); ?>
                                </p>
                                <?php if ($quizCompletionResult && isset($quizCompletionResult['total_points'])): ?>
                                    <small style="color: rgba(255,255,255,0.9);">
                                        Total Starr Points: <strong><?php echo $quizCompletionResult['total_points']; ?></strong>
                                    </small>
                                <?php endif; ?>
                            </div>
                            <div style="font-size: 3rem; margin-left: 20px; color: #ffd700;">
                                <i class="fas fa-coins"></i>
                            </div>
                        </div>
                    <?php elseif ($quizCompletionResult && !$quizCompletionResult['success']): ?>
                        <div class="alert alert-info" style="margin: 20px 0;">
                            <i class="fas fa-info-circle"></i> Points could not be awarded at this time.
                        </div>
                    <?php endif; ?>

                    <h3 class="mb-4"><i class="fas fa-clipboard-list"></i> Detailed Results</h3>
                    <?php foreach ($quizResults as $index => $result): ?>
                        <div class="result-item <?php echo $result['isCorrect'] ? 'correct' : 'incorrect'; ?>">
                            <div class="result-question">
                                <i class="fas fa-question-circle"></i> Question <?php echo $index + 1; ?>: <?php echo htmlspecialchars($result['questionText']); ?>
                                <span class="badge" style="background: <?php echo $result['isCorrect'] ? '#4caf50' : '#ccc'; ?>; color: white; margin-left: 10px; font-size: 0.85rem;">
                                    <i class="fas fa-star"></i> <?php echo $result['isCorrect'] ? '+' . $result['points'] : '0'; ?> / <?php echo $result['points']; ?> pts
                                </span>
                            </div>
                            <div class="result-answer">
                                <span class="result-icon <?php echo $result['isCorrect'] ? 'correct' : 'incorrect'; ?>"></span>
                                Your answer: <strong>
                                    <?php 
                                        if ($result['userAnswer'] === '') {
                                            echo '(Not answered)';
                                        } else {
                                            $ua = strtoupper($result['userAnswer']);
                                            $uaText = $result['userAnswerText'] ?? '';
                                            echo $ua;
                                            if (!empty($uaText)) {
                                                echo ' - ' . htmlspecialchars($uaText);
                                            }
                                        }
                                    ?>
                                </strong>
                            </div>
                            <?php if (!$result['isCorrect']): ?>
                                <div class="result-answer" style="color: #4caf50; font-weight: 600;">
                                    <?php $caText = $result['correctAnswerText'] ?? ''; ?>
                                    <i class="fas fa-check-circle"></i> Correct answer: <strong><?php echo htmlspecialchars(strtoupper($result['correctAnswer'])); ?><?php echo !empty($caText) ? ' - ' . htmlspecialchars($caText) : ''; ?></strong>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>

                    <div class="button-group">
                        <a href="lessonQuiz_direct.php?lessonId=<?php echo htmlspecialchars($lesson['lessonId']); ?>" class="btn-submit-quiz" style="text-decoration: none;"><i class="fas fa-redo-alt"></i> Retake Quiz</a>
                        <a href="lessonDisplay_direct.php" class="btn btn-outline-secondary btn-lg"><i class="fas fa-arrow-left"></i> Back to Lessons</a>
                    </div>
                </div>
            <?php endif; ?>
            </div>
        </div>
        <!-- Quiz Content End -->

        <!-- Footer Start -->
        <div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
            <div class="container py-5">
                <div class="row g-5">
                    <div class="col-lg-3 col-md-6">
                        <h3 class="text-white mb-4">Get In Touch</h3>
                        <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>Starr Education Center</p>
                        <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>+012 345 67890</p>
                        <p class="mb-2"><i class="fa fa-envelope me-3"></i>info@starr.edu</p>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h3 class="text-white mb-4">Quick Links</h3>
                        <a class="btn btn-link text-white-50" href="../index.html">Home</a>
                        <a class="btn btn-link text-white-50" href="../about.html">About Us</a>
                        <a class="btn btn-link text-white-50" href="lessonDisplay_direct.php">Lessons</a>
                        <a class="btn btn-link text-white-50" href="../contact.html">Contact Us</a>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h3 class="text-white mb-4">Newsletter</h3>
                        <p>Stay updated with our latest lessons!</p>
                        <div class="position-relative mx-auto" style="max-width: 400px;">
                            <input class="form-control bg-transparent w-100 py-3 ps-4 pe-5" type="text" placeholder="Your email">
                            <button type="button" class="btn btn-primary py-2 position-absolute top-0 end-0 mt-2 me-2">Subscribe</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="copyright">
                    <div class="row">
                        <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                            &copy; <a class="border-bottom" href="#">Starr Education Corner</a>, All Right Reserved.
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->

        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/lib/wow/wow.min.js"></script>
    <script src="../assets/lib/easing/easing.min.js"></script>
    <script src="../assets/lib/waypoints/waypoints.min.js"></script>
    <script src="../assets/lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="../assets/js/main.js"></script>

    <script>
        // Quiz Timer - 30 seconds per quiz
        const QUIZ_TIME_LIMIT = 30; // seconds
        let timeRemaining = QUIZ_TIME_LIMIT;
        let timerInterval = null;
        let isTimedOut = false;
        let startTime = Date.now(); // Track start time for duration calculation

        function initializeTimer() {
            const timerDisplay = document.getElementById('timerDisplay');
            const timerMessage = document.getElementById('timerMessage');
            const quizForm = document.getElementById('quizForm');

            if (!timerDisplay || !quizForm) return;

            // Update time spent on form submission
            quizForm.addEventListener('submit', function() {
                const timeSpentSeconds = Math.floor((Date.now() - startTime) / 1000);
                document.getElementById('timeSpentInput').value = timeSpentSeconds;
            });

            function updateTimerDisplay() {
                const minutes = Math.floor(timeRemaining / 60);
                const seconds = timeRemaining % 60;
                timerDisplay.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;

                // Update styling based on time remaining
                timerDisplay.classList.remove('warning', 'critical');
                if (timeRemaining <= 10 && timeRemaining > 5) {
                    timerDisplay.classList.add('warning');
                    timerMessage.textContent = 'Hurry up! Time is running out...';
                } else if (timeRemaining <= 5) {
                    timerDisplay.classList.add('critical');
                    timerMessage.textContent = '⚠️ CRITICAL: Less than 5 seconds remaining!';
                }
            }

            function handleTimeExpired() {
                isTimedOut = true;
                timerDisplay.classList.remove('warning');
                timerDisplay.classList.add('critical');
                timerMessage.textContent = '⏰ Time\'s Up! Your quiz has been submitted.';
                timerMessage.classList.add('timeout');
                
                // Add a hidden input to track timeout
                const timeoutInput = document.createElement('input');
                timeoutInput.type = 'hidden';
                timeoutInput.name = 'quiz_timeout';
                timeoutInput.value = '1';
                quizForm.appendChild(timeoutInput);

                // Auto-submit the form
                setTimeout(() => {
                    quizForm.submit();
                }, 1000);
            }

            // Start the timer
            updateTimerDisplay();
            timerInterval = setInterval(() => {
                timeRemaining--;
                updateTimerDisplay();

                if (timeRemaining <= 0) {
                    clearInterval(timerInterval);
                    handleTimeExpired();
                }
            }, 1000);

            // Prevent form submission if timer is running
            quizForm.addEventListener('submit', function(e) {
                if (!isTimedOut && timerInterval) {
                    clearInterval(timerInterval);
                }
            });

            // Clear timer on page unload
            window.addEventListener('beforeunload', () => {
                if (timerInterval) clearInterval(timerInterval);
            });
        }

        // Initialize timer when DOM is ready
        document.addEventListener('DOMContentLoaded', initializeTimer);
    </script>
