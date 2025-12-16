<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lessons - Admin</title>
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
                                <h2 class="mb-1">Manage Lessons</h2>
                                <div class="text-muted">Create and manage lesson content</div>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="questionList_direct.php" class="btn btn-outline-primary btn-sm">Questions</a>
                                <a href="lessonAdd_direct.php" class="btn btn-primary btn-sm">Add Lesson</a>
                            </div>
                        </div>

                        <?php if (!empty($lessons)): ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th style="width:70px">ID</th>
                                            <th>Title</th>
                                            <th style="width:120px">Age Range</th>
                                            <th style="width:100px">Avg Age</th>
                                            <th style="width:100px">Duration</th>
                                            <th style="width:200px">Description</th>
                                            <th style="width:180px">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($lessons as $lesson): ?>
                                            <tr>
                                                <td><?= htmlspecialchars((string)$lesson['lessonId']) ?></td>
                                                <td>
                                                    <strong><?= htmlspecialchars($lesson['title']) ?></strong>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info text-white"><?= htmlspecialchars($lesson['ageRange'] ?? '') ?></span>
                                                </td>
                                                <td><?= htmlspecialchars((string)($lesson['average_age'] ?? '')) ?></td>
                                                <td><?= htmlspecialchars($lesson['duration']) ?> min</td>
                                                <td><?= htmlspecialchars(substr($lesson['description'], 0, 80)) ?><?= (strlen($lesson['description']) > 80) ? '...' : '' ?></td>
                                                <td>
                                                    <div class="d-flex gap-1 flex-wrap">
                                                        <a href="lessonEdit_direct.php?lessonId=<?= htmlspecialchars((string)$lesson['lessonId']) ?>" class="btn btn-outline-primary btn-sm" title="Edit">Edit</a>
                                                        <a href="questionForm_direct.php?lessonId=<?= htmlspecialchars((string)$lesson['lessonId']) ?>" class="btn btn-outline-success btn-sm" title="Quiz">Quiz</a>
                                                        <a href="lessonList_direct.php?delete=1&lessonId=<?= htmlspecialchars((string)$lesson['lessonId']) ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to delete this lesson?')" title="Delete">Delete</a>
                                                        <a href="../front/lessonDetails_direct.php?lessonId=<?= htmlspecialchars((string)$lesson['lessonId']) ?>" class="btn btn-outline-secondary btn-sm" title="View">View</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <div class="text-muted mb-3">No lessons found.</div>
                                <a href="lessonAdd_direct.php" class="btn btn-primary btn-sm">Create your first lesson</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
