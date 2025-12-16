<?php
// Check if we should show results or the quiz form
$showResults = $showResults ?? false;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Quiz - <?= htmlspecialchars($viewLesson['title'] ?? 'Lesson Quiz') ?></title>
    <script>
    // Timer functionality
    function startTimer(duration, display) {
        var timer = duration, minutes, seconds;
        var interval = setInterval(function () {
            minutes = parseInt(timer / 60, 10);
            seconds = parseInt(timer % 60, 10);

            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            display.textContent = minutes + ":" + seconds;

            if (--timer < 0) {
                clearInterval(interval);
                document.getElementById('quiz-form').submit();
            }
        }, 1000);
    }

    window.onload = function () {
        var timeLimit = <?= ($viewLesson['quiz_time_limit'] ?? 0) * 60 || 3600 ?>; // Default to 60 minutes if not set
        var display = document.querySelector('#time');
        if (display) {
            startTimer(timeLimit, display);
        }
    };
    </script>
    <style>
        .question { margin-bottom: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .options { margin: 10px 0 0 20px; }
        .option { margin: 5px 0; }
        .correct { color: green; }
        .incorrect { color: red; }
        .quiz-header { margin-bottom: 20px; }
        .timer-container {
            font-size: 1.2em;
            font-weight: bold;
            color: #2c3e50;
            background: #ecf0f1;
            padding: 10px 15px;
            border-radius: 5px;
            display: inline-block;
            margin-top: 10px;
        }
        #time {
            color: #e74c3c;
            font-family: monospace;
            font-size: 1.3em;
        }
        .result-summary { 
            padding: 15px; 
            margin-bottom: 20px; 
            border-radius: 5px; 
            font-size: 1.2em; 
            font-weight: bold;
        }
        .score {
            font-size: 1.5em;
            margin-bottom: 20px;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .back-link:hover {
            background: #0056b3;
        }
        .submit-btn {
            padding: 10px 20px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.1em;
        }
        .submit-btn:hover {
            background: #218838;
        }
    </style>
</head>
<body>
    <div class="quiz-header">
        <h1>Quiz: <?= htmlspecialchars($viewLesson['title'] ?? '') ?></h1>
        <?php if (!empty($viewLesson['quiz_time_limit'])): ?>
            <div class="timer-container">
                <span>Time Remaining: </span>
                <span id="time"><?= sprintf('%02d:00', $viewLesson['quiz_time_limit']) ?></span>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($showResults): ?>
        <div class="result-summary">
            <div class="score">
                You scored <?= $viewScore ?> out of <?= $viewTotal ?> 
                (<?= round(($viewScore / $viewTotal) * 100) ?>%)
            </div>
            
            <h2>Quiz Results:</h2>
            <?php foreach ($viewResults as $i => $result): 
                $question = $result['question'];
                $isCorrect = $result['is_correct'];
            ?>
                <div class="question <?= $isCorrect ? 'correct' : 'incorrect' ?>">
                    <p><strong>Question <?= $i + 1 ?>:</strong> <?= htmlspecialchars($question['questionText']) ?></p>
                    <p>Your answer: <?= htmlspecialchars(is_array($result['selected']) ? implode(', ', $result['selected']) : (string)$result['selected']) ?></p>
                    <p>Correct answer: <?= htmlspecialchars(isset($question['correctOptions']) && is_array($question['correctOptions']) && !empty($question['correctOptions']) ? implode(', ', $question['correctOptions']) : (string)($question['goodAnswer'] ?? '')) ?></p>
                    <p><strong><?= $isCorrect ? '✅ Correct' : '❌ Incorrect' ?></strong></p>
                </div>
            <?php endforeach; ?>

            <a href="/lessons_project/views/front/lessonDisplay_direct.php" class="back-link">Back to Lessons</a>
        </div>

    <?php else: ?>
        <form id="quiz-form" method="post" action="">
            <input type="hidden" name="start_time" value="<?= time() ?>">
            <?php foreach ($viewQuestions as $index => $question): ?>
                <div class="question">
                    <p><strong>Question <?= $index + 1 ?>:</strong> <?= htmlspecialchars($question['questionText']) ?></p>
                    <div class="options">
                        <?php 
                        $options = [];
                        if (isset($question['options']) && is_array($question['options']) && !empty($question['options'])) {
                            $options = $question['options'];
                        } else {
                            $options = [
                                $question['option1'],
                                $question['option2'],
                                !empty($question['option3']) ? $question['option3'] : null,
                                !empty($question['option4']) ? $question['option4'] : null
                            ];
                        }
                        $options = array_values(array_filter($options)); // Remove null/empty options
                        
                        $isMulti = isset($question['correctOptions']) && is_array($question['correctOptions']) && count($question['correctOptions']) > 1;

                        foreach ($options as $i => $option): 
                            if (empty($option)) continue;
                        ?>
                            <div class="option">
                                <label>
                                    <input type="<?= $isMulti ? 'checkbox' : 'radio' ?>" 
                                           name="q<?= $question['questionId'] ?><?= $isMulti ? '[]' : '' ?>" 
                                           value="<?= htmlspecialchars($option) ?>"
                                           <?= $isMulti ? '' : 'required' ?>>
                                    <?= htmlspecialchars($option) ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <button type="submit" name="submitQuiz" class="submit-btn">Submit Quiz</button>
        </form>
    <?php endif; ?>
</body>
</html>
