<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Add Lesson - Education Corner Admin</title>
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.5.0/nouislider.min.css">
    <script src="https://kit.fontawesome.com/9d17856d97.js" crossorigin="anonymous"></script>
    <style>
        .lesson-cover{position:relative;width:100%;height:240px;border-radius:12px;overflow:hidden;background:linear-gradient(135deg, rgba(15,23,42,.06), rgba(15,23,42,.02));border:2px dashed rgba(15,23,42,.18);cursor:pointer;transition:all .3s ease}
        .lesson-cover.has-image{border:0}
        .lesson-cover-img{width:100%;height:100%;object-fit:cover;display:block}
        .lesson-cover-overlay{position:absolute;inset:0;background:linear-gradient(180deg, rgba(0,0,0,.28), rgba(0,0,0,0));opacity:0;transition:opacity .2s ease}
        .lesson-cover.has-image:hover .lesson-cover-overlay{opacity:1}
        .lesson-cover-action{position:absolute;top:14px;right:14px;width:42px;height:42px;border-radius:50%;background:rgba(255,255,255,.92);display:flex;align-items:center;justify-content:center;box-shadow:0 10px 24px rgba(15,23,42,.18)}
        .lesson-cover-action i{font-size:18px;color:#0f172a}
        .lesson-cover-placeholder{position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:10px;color:#0f172a}
        .lesson-cover-placeholder .plus{width:54px;height:54px;border-radius:50%;background:rgba(43,124,255,.10);display:flex;align-items:center;justify-content:center}
        .lesson-cover-placeholder .plus i{font-size:22px;color:#2b7cff}
        .lesson-cover-placeholder .title{font-weight:700;font-size:1rem}
        .lesson-cover-placeholder .hint{font-size:.95rem;opacity:.75}
        .noUi-connect{background:#4e73df}
        .noUi-horizontal{height:8px;margin:15px 0}
        .noUi-horizontal .noUi-handle{width:20px;height:20px;right:-10px;top:-7px;border-radius:50%;background:#4e73df;border:2px solid white;box-shadow:0 2px 4px rgba(0,0,0,0.1)}
        .noUi-handle:before,.noUi-handle:after{display:none}
        @media (max-width:576px){.lesson-cover{height:190px}}
    </style>

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
          <h1 class="h3 mb-0 text-gray-800">Add New Lesson</h1>
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
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-plus-circle"></i> Lesson Information</h6>
          </div>
          <div class="card-body">
            <form method="POST" action="lessonAdd_direct.php" enctype="multipart/form-data">
              <!-- Thumbnail Upload -->
              <div class="form-group">
                <label>Lesson Cover Image</label>
                <input type="file" class="d-none" id="thumbnail" name="thumbnail" accept="image/jpeg,image/png,image/gif">
                <div id="lessonCover" class="lesson-cover">
                  <div id="lessonCoverPlaceholder" class="lesson-cover-placeholder">
                    <div class="plus"><i class="fas fa-plus"></i></div>
                    <div class="title">Add image cover to your lesson</div>
                    <div class="hint">Click to upload (JPG, PNG, GIF)</div>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="title">Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="title" name="title" required placeholder="Enter lesson title" value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">
              </div>

              <div class="form-row">
                <div class="form-group col-md-6">
                  <label>Age Range <span class="text-danger">*</span></label>
                  <div id="age-range-slider" class="mb-3"></div>
                  <div class="d-flex justify-content-between mb-2">
                    <span id="age-min-display" class="badge badge-primary">5</span>
                    <span class="text-muted">to</span>
                    <span id="age-max-display" class="badge badge-primary">18</span>
                  </div>
                  <input type="hidden" name="ageRange" id="age-range-value" value="<?= htmlspecialchars($_POST['ageRange'] ?? '5-18') ?>">
                </div>
                <div class="form-group col-md-6">
                  <label for="duration">Duration (minutes) <span class="text-danger">*</span></label>
                  <input type="number" class="form-control" id="duration" name="duration" min="1" required placeholder="Enter duration" value="<?= htmlspecialchars($_POST['duration'] ?? '') ?>">
                </div>
              </div>

              <div class="form-group">
                <label for="quiz_time_limit">Quiz Time Limit (minutes)</label>
                <input type="number" class="form-control" id="quiz_time_limit" name="quiz_time_limit" min="1" max="180" placeholder="e.g., 30" value="<?= htmlspecialchars($_POST['quiz_time_limit'] ?? 30) ?>">
                <small class="form-text text-muted">Set time limit for quiz (1-180 minutes). Default: 30 minutes.</small>
              </div>

              <div class="form-group">
                <label for="description">Description <span class="text-danger">*</span></label>
                <textarea class="form-control" id="description" name="description" rows="5" required placeholder="Enter lesson description"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
              </div>

              <hr>
              <div class="form-group mb-0">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Add Lesson</button>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.5.0/nouislider.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Age Range Slider
    const ageSlider = document.getElementById('age-range-slider');
    const ageMinDisplay = document.getElementById('age-min-display');
    const ageMaxDisplay = document.getElementById('age-max-display');
    const ageRangeValue = document.getElementById('age-range-value');
    
    let currentValue = ageRangeValue.value;
    let [startMin, startMax] = currentValue ? currentValue.split('-').map(Number) : [5, 18];
    
    noUiSlider.create(ageSlider, {
        start: [startMin, startMax],
        connect: true,
        range: { 'min': 3, 'max': 30 },
        step: 1,
        tooltips: false
    });

    ageSlider.noUiSlider.on('update', function(values, handle) {
        const minAge = Math.round(values[0]);
        const maxAge = Math.round(values[1]);
        ageMinDisplay.textContent = minAge;
        ageMaxDisplay.textContent = maxAge;
        ageRangeValue.value = minAge + '-' + maxAge;
    });

    // Thumbnail Upload
    const thumbnailInput = document.getElementById('thumbnail');
    const cover = document.getElementById('lessonCover');
    
    if (thumbnailInput && cover) {
        cover.addEventListener('click', function() {
            thumbnailInput.click();
        });

        thumbnailInput.addEventListener('change', function() {
            const file = thumbnailInput.files && thumbnailInput.files[0];
            if (!file) return;

            const placeholder = document.getElementById('lessonCoverPlaceholder');
            const url = URL.createObjectURL(file);

            if (placeholder) placeholder.remove();

            let img = document.getElementById('lessonCoverImg');
            if (!img) {
                img = document.createElement('img');
                img.id = 'lessonCoverImg';
                img.className = 'lesson-cover-img';
                img.alt = 'Lesson cover';
                cover.prepend(img);

                const overlay = document.createElement('div');
                overlay.className = 'lesson-cover-overlay';
                overlay.innerHTML = '<div class="lesson-cover-action"><i class="fas fa-pencil-alt"></i></div>';
                cover.appendChild(overlay);
            }
            img.src = url;
            cover.classList.add('has-image');
        });
    }
});
</script>
</body>
</html>
