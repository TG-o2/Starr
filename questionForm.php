<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($question) ? 'Edit Question' : 'Add Question' ?> - Admin</title>
    <link rel="stylesheet" href="../../public/css/back.css">
</head>
<body>
    <div class="container">
        <nav class="navbar">
            <h1>🎓 Admin Panel</h1>
            <ul>
                <li><a href="lessonList_direct.php" class="nav-link">Lessons</a></li>
                <li><a href="questionList_direct.php" class="nav-link active">Questions</a></li>
            </ul>
        </nav>

        <main class="content">
            <div class="form-section">
                <h2><?= isset($question) ? 'Edit Question' : 'Add New Question' ?></h2>
                
                <?php if(isset($error)): ?>
                    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="post" class="form-group" id="questionForm">
                    <div class="form-field">
                        <label for="lessonId">Lesson *</label>
                        <select id="lessonId" name="lessonId" required>
                            <option value="">-- Select Lesson --</option>
                            <?php foreach($lessons as $l): ?>
                                <option value="<?= $l['lessonId'] ?>" <?= isset($question) && $question['lessonId'] == $l['lessonId'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($l['title']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-field">
                        <label for="questionText">Question Text *</label>
                        <textarea id="questionText" name="questionText" rows="3" required placeholder="Enter the question"><?= htmlspecialchars($question['questionText'] ?? '') ?></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-field">
                            <label for="option1">Option 1 *</label>
                            <input type="text" id="option1" name="option1" value="<?= htmlspecialchars($question['option1'] ?? '') ?>" required placeholder="First option">
                        </div>

                        <div class="form-field">
                            <label for="option2">Option 2 *</label>
                            <input type="text" id="option2" name="option2" value="<?= htmlspecialchars($question['option2'] ?? '') ?>" required placeholder="Second option">
                        </div>
                    </div>

                    <div class="form-field">
                        <label for="option3">Option 3 (optional)</label>
                        <input type="text" id="option3" name="option3" value="<?= htmlspecialchars($question['option3'] ?? '') ?>" placeholder="Third option (leave blank if not needed)">
                    </div>

                    <div class="form-field">
                        <label for="goodAnswer">Correct Answer *</label>
                        <p class="hint">Must match one of the options exactly</p>
                        <input type="text" id="goodAnswer" name="goodAnswer" value="<?= htmlspecialchars($question['goodAnswer'] ?? '') ?>" required placeholder="Enter the correct answer">
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary"><?= isset($question) ? 'Update Question' : 'Add Question' ?></button>
                        <a href="questionList_direct.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script src="../../public/js/back.js"></script>
</body>
</html>
