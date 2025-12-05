<?php include('../../includes/header.php'); ?>
<?php include('../../includes/sidebar.php'); ?>
<?php include('../../includes/navbar.php'); ?>

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Lessons Management</h1>
        <a href="/lessons_project/views/back/lessonAdd_direct.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Add New Lesson
        </a>
    </div>

    <!-- Lessons Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Lessons</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Age Range</th>
                            <th>Duration (min)</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($lessons)): ?>
                            <?php foreach ($lessons as $lesson): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($lesson['lessonId']); ?></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($lesson['title']); ?></strong>
                                    </td>
                                    <td>
                                        <span class="badge badge-info"><?php echo htmlspecialchars($lesson['ageRange']); ?></span>
                                    </td>
                                    <td><?php echo htmlspecialchars($lesson['duration']); ?></td>
                                    <td><?php echo htmlspecialchars(substr($lesson['description'], 0, 60)) . '...'; ?></td>
                                    <td>
                                        <a href="/lessons_project/views/back/lessonEdit_direct.php?lessonId=<?php echo $lesson['lessonId']; ?>" class="btn btn-info btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="/lessons_project/views/back/lessonList_direct.php?delete=1&lessonId=<?php echo $lesson['lessonId']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this lesson?');" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        <a href="/lessons_project/views/front/lessonDetails_direct.php?lessonId=<?php echo $lesson['lessonId']; ?>" class="btn btn-success btn-sm" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">
                                    <div class="py-4">
                                        <i class="fas fa-inbox fa-3x text-gray-300 mb-3"></i>
                                        <p class="text-gray-500 mb-0">No lessons found. <a href="/lessons_project/views/back/lessonAdd_direct.php">Create one now!</a></p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include('../../includes/footer.php'); ?>