<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Lesson - Admin</title>
    <link rel="stylesheet" href="../../public/css/back.css">
</head>
<body>
    <div class="container">
        <nav class="navbar">
            <h1>🎓 Admin Panel</h1>
            <ul>
                <li><a href="lessonList_direct.php" class="nav-link">Lessons</a></li>
                <li><a href="questionList_direct.php" class="nav-link">Questions</a></li>
            </ul>
        </nav>

        <main class="content">
            <div class="form-section">
                <h2>Edit Lesson</h2>
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST" class="form-group" id="lessonForm">
                    <div class="form-field">
                        <label for="title">Title *</label>
                        <input type="text" id="title" name="title" value="<?= htmlspecialchars($lesson['title']) ?>" required>
                    </div>

                    <div class="form-field">
                        <label for="ageRange">Age Range *</label>
                        <input type="text" id="ageRange" name="ageRange" value="<?= htmlspecialchars($lesson['ageRange']) ?>" required>
                    </div>

                    <div class="form-field">
                        <label for="duration">Duration (minutes) *</label>
                        <input type="number" id="duration" name="duration" value="<?= htmlspecialchars($lesson['duration']) ?>" min="1" required>
                    </div>

                    <div class="form-field">
                        <label for="description">Description *</label>
                        <textarea id="description" name="description" rows="5" required><?= htmlspecialchars($lesson['description']) ?></textarea>
                    </div>

                    <div class="form-field">
                        <label for="image">Image URL</label>
                        <input type="url" id="image" name="image" value="<?= htmlspecialchars($lesson['image'] ?? '') ?>">
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Update Lesson</button>
                        <a href="lessonList_direct.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script src="../../public/js/back.js"></script>
</body>
</html>
