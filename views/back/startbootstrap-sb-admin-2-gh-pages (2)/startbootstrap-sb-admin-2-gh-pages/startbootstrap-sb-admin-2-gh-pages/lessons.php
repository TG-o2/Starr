<?php include('includes/header.php'); ?>
<?php include('includes/sidebar.php'); ?>
<?php include('includes/navbar.php'); ?>

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Lessons Management</h1>
        <a href="index.php?action=lessonAdd" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Add New Lesson
        </a>
    </div>

    <!-- Search & Filter Section -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Search & Filter</h6>
        </div>
        <div class="card-body">
            <form method="GET" class="form-inline">
                <input type="hidden" name="action" value="lessonList">
                <div class="form-group mr-3 mb-2">
                    <label for="searchInput" class="mr-2">Search:</label>
                    <input type="text" class="form-control form-control-sm" id="searchInput" name="search" placeholder="Search lessons...">
                </div>
                <div class="form-group mr-3 mb-2">
                    <label for="ageRangeFilter" class="mr-2">Age Range:</label>
                    <select class="form-control form-control-sm" id="ageRangeFilter" name="ageRange">
                        <option value="">All Age Ranges</option>
                        <option value="3-5">3-5 years</option>
                        <option value="6-8">6-8 years</option>
                        <option value="9-12">9-12 years</option>
                        <option value="13+">13+ years</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-sm mb-2">
                    <i class="fas fa-search"></i> Filter
                </button>
            </form>
        </div>
    </div>

    <!-- Lessons Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">All Lessons</h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                    <div class="dropdown-header">Export Options:</div>
                    <a class="dropdown-item" href="#">Export as CSV</a>
                    <a class="dropdown-item" href="#">Export as PDF</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Refresh Data</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Age Range</th>
                            <th>Duration (min)</th>
                            <th>Description</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($lessons)): ?>
                            <?php foreach ($lessons as $lesson): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($lesson['id'] ?? $lesson['lessonId']); ?></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($lesson['title']); ?></strong>
                                        <?php if (!empty($lesson['image'])): ?>
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-image"></i> Image: <?php echo htmlspecialchars(basename($lesson['image'])); ?>
                                            </small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge badge-info"><?php echo htmlspecialchars($lesson['ageRange']); ?></span>
                                    </td>
                                    <td><?php echo htmlspecialchars($lesson['duration']); ?></td>
                                    <td><?php echo htmlspecialchars(substr($lesson['description'], 0, 60)) . '...'; ?></td>
                                    <td>
                                        <small class="text-muted">
                                            <?php echo !empty($lesson['created_at']) ? date('M d, Y', strtotime($lesson['created_at'])) : 'N/A'; ?>
                                        </small>
                                    </td>
                                    <td>
                                        <a href="index.php?action=lessonEdit&lessonId=<?php echo $lesson['id'] ?? $lesson['lessonId']; ?>" class="btn btn-info btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="index.php?action=lessonDelete&lessonId=<?php echo $lesson['id'] ?? $lesson['lessonId']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this lesson?');" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        <a href="index.php?action=lessonDetails&lessonId=<?php echo $lesson['id'] ?? $lesson['lessonId']; ?>" class="btn btn-success btn-sm" title="View Questions">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">
                                    <div class="py-4">
                                        <i class="fas fa-inbox fa-3x text-gray-300 mb-3"></i>
                                        <p class="text-gray-500 mb-0">No lessons found. <a href="index.php?action=lessonAdd">Create one now!</a></p>
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

<?php include('includes/footer.php'); ?>

<script>
    // Optional: Add DataTables initialization for advanced features
    $(document).ready(function() {
        // Uncomment below if you have DataTables plugin included
        // $('#dataTable').DataTable({
        //     "columnDefs": [
        //         { "orderable": false, "targets": 6 }
        //     ]
        // });
    });
</script>