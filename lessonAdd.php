<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Lesson - Admin</title>
    <link rel="stylesheet" href="../../public/css/back.css">
</head>
<body>
    <div class="container">
        <nav class="navbar">
            <h1>🎓 Admin Panel</h1>
            <ul>
                <li><a href="/lessons_project/views/back/lessonList_direct.php" class="nav-link">Lessons</a></li>
                <li><a href="/lessons_project/views/back/questionList_direct.php" class="nav-link">Questions</a></li>
            </ul>
        </nav>

        <main class="content">
            <div class="form-section">
                <h2>Add New Lesson</h2>
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST" action="/lessons_project/views/back/lessonAdd_direct.php" class="form-group" id="lessonForm">
                    <div class="form-field">
                        <label for="title">Title *</label>
                        <input type="text" id="title" name="title" required placeholder="Enter lesson title" value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>">
                    </div>

                    <div class="form-field">
                        <label for="ageRange">Age Range *</label>
                        <input type="text" id="ageRange" name="ageRange" placeholder="e.g., 3-5 years" required value="<?php echo htmlspecialchars($_POST['ageRange'] ?? ''); ?>">
                    </div>

                    <div class="form-field">
                        <label for="duration">Duration (minutes) *</label>
                        <input type="number" id="duration" name="duration" min="1" required placeholder="Enter duration" value="<?php echo htmlspecialchars($_POST['duration'] ?? ''); ?>">
                    </div>

                    <div class="form-field">
                        <label for="description">Description *</label>
                        <textarea id="description" name="description" rows="5" required placeholder="Enter lesson description"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                    </div>

                    <div class="form-field">
                        <label for="image">Image URL</label>
                        <input type="url" id="image" name="image" placeholder="https://example.com/image.jpg" value="<?php echo htmlspecialchars($_POST['image'] ?? ''); ?>">
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Add Lesson</button>
                        <a href="/lessons_project/views/back/lessonList_direct.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script src="../../public/js/back.js"></script>
</body>
</html>
