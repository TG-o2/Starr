<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Questions - Admin</title>
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
            <div class="section-header">
                <h2>Manage Questions</h2>
                <a href="questionForm_direct.php" class="btn btn-primary">+ Add New Question</a>
            </div>

            <?php if(!empty($questions)): ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Question</th>
                            <th>Lesson</th>
                            <th>Correct Answer</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($questions as $q): ?>
                            <tr>
                                <td><?= $q['questionId'] ?></td>
                                <td class="question-cell"><?= htmlspecialchars(substr($q['questionText'], 0, 50)) ?>...</td>
                                <td><?= htmlspecialchars($q['lessonTitle'] ?? 'Unknown') ?></td>
                                <td><?= htmlspecialchars($q['goodAnswer']) ?></td>
                                <td class="action-buttons">
                                    <a href="questionForm_direct.php?questionId=<?= $q['questionId'] ?>" class="btn btn-edit">Edit</a>
                                    <a href="questionList_direct.php?delete=1&questionId=<?= $q['questionId'] ?>" class="btn btn-delete" onclick="return confirmDelete('Are you sure you want to delete this question?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <p>No questions found. <a href="index.php?action=questionAdd"></a></p>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <script src="../../public/js/back.js"></script>
</body>
</html>
