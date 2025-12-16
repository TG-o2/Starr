<?php
// Check if student already attempted this quiz
$studentId = $_SESSION['studentId'] ?? 'guest';
$quizAlreadyAttempted = $quizAttempted ?? false;
$previousAttempt = $quizAttempt ?? null;
$quizResults = null; // Initialize quizResults to avoid undefined variable notice

// Process quiz submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$quizAlreadyAttempted) {
    $totalQuestions = count($questions);
    $correctAnswers = 0;
    
    foreach ($questions as $question) {
        $questionId = $question['questionId'];
        $userAnswer = isset($_POST['q' . $questionId]) && !empty($_POST['q' . $questionId]) ? $_POST['q' . $questionId] : '';
        $correctAnswer = $question['goodAnswer'];
        
        $isCorrect = false;
        if (!empty($userAnswer)) {
            $isCorrect = (strtolower(trim($userAnswer)) === strtolower(trim($correctAnswer)));
        }
        
        if ($isCorrect) {
            $correctAnswers++;
        }
    }

    $scorePercentage = ($totalQuestions > 0) ? round(($correctAnswers / $totalQuestions) * 100) : 0;
    
    // Record the quiz attempt in database
    require_once __DIR__ . '/../../models/QuizAttemptModel.php';
    $quizAttemptModel = new QuizAttemptModel();
    $totalQuestions = count($questions);
$correctAnswers = 0;

// Calculate correct answers
foreach ($questions as $question) {
    $qid = $question['questionId'];
    $userAnswer = $userAnswers[$qid] ?? '';
    if (strtolower(trim($userAnswer)) === strtolower(trim($question['goodAnswer']))) {
        $correctAnswers++;
    }
}

$scorePercentage = ($totalQuestions > 0) ? round(($correctAnswers / $totalQuestions) * 100) : 0;

// Record the attempt
$quizAttemptModel->recordAttempt($lesson['lessonId'], $correctAnswers, $totalQuestions, $scorePercentage, $studentId);


    // Set session for completed quiz
    $_SESSION['last_quiz_completed'] = $lesson['lessonId'];
    
    // IMMEDIATELY REDIRECT
    header("Location: http://localhost/lessons_project/views/front/lessonDisplay_direct.php");
    exit;
}

// If we get here, we need to track current question
$currentQuestionIndex = 0;
$totalQuestions = count($questions);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($lesson['title']); ?> - Quiz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
document.addEventListener('DOMContentLoaded', function() {
    let timeLeft = 10;
    let currentQuestion = 0;
    const totalQuestions = <?php echo $totalQuestions; ?>;
    const timerElement = document.querySelector('.timer');
    const form = document.getElementById('quizForm');
    const submitBtn = document.getElementById('submitQuizBtn');
    const nextBtn = document.getElementById('nextBtn');
    const prevBtn = document.getElementById('prevBtn');
    const currentQElement = document.getElementById('currentQ');
    const questionTextElement = document.getElementById('questionText');
    const optionsElement = document.getElementById('options');
    let hasSubmitted = false;
    let userAnswers = {}; // Store answers as {questionId: answer}
    
    // Load a specific question
    function loadQuestion(index) {
        currentQuestion = index;
        currentQElement.textContent = index + 1;
        
        // Get question data from hidden inputs
        const questionText = document.getElementById('qText' + index).value;
        const questionId = document.getElementById('qId' + index).value;
        
        // Display question text
        questionTextElement.innerHTML = questionText;
        
        // Display options
        optionsElement.innerHTML = '';
        for (let i = 1; i <= 4; i++) {
            const optionId = 'q' + index + 'opt' + i;
            const optionElement = document.getElementById(optionId);
            if (optionElement && optionElement.value) {
                const optionDiv = document.createElement('div');
                optionDiv.className = 'option-item';
                optionDiv.innerHTML = `
                    <label class="option-label">
                        <input type="radio" name="q${questionId}" value="${optionElement.value}">
                        <span class="option-text">${optionElement.value}</span>
                    </label>
                `;
                optionsElement.appendChild(optionDiv);
            }
        }
        
        // Check if user already answered this question
        const questionIdValue = questionId;
        if (userAnswers[questionIdValue]) {
            // Pre-select the answer
            const radios = optionsElement.querySelectorAll(`input[value="${userAnswers[questionIdValue]}"]`);
            if (radios.length > 0) {
                radios[0].checked = true;
                radios[0].closest('.option-label').classList.add('selected');
            }
        }
        
        // Show/hide navigation buttons
        prevBtn.style.display = index > 0 ? '' : 'none';
        nextBtn.style.display = index < totalQuestions - 1 ? '' : 'none';
        submitBtn.style.display = index === totalQuestions - 1 ? '' : 'none';
        
        // Reset timer
        resetTimer();
    }
    
    // Timer functions
    function updateTimer() {
        if (timerElement) {
            timerElement.textContent = timeLeft;
            timerElement.style.color = timeLeft <= 3 ? '#dc3545' : '#4e73df';
        }
    }
    
    function resetTimer() {
        timeLeft = 10;
        updateTimer();
        clearInterval(timerInterval);
        timerInterval = setInterval(runTimer, 1000);
    }
    
    function runTimer() {
        timeLeft--;
        updateTimer();
        
        if (timeLeft <= 0) {
            clearInterval(timerInterval);
            if (!hasSubmitted) {
                hasSubmitted = true;
                if (timerElement) {
                    timerElement.innerHTML = '⏱️ Time\'s up!';
                }
                // Auto-move to next question or submit
                if (currentQuestion < totalQuestions - 1) {
                    setTimeout(() => {
                        loadQuestion(currentQuestion + 1);
                    }, 1000);
                } else {
                    submitQuiz();
                }
            }
        }
    }
    
    // Initialize
    let timerInterval;
    loadQuestion(0);
    resetTimer();
    
    // Event listeners for answer selection
    document.addEventListener('change', function(e) {
        if (e.target.type === 'radio') {
            // Store the answer
            const questionId = e.target.name.replace('q', '');
            userAnswers[questionId] = e.target.value;
            
            // Visual feedback
            document.querySelectorAll('.option-label').forEach(function(label) {
                label.classList.remove('selected');
            });
            if (e.target.closest('.option-label')) {
                e.target.closest('.option-label').classList.add('selected');
            }
        }
    });
    
    // Navigation buttons
    nextBtn.addEventListener('click', function() {
        if (currentQuestion < totalQuestions - 1) {
            loadQuestion(currentQuestion + 1);
        }
    });
    
    prevBtn.addEventListener('click', function() {
        if (currentQuestion > 0) {
            loadQuestion(currentQuestion - 1);
        }
    });
    
    // Submit button
    if (submitBtn) {
        submitBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (!hasSubmitted) {
                hasSubmitted = true;
                clearInterval(timerInterval);
                this.textContent = 'Submitting...';
                this.disabled = true;
                if (form) {
                    // Fill in all answers before submitting
                    Object.keys(userAnswers).forEach(questionId => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'q' + questionId;
                        input.value = userAnswers[questionId];
                        form.appendChild(input);
                    });
                    form.submit();
                }
            }
        });
    }
    
    // Timeout submit function
    function submitQuiz() {
        if (!hasSubmitted) {
            hasSubmitted = true;
            clearInterval(timerInterval);
            if (form) {
                // Fill in any answers before submitting
                Object.keys(userAnswers).forEach(questionId => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'q' + questionId;
                    input.value = userAnswers[questionId];
                    form.appendChild(input);
                });
                form.submit();
            }
        }
    }
    
    // Form submission handler
    if (form) {
        form.addEventListener('submit', function(e) {
            if (hasSubmitted) {
                e.preventDefault();
                return false;
            }
            hasSubmitted = true;
            clearInterval(timerInterval);
        });
    }
});
</script>
    <style>
        .quiz-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .quiz-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #eaeaea;
        }
        
        .quiz-header h1 {
            color: #2c3e50;
            margin-top: 10px;
        }
        
        .question-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: opacity 0.3s;
        }
        
        .question-number {
            font-weight: 600;
            color: #4e73df;
            font-size: 1.1rem;
        }
        
        .question-text {
            font-size: 1.3rem;
            margin: 20px 0;
            color: #2c3e50;
            line-height: 1.5;
        }
        
        .options {
            margin-top: 25px;
        }
        
        .option-item {
            margin-bottom: 12px;
        }
        
        .option-label {
            display: flex;
            align-items: center;
            padding: 15px;
            border: 2px solid #eaeaea;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .option-label:hover {
            border-color: #4e73df;
            background: #f8f9ff;
        }
        
        .option-label.selected {
            border-color: #4e73df;
            background: #e7f0ff;
        }
        
        .option-label input[type="radio"] {
            margin-right: 15px;
            cursor: pointer;
        }
        
        .option-text {
            font-size: 1.1rem;
            color: #34495e;
        }
        
        .button-group {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 30px;
        }
        
        .btn-custom {
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            font-size: 1.1rem;
        }
        
        .btn-primary-custom {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            color: white;
        }
        
        .btn-primary-custom:hover {
            background: linear-gradient(135deg, #3a56c4 0%, #1d3c8f 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }
        
        .btn-secondary-custom {
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary-custom:hover {
            background: #5a6268;
            color: white;
            transform: translateY(-2px);
        }
        
        .results-container {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .score-display {
            margin: 20px 0;
            padding: 30px;
            background: linear-gradient(135deg, #f8f9ff 0%, #e7f0ff 100%);
            border-radius: 10px;
        }
        
        .score-number {
            font-size: 3.5rem;
            font-weight: 700;
            color: #4e73df;
            line-height: 1;
        }
        
        .score-label {
            font-size: 1.3rem;
            color: #6c757d;
            margin-top: 10px;
        }
        
        .result-item {
            padding: 15px;
            margin: 15px 0;
            border-radius: 8px;
            text-align: left;
            border-left: 4px solid;
        }
        
        .result-item.correct {
            background: #d4edda;
            border-left-color: #28a745;
        }
        
        .result-item.incorrect {
            background: #f8d7da;
            border-left-color: #dc3545;
        }
        
        .result-question {
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
        }
        
        .result-answer {
            padding: 10px;
            background: rgba(255, 255, 255, 0.7);
            border-radius: 5px;
            margin-top: 5px;
        }
        
        .empty-state {
            text-align: center;
            padding: 50px 20px;
            color: #6c757d;
        }
        
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            color: #dee2e6;
        }
        
        .timer {
            font-weight: bold;
            font-size: 1.2rem;
        }
        #navButtons {
    display: flex;
    justify-content: space-between;
    gap: 15px;
}

#prevBtn, #nextBtn, #submitQuizBtn {
    flex: 1;
}

#submitQuizBtn {
    background: #28a745 !important;
}

#submitQuizBtn:hover:not(:disabled) {
    background: #218838 !important;
}
    </style>
</head>
<body>
    <div class="quiz-container">
        <!-- Header -->
        <div class="quiz-header">
            <a href="/lessons_project/views/front/lessonDisplay_direct.php"><i class="fas fa-arrow-left"></i> Back to Lessons</a>
            <h1><?php echo htmlspecialchars($lesson['title']); ?> - Quiz</h1>
        </div>

        <?php if ($quizAlreadyAttempted && $previousAttempt): ?>
            <!-- Quiz Already Completed -->
            <div class="results-container">
                <div class="score-display">
                    <div class="score-number"><?php echo $previousAttempt['score']; ?> / <?php echo $previousAttempt['totalQuestions']; ?></div>
                    <div class="score-label">You already completed this quiz with <?php echo $previousAttempt['scorePercentage']; ?>%</div>
                </div>
                <p style="text-align: center; color: #666; margin: 20px 0;">You can only take each quiz once. Your score has been recorded.</p>
                <div class="button-group">
                    <a href="/lessons_project/views/front/lessonDisplay_direct.php" class="btn-custom btn-secondary-custom">
                        <i class="fas fa-arrow-left"></i> Back to Lessons
                    </a>
                </div>
            </div>
        <?php elseif ($quizResults === null): ?>
            <!-- Quiz Form -->
            <?php if (empty($questions)): ?>
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>No quiz questions available for this lesson.</p>
                    <a href="/lessons_project/views/front/lessonDisplay_direct.php" class="btn btn-secondary mt-3">Back to Lessons</a>
                </div>
            <?php else: ?>
                <form method="POST" class="quiz-form" id="quizForm">
                    <?php if ($totalQuestions > 0): ?>
                        <!-- Current Question Display -->
                        <div class="question-card" id="currentQuestion">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                                <span class="question-number">
                                    Question <span id="currentQ">1</span> of <?php echo $totalQuestions; ?>
                                </span>
                                <div style="background: #e7f0ff; padding: 10px 15px; border-radius: 20px; font-weight: 600; color: #4e73df;">
                                    ⏱️ <span class="timer">10</span>s
                                </div>
                            </div>
                            
                            <!-- Question text will be inserted by JavaScript -->
                            <div id="questionText" class="question-text"></div>
                            
                            <!-- Options will be inserted by JavaScript -->
                            <div id="options" class="options"></div>
                        </div>

                        <!-- Hidden inputs for all questions -->
                        <?php foreach ($questions as $index => $question): ?>
                            <input type="hidden" id="qText<?php echo $index; ?>" value="<?php echo htmlspecialchars($question['questionText']); ?>">
                            <input type="hidden" id="qId<?php echo $index; ?>" value="<?php echo $question['questionId']; ?>">
                            <?php for ($i = 1; $i <= 4; $i++): ?>
                                <?php if (!empty($question['option' . $i])): ?>
                                    <input type="hidden" id="q<?php echo $index; ?>opt<?php echo $i; ?>" 
                                        value="<?php echo htmlspecialchars($question['option' . $i]); ?>">
                                <?php endif; ?>
                            <?php endfor; ?>
                        <?php endforeach; ?>

                        <!-- Navigation Buttons -->
                        <div class="button-group" id="navButtons">
                            <button type="button" id="prevBtn" class="btn-custom" style="background: #6c757d; color: white; display: none;">
                                <i class="fas fa-arrow-left"></i> Previous
                            </button>
                            <button type="button" id="nextBtn" class="btn-custom btn-primary-custom">
                                <i class="fas fa-arrow-right"></i> Next Question
                            </button>
                            <button type="submit" id="submitQuizBtn" class="btn-custom btn-primary-custom" style="display: none;">
                                <i class="fas fa-check-circle"></i> Submit Quiz
                            </button>
                        </div>
                    <?php endif; ?>
                </form>
            <?php endif; ?>

        <?php else: ?>
            <!-- Quiz Results -->
            <div class="results-container">
                <div class="score-display">
                    <div class="score-number"><?php echo $correctAnswers; ?> / <?php echo count($quizResults); ?></div>
                    <div class="score-label">You scored <?php echo $scorePercentage; ?>%</div>
                </div>

                <h3>📋 Detailed Results</h3>
                <?php foreach ($quizResults as $index => $result): ?>
                    <div class="result-item <?php echo $result['isCorrect'] ? 'correct' : 'incorrect'; ?>">
                        <div class="result-question">
                            Question <?php echo $index + 1; ?>: <?php echo htmlspecialchars($result['questionText']); ?>
                            <span style="float: right; font-weight: bold; color: <?php echo $result['isCorrect'] ? '#28a745' : '#dc3545'; ?>">
                                <?php echo $result['isCorrect'] ? '✅ 1 point' : '❌ 0 points'; ?>
                            </span>
                        </div>
                        <div class="result-answer">
                            <?php if ($result['isCorrect']): ?>
                                <span style="color: #28a745; margin-right: 8px;">✓</span>
                                Your answer: <strong><?php echo htmlspecialchars($result['userAnswer']); ?></strong>
                                <span style="color: #28a745; margin-left: 15px;">(Correct! +1 point)</span>
                            <?php else: ?>
                                <span style="color: #dc3545; margin-right: 8px;">✗</span>
                                Your answer: <strong><?php echo !empty($result['userAnswer']) ? htmlspecialchars($result['userAnswer']) : '(Not answered)'; ?></strong>
                                <br>
                                <span style="color: #28a745; margin-left: 25px;">
                                    Correct answer: <strong><?php echo htmlspecialchars($result['correctAnswer']); ?></strong>
                                    <span style="margin-left: 10px;">(0 points)</span>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="button-group">
                    <a href="/lessons_project/views/front/lessonDisplay_direct.php" class="btn-custom btn-secondary-custom">
                        <i class="fas fa-arrow-left"></i> Back to Lessons
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>