<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Edit Lesson - Education Corner Admin</title>
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
    <li class="nav-item active"><a class="nav-link" href="lessonList_direct.php"><i class="fas fa-book"></i><span>Lessons</span></a></li>
    <li class="nav-item"><a class="nav-link" href="questionList_direct.php"><i class="fas fa-question-circle"></i><span>Questions</span></a></li>
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
          <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-edit"></i> Edit Lesson</h1>
          <a href="lessonList_direct.php" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Back to List</a>
        </div>

        <?php if (isset($error)): ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error) ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        <?php endif; ?>

        <div class="card shadow mb-4">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-pencil-alt"></i> Update Lesson Information</h6>
          </div>
          <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
              <div class="form-group">
                <label for="title">Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($lesson['title']) ?>" required>
              </div>

              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="ageRange">Age Range <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="ageRange" name="ageRange" value="<?= htmlspecialchars($lesson['ageRange']) ?>" required>
                </div>
                <div class="form-group col-md-6">
                  <label for="duration">Duration (minutes) <span class="text-danger">*</span></label>
                  <input type="number" class="form-control" id="duration" name="duration" value="<?= htmlspecialchars($lesson['duration']) ?>" min="1" required>
                </div>
              </div>

              <div class="form-group">
                <label for="description">Description <span class="text-danger">*</span></label>
                <textarea class="form-control" id="description" name="description" rows="5" required><?= htmlspecialchars($lesson['description']) ?></textarea>
              </div>

              <div class="form-group">
                <label for="image">Lesson Image</label>
                <?php if (!empty($lesson['image'])): ?>
                  <div class="mb-2">
                    <img src="../../Front office/<?= htmlspecialchars($lesson['image']) ?>" alt="Current image" style="max-width: 200px; height: auto; border-radius: 8px;">
                    <p class="text-muted small mt-1">Current image</p>
                  </div>
                <?php endif; ?>
                <input type="file" class="form-control-file" id="image" name="image" accept="image/*">
                <small class="form-text text-muted">Optional: Upload a new image to replace the current one (JPG, PNG, GIF).</small>
              </div>

              <hr>
              <div class="form-group mb-0">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Lesson</button>
                <a href="lessonList_direct.php" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
              </div>
            </form>
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
