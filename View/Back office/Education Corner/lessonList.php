<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Lessons Management - Education Corner Admin</title>
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/9d17856d97.js" crossorigin="anonymous"></script>

</head>
<body id="page-top">
<div id="wrapper">
  <!-- Sidebar -->
  <ul class="navbar-nav bg-gradient-success sidebar sidebar-dark accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="Dashboard.php">
      <div class="sidebar-brand-icon rotate-n-15"><i class="fas fa-chalkboard-teacher"></i></div>
      <div class="sidebar-brand-text mx-3">Teacher Starr</div>
    </a>
    <hr class="sidebar-divider my-0">
    <li class="nav-item"><a class="nav-link" href="../Teacher Dashboard/Dashboard.php"><i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a></li>
    <hr class="sidebar-divider">
    <div class="sidebar-heading">Education Corner</div>
    <li class="nav-item active"><a class="nav-link" href="lessonList_direct.php"><i class="fas fa-book"></i><span>Lessons</span></a></li>
    <li class="nav-item"><a class="nav-link" href="questionList_direct.php"><i class="fas fa-question-circle"></i><span>Questions</span></a></li>
    <hr class="sidebar-divider d-none d-md-block">
    <div class="text-center d-none d-md-inline">
      <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
  </ul>

  <div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
      <!-- Topbar -->
      <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3"><i class="fa fa-bars"></i></button>
        <h5 class="m-0 font-weight-bold text-success">Education Corner Management</h5>
      </nav>

      <!-- Page Content -->
      <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
          <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-book"></i> Lessons Management</h1>
          <a href="lessonAdd_direct.php" class="btn btn-success btn-icon-split">
            <span class="icon text-white-50"><i class="fas fa-plus"></i></span>
            <span class="text">Add New Lesson</span>
          </a>
        </div>

        <div class="card shadow mb-4">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-success"><i class="fas fa-list"></i> All Lessons</h6>
          </div>
          <div class="card-body">
            <!-- Search and Filter Section -->
            <div class="row mb-4">
              <div class="col-md-4">
                <input type="text" class="form-control" id="searchInput" placeholder="Search by title or description...">
              </div>
              <div class="col-md-3">
                <select class="form-control" id="filterAgeRange">
                  <option value="">All Age Ranges</option>
                  <option value="3-5">3-5 years</option>
                  <option value="5-7">5-7 years</option>
                  <option value="7-9">7-9 years</option>
                  <option value="9-11">9-11 years</option>
                  <option value="11-13">11-13 years</option>
                </select>
              </div>
              <div class="col-md-3">
                <select class="form-control" id="filterDuration">
                  <option value="">All Durations</option>
                  <option value="0-15">0-15 min</option>
                  <option value="15-30">15-30 min</option>
                  <option value="30-45">30-45 min</option>
                  <option value="45-60">45-60 min</option>
                  <option value="60">60+ min</option>
                </select>
              </div>
              <div class="col-md-2">
                <button class="btn btn-secondary btn-block" onclick="resetFilters()">Reset Filters</button>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table table-bordered table-hover" id="lessonsTable" width="100%" cellspacing="0">
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
                <tbody id="lessonsTableBody">
                  <?php if (!empty($lessons)): ?>
                    <?php foreach ($lessons as $lesson): ?>
                      <tr class="lesson-row" data-title="<?= strtolower(htmlspecialchars($lesson['title'])) ?>" data-description="<?= strtolower(htmlspecialchars($lesson['description'])) ?>" data-age="<?= htmlspecialchars($lesson['ageRange']) ?>" data-duration="<?= (int)$lesson['duration'] ?>">
                        <td><?= htmlspecialchars($lesson['lessonId']) ?></td>
                        <td><strong><?= htmlspecialchars($lesson['title']) ?></strong></td>
                        <td><span class="badge badge-info"><?= htmlspecialchars($lesson['ageRange']) ?></span></td>
                        <td><?= htmlspecialchars($lesson['duration']) ?> min</td>
                        <td><?= htmlspecialchars(substr($lesson['description'], 0, 80)) ?>...</td>
                        <td class="text-center">
                          <a href="lessonEdit_direct.php?lessonId=<?= $lesson['lessonId'] ?>" class="btn btn-info btn-circle btn-sm" title="Edit">
                            <i class="fas fa-edit"></i>
                          </a>
                          <a href="lessonList_direct.php?delete=1&lessonId=<?= $lesson['lessonId'] ?>" class="btn btn-danger btn-circle btn-sm" onclick="return confirm('Are you sure you want to delete this lesson and all its questions?');" title="Delete">
                            <i class="fas fa-trash"></i>
                          </a>
                          <a href="../../Front office/Education Corner/lessonDetails_direct.php?lessonId=<?= $lesson['lessonId'] ?>" class="btn btn-success btn-circle btn-sm" title="View Details" target="_blank">
                            <i class="fas fa-eye"></i>
                          </a>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="6" class="text-center">
                        <div class="py-5">
                          <i class="fas fa-inbox fa-4x text-gray-300 mb-3"></i>
                          <p class="text-gray-500 mb-3 lead">No lessons found.</p>
                          <a href="lessonAdd_direct.php" class="btn btn-primary"><i class="fas fa-plus"></i> Create Your First Lesson</a>
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
    </div>

    <!-- Footer -->
    <footer class="sticky-footer bg-white">
      <div class="container my-auto">
        <div class="copyright text-center my-auto"><span>Copyright &copy; Starr Education Corner 2025</span></div>
      </div>
    </footer>
  </div>
</div>

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top"><i class="fas fa-angle-up"></i></a>

<!-- Bootstrap core JavaScript-->
<script src="../assets/vendor/jquery/jquery.min.js"></script>
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="../assets/js/sb-admin-2.min.js"></script>

<script>
function filterLessons() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const ageFilter = document.getElementById('filterAgeRange').value;
    const durationFilter = document.getElementById('filterDuration').value;
    const rows = document.querySelectorAll('.lesson-row');
    
    rows.forEach(row => {
        let show = true;
        
        // Search filter
        if (searchTerm) {
            const title = row.getAttribute('data-title');
            const description = row.getAttribute('data-description');
            show = title.includes(searchTerm) || description.includes(searchTerm);
        }
        
        // Age range filter
        if (show && ageFilter) {
            const age = row.getAttribute('data-age');
            show = age === ageFilter;
        }
        
        // Duration filter
        if (show && durationFilter) {
            const duration = parseInt(row.getAttribute('data-duration'));
            if (durationFilter === '0-15') show = duration <= 15;
            else if (durationFilter === '15-30') show = duration > 15 && duration <= 30;
            else if (durationFilter === '30-45') show = duration > 30 && duration <= 45;
            else if (durationFilter === '45-60') show = duration > 45 && duration <= 60;
            else if (durationFilter === '60') show = duration > 60;
        }
        
        row.style.display = show ? '' : 'none';
    });
}

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('filterAgeRange').value = '';
    document.getElementById('filterDuration').value = '';
    filterLessons();
}

// Add event listeners
document.getElementById('searchInput').addEventListener('keyup', filterLessons);
document.getElementById('filterAgeRange').addEventListener('change', filterLessons);
document.getElementById('filterDuration').addEventListener('change', filterLessons);
</script>
</body>
</html>
