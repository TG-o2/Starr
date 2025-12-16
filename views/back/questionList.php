<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Questions - Admin</title>
    <link href="../front/kider-1.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="../front/kider-1.0.0/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="bg-white rounded shadow p-4 p-md-5">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div>
                                <h2 class="mb-1">Manage Questions</h2>
                                <div class="text-muted">Create and edit lesson quizzes</div>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="lessonList_direct.php" class="btn btn-outline-secondary btn-sm">Lessons</a>
                                <a href="questionForm_direct.php" class="btn btn-primary btn-sm">Add Question</a>
                            </div>
                        </div>

                        <?php if(!empty($questions)): ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th style="width:90px">ID</th>
                                            <th>Question</th>
                                            <th style="width:220px">Lesson</th>
                                            <th style="width:90px">Points</th>
                                            <th style="width:220px">Correct Answer</th>
                                            <th style="width:170px">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($questions as $q): ?>
                                            <tr>
                                                <td><?= htmlspecialchars((string)$q['questionId']) ?></td>
                                                <td><?= htmlspecialchars(substr($q['questionText'], 0, 80)) ?><?= (strlen($q['questionText']) > 80) ? '...' : '' ?></td>
                                                <td><?= htmlspecialchars($q['lessonTitle'] ?? 'Unknown') ?></td>
                                                <td><?= htmlspecialchars((string)($q['points'] ?? 1)) ?></td>
                                                <td><?= htmlspecialchars((string)($q['goodAnswer'] ?? '')) ?></td>
                                                <td>
                                                    <a href="questionForm_direct.php?questionId=<?= htmlspecialchars((string)$q['questionId']) ?>" class="btn btn-outline-primary btn-sm">Edit</a>
                                                    <a href="questionList_direct.php?delete=1&questionId=<?= htmlspecialchars((string)$q['questionId']) ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to delete this question?')">Delete</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <div class="text-muted mb-3">No questions found.</div>
                                <a href="questionForm_direct.php" class="btn btn-primary btn-sm">Add your first question</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
