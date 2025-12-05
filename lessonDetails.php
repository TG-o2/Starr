<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($lesson['title']); ?> - Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f8f9fc 0%, #e9ecef 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .lesson-details-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }

        .lesson-header {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .lesson-meta {
            display: flex;
            gap: 20px;
            margin: 15px 0;
            flex-wrap: wrap;
        }

        .badge-custom { padding: 8px 15px; border-radius: 20px; font-weight: bold; display: inline-block; }
        .badge-age { background: #e3f2fd; color: #1976d2; }
        .badge-duration { background: #fff3e0; color: #f57c00; }

        .lesson-content, .questions-section { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 30px; }
        .question-card { border: 1px solid #e0e0e0; border-radius: 8px; padding: 20px; margin-bottom: 20px; }
        .correct-answer { color: #4caf50; font-weight: bold; }
        .empty-state { text-align: center; padding: 40px; color: #666; }

        .action-buttons { display:flex; gap:10px; flex-wrap:wrap; margin-top:20px; }
        .btn-custom { padding:12px 24px; border-radius:6px; text-decoration:none; font-weight:600; display:inline-flex; align-items:center; gap:8px; }
        .btn-primary-custom { background:#4e73df; color:white; }
        .btn-secondary-custom { background:#858796; color:white; }

        @media (max-width:768px){ .lesson-details-container{padding:10px;} .action-buttons{flex-direction:column;} .btn-custom{width:100%; justify-content:center;} }
    </style>
</head>
<body>
    <div class="lesson-details-container">
        <!-- Header -->
        <div class="lesson-header">
            <a href="/lessons_project/views/front/lessonDisplay_direct.php" class="btn btn-secondary mb-3">← Back to Lessons</a>
            <h1><?php echo htmlspecialchars($lesson['title']); ?></h1>

            <div class="lesson-meta">
                <span class="badge-custom badge-age"><i class="fas fa-child"></i> Age: <?php echo htmlspecialchars($lesson['ageRange']); ?></span>
                <span class="badge-custom badge-duration"><i class="fas fa-clock"></i> Duration: <?php echo htmlspecialchars($lesson['duration']); ?> minutes</span>
            </div>

            <?php if (!empty($lesson['image'])): ?>
                <div class="lesson-image mt-3"><img src="<?php echo htmlspecialchars($lesson['image']); ?>" alt="<?php echo htmlspecialchars($lesson['title']); ?>" style="max-width:100%; height:auto; border-radius:8px;"></div>
            <?php endif; ?>
        </div>

        <!-- Lesson Description -->
        <div class="lesson-content">
            <h2>📖 Lesson Description</h2>
            <p style="white-space:pre-line; line-height:1.6"><?php echo htmlspecialchars($lesson['description']); ?></p>
        </div>

        <!-- Quiz Questions Preview -->
        <div class="questions-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>❓ Quiz Questions</h2>
                <?php if (!empty($questions)): ?>
                    <span class="badge badge-primary"><?php echo count($questions); ?> Questions</span>
                <?php endif; ?>
            </div>

            <?php if (!empty($questions)): ?>
                <?php foreach ($questions as $index => $question): ?>
                    <div class="question-card">
                        <h4>Question <?php echo $index + 1; ?></h4>
                        <p><strong><?php echo htmlspecialchars($question['questionText']); ?></strong></p>

                        <?php for ($i=1; $i<=4; $i++): ?>
                            <?php if (!empty($question['option'.$i])): ?>
                                <div class="option-item">
                                    <?php echo htmlspecialchars($question['option'.$i]); ?>
                                </div>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state"><i class="fas fa-inbox fa-3x mb-3"></i><p>No questions available for this lesson yet.</p></div>
            <?php endif; ?>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons mt-4">
            <?php if (!empty($questions)): ?>
                <a href="/lessons_project/views/front/lessonQuiz_direct.php?lessonId=<?php echo htmlspecialchars($lesson['lessonId']); ?>" class="btn-custom btn-primary-custom"><i class="fas fa-play-circle"></i> Start Quiz</a>
            <?php endif; ?>
            <a href="/lessons_project/views/front/lessonDisplay_direct.php" class="btn-custom btn-secondary-custom"><i class="fas fa-arrow-left"></i> Back to All Lessons</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>