<?php
// Process quiz submission
$quizResults = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $totalQuestions = count($questions);
    $correctAnswers = 0;
    $quizResults = [];

    foreach ($questions as $question) {
        $questionId = $question['questionId'];
        $userAnswer = $_POST['q' . $questionId] ?? '';
        $correctAnswer = $question['goodAnswer'];
        
        $isCorrect = (strtolower(trim($userAnswer)) === strtolower(trim($correctAnswer)));
        if ($isCorrect) {
            $correctAnswers++;
        }

        $quizResults[] = [
            'questionText' => $question['questionText'],
            'userAnswer' => $userAnswer,
            'correctAnswer' => $correctAnswer,
            'isCorrect' => $isCorrect,
            'option1' => $question['option1'],
            'option2' => $question['option2'],
            'option3' => $question['option3'] ?? null
        ];
    }

    $scorePercentage = ($totalQuestions > 0) ? round(($correctAnswers / $totalQuestions) * 100) : 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($lesson['title']); ?> - Quiz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f8f9fc 0%, #e9ecef 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .quiz-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .quiz-header {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .quiz-header a {
            color: white;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }

        .quiz-header a:hover {
            transform: translateX(-5px);
        }

        .quiz-header h1 {
            font-size: 2rem;
            margin: 0;
        }

        .question-card {
            background: white;
            padding: 25px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .question-number {
            display: inline-block;
            background: #4e73df;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: 600;
            margin-bottom: 15px;
            font-size: 0.9rem;
        }

        .question-text {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: #333;
        }

        .options {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .option-item {
            position: relative;
        }

        .option-item input[type="radio"] {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            cursor: pointer;
        }

        .option-label {
            display: block;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .option-item input[type="radio"]:checked + .option-label {
            border-color: #4e73df;
            background: #e7f0ff;
            font-weight: 600;
        }

        .option-label:hover {
            border-color: #4e73df;
            background: #f0f4ff;
        }

        /* Results Styles */
        .results-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .score-display {
            text-align: center;
            margin-bottom: 30px;
            padding: 30px;
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            color: white;
            border-radius: 10px;
        }

        .score-number {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .score-label {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .result-item {
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 8px;
            border-left: 4px solid #ccc;
        }

        .result-item.correct {
            background: #e8f5e9;
            border-left-color: #4caf50;
        }

        .result-item.incorrect {
            background: #ffebee;
            border-left-color: #f44336;
        }

        .result-question {
            font-weight: 600;
            margin-bottom: 10px;
            color: #333;
        }

        .result-answer {
            font-size: 0.95rem;
            margin: 8px 0;
        }

        .result-icon {
            font-weight: 600;
            margin-right: 8px;
        }

        .result-icon.correct::before {
            content: "✓ ";
            color: #4caf50;
        }

        .result-icon.incorrect::before {
            content: "✗ ";
            color: #f44336;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state i {
            font-size: 4rem;
            color: #ccc;
            margin-bottom: 20px;
        }

        .empty-state p {
            font-size: 1.1rem;
            color: #666;
        }

        .btn-custom {
            padding: 12px 24px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
            cursor: pointer;
        }

        .btn-primary-custom {
            background: #4e73df;
            color: white;
        }

        .btn-primary-custom:hover {
            background: #3d5cc3;
            transform: translateY(-2px);
        }

        .btn-secondary-custom {
            background: #858796;
            color: white;
        }

        .btn-secondary-custom:hover {
            background: #6c757d;
            transform: translateY(-2px);
        }

        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        @media (max-width: 768px) {
            .quiz-header h1 {
                font-size: 1.5rem;
            }

            .score-number {
                font-size: 2rem;
            }

            .button-group {
                flex-direction: column;
            }

            .btn-custom {
                width: 100%;
                justify-content: center;
            }
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

        <?php if ($quizResults === null): ?>
            <!-- Quiz Form -->
            <?php if (empty($questions)): ?>
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>No quiz questions available for this lesson.</p>
                    <a href="/lessons_project/views/front/lessonDisplay_direct.php" class="btn btn-secondary mt-3">Back to Lessons</a>
                </div>
            <?php else: ?>
                <form method="POST" class="quiz-form">
                    <?php foreach ($questions as $index => $question): ?>
                        <div class="question-card">
                            <span class="question-number">Question <?php echo $index + 1; ?> of <?php echo count($questions); ?></span>
                            <div class="question-text"><?php echo htmlspecialchars($question['questionText']); ?></div>
                            
                            <div class="options">
                                <?php for ($i = 1; $i <= 4; $i++): ?>
                                    <?php if (!empty($question['option' . $i])): ?>
                                        <div class="option-item">
                                            <input type="radio" id="q<?php echo $question['questionId']; ?>_opt<?php echo $i; ?>" name="q<?php echo $question['questionId']; ?>" value="<?php echo htmlspecialchars($question['option' . $i]); ?>" required>
                                            <label class="option-label" for="q<?php echo $question['questionId']; ?>_opt<?php echo $i; ?>">
                                                <?php echo htmlspecialchars($question['option' . $i]); ?>
                                            </label>
                                        </div>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="button-group">
                        <button type="submit" class="btn-custom btn-primary-custom">
                            <i class="fas fa-check-circle"></i> Submit Quiz
                        </button>
                        <a href="/lessons_project/views/front/lessonDisplay_direct.php" class="btn-custom btn-secondary-custom">
                            <i class="fas fa-times-circle"></i> Cancel
                        </a>
                    </div>
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
                        <div class="result-question">Question <?php echo $index + 1; ?>: <?php echo htmlspecialchars($result['questionText']); ?></div>
                        <div class="result-answer">
                            <span class="result-icon <?php echo $result['isCorrect'] ? 'correct' : 'incorrect'; ?>"></span>
                            Your answer: <strong><?php echo !empty($result['userAnswer']) ? htmlspecialchars($result['userAnswer']) : '(Not answered)'; ?></strong>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="button-group">
                    <a href="/lessons_project/views/front/lessonQuiz_direct.php?lessonId=<?php echo htmlspecialchars($lesson['lessonId']); ?>" class="btn-custom btn-primary-custom">
                        <i class="fas fa-redo-alt"></i> Retake Quiz
                    </a>
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