<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Questions Management - Education Corner Admin</title>
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/9d17856d97.js" crossorigin="anonymous"></script>

</head>
<body id="page-top">
<div id="wrapper">
  <!-- Sidebar -->
  <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../Admin Dashboard/Dashboard.html">
      <div class="sidebar-brand-icon rotate-n-15"><i class="fas fa-laugh-wink"></i></div>
      <div class="sidebar-brand-text mx-3">Teacher Starr<sup>*</sup></div>
    </a>
    <hr class="sidebar-divider my-0">
    <li class="nav-item"><a class="nav-link" href="../Admin Dashboard/Dashboard.html"><i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a></li>
    <hr class="sidebar-divider">
    <div class="sidebar-heading">Education Corner</div>
    <li class="nav-item"><a class="nav-link" href="lessonList_direct.php"><i class="fas fa-book"></i><span>Lessons</span></a></li>
    <li class="nav-item active"><a class="nav-link" href="questionList_direct.php"><i class="fas fa-question-circle"></i><span>Questions</span></a></li>
    <hr class="sidebar-divider">
    <div class="sidebar-heading">Other Modules</div>
    <li class="nav-item"><a class="nav-link" href="../Admin Moderation/Review-list.php"><i class="fa-solid fa-flag"></i><span>Reports</span></a></li>
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
        <h5 class="m-0 font-weight-bold text-primary">Education Corner Management</h5>
      </nav>

      <!-- Page Content -->
      <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
          <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-question-circle"></i> Questions Management</h1>
          <a href="questionForm_direct.php" class="btn btn-primary btn-icon-split">
            <span class="icon text-white-50"><i class="fas fa-plus"></i></span>
            <span class="text">Add New Question</span>
          </a>
        </div>

        <div class="card shadow mb-4">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-list"></i> All Questions</h6>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Question</th>
                    <th>Lesson</th>
                    <th>Correct Answer</th>
                    <th>Points</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if(!empty($questions)): ?>
                    <?php foreach($questions as $q): ?>
                      <tr>
                        <td><?= $q['questionId'] ?></td>
                        <td><?= htmlspecialchars(substr($q['questionText'], 0, 70)) ?><?= strlen($q['questionText']) > 70 ? '...' : '' ?></td>
                        <td><span class="badge badge-primary"><?= htmlspecialchars($q['lessonTitle'] ?? 'Unknown') ?></span></td>
                        <td><span class="badge badge-success"><?= htmlspecialchars($q['goodAnswer']) ?></span></td>
                        <td class="text-center"><span class="badge badge-warning"><i class="fas fa-star"></i> <?= htmlspecialchars($q['points'] ?? 5) ?> pts</span></td>
                        <td class="text-center">
                          <a href="questionForm_direct.php?questionId=<?= $q['questionId'] ?>" class="btn btn-info btn-circle btn-sm" title="Edit">
                            <i class="fas fa-edit"></i>
                          </a>
                          <a href="questionList_direct.php?delete=1&questionId=<?= $q['questionId'] ?>" class="btn btn-danger btn-circle btn-sm" onclick="return confirm('Are you sure you want to delete this question?');" title="Delete">
                            <i class="fas fa-trash"></i>
                          </a>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="6" class="text-center">
                        <div class="py-5">
                          <i class="fas fa-inbox fa-4x text-gray-300 mb-3"></i>
                          <p class="text-gray-500 mb-3 lead">No questions found.</p>
                          <a href="questionForm_direct.php" class="btn btn-primary"><i class="fas fa-plus"></i> Create Your First Question</a>
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
</body>
</html>
