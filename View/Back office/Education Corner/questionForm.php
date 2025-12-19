<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= isset($question) ? 'Edit' : 'Add' ?> Question - Education Corner Admin</title>
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
          <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-<?= isset($question) ? 'edit' : 'plus-circle' ?>"></i> <?= isset($question) ? 'Edit' : 'Add New' ?> Question</h1>
          <a href="questionList_direct.php" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Back to List</a>
        </div>

        <?php if(isset($error)): ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error) ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        <?php endif; ?>

        <div class="card shadow mb-4">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-question"></i> Question Information</h6>
          </div>
          <div class="card-body">
            <form method="post">
              <div class="form-group">
                <label for="lessonId">Associated Lesson <span class="text-danger">*</span></label>
                <select class="form-control" id="lessonId" name="lessonId">
                  <option value="">-- Select a Lesson --</option>
                  <?php foreach($lessons as $l): ?>
                    <option value="<?= $l['lessonId'] ?>" <?= isset($question) && $question['lessonId'] == $l['lessonId'] ? 'selected' : '' ?>>
                      <?= htmlspecialchars($l['title']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <span class="text-sm d-block mt-1" id="lessonId-msg"></span>
              </div>

              <div class="form-group">
                <label for="questionText">Question Text <span class="text-danger">*</span></label>
                <textarea class="form-control" id="questionText" name="questionText" rows="4" placeholder="Enter the question text"><?= htmlspecialchars($question['questionText'] ?? '') ?></textarea>
                <span class="text-sm d-block mt-1" id="questionText-msg"></span>
              </div>

              <hr>
              <h6 class="text-primary"><i class="fas fa-list-ul"></i> Answer Options</h6>
              
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="option1">Option 1 <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="option1" name="option1" value="<?= htmlspecialchars($question['option1'] ?? '') ?>" placeholder="First answer option">
                  <span class="text-sm d-block mt-1" id="option1-msg"></span>
                </div>
                <div class="form-group col-md-6">
                  <label for="option2">Option 2 <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="option2" name="option2" value="<?= htmlspecialchars($question['option2'] ?? '') ?>" placeholder="Second answer option">
                  <span class="text-sm d-block mt-1" id="option2-msg"></span>
                </div>
              </div>

              <div class="form-group">
                <label for="option3">Option 3 <span class="text-muted">(Optional)</span></label>
                <input type="text" class="form-control" id="option3" name="option3" value="<?= htmlspecialchars($question['option3'] ?? '') ?>" placeholder="Third answer option (leave blank if only 2 options needed)">
                <span class="text-sm d-block mt-1" id="option3-msg"></span>
              </div>

              <div class="form-group">
                <label for="goodAnswer">Correct Answer <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="goodAnswer" name="goodAnswer" value="<?= htmlspecialchars($question['goodAnswer'] ?? '') ?>" placeholder="Enter the correct answer">
                <small class="form-text text-muted"><i class="fas fa-info-circle"></i> Must match exactly one of the options above.</small>
                <span class="text-sm d-block mt-1" id="goodAnswer-msg"></span>
              </div>

              <div class="form-group">
                <label for="points">Points <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="points" name="points" value="<?= htmlspecialchars($question['points'] ?? 5) ?>" placeholder="Points awarded for correct answer">
                <small class="form-text text-muted"><i class="fas fa-star"></i> Points awarded when this question is answered correctly (default: 5 points).</small>
                <span class="text-sm d-block mt-1" id="points-msg"></span>
              </div>

              <hr>
              <div class="form-group mb-0">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> <?= isset($question) ? 'Update' : 'Add' ?> Question</button>
                <a href="questionList_direct.php" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
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

<script>
// Real-time validation for question form
(function() {
    'use strict';

    const form = document.querySelector('form');
    let validations = {
        lessonId: false,
        questionText: false,
        option1: false,
        option2: false,
        goodAnswer: false,
        points: false
    };

    // Validate select fields
    function validateSelect(selectId, msgId) {
        const msg = document.getElementById(msgId);
        const select = document.getElementById(selectId);
        if (!select) return false;

        if (select.value !== "" && select.value !== "0") {
            select.style.border = "2px solid #28a745";
            if (msg) { 
                msg.style.color = "#28a745"; 
                msg.innerHTML = '<i class="fas fa-check-circle"></i> Lesson selected';
            }
            return true;
        } else {
            select.style.border = "2px solid #dc3545";
            if (msg) { 
                msg.style.color = "#dc3545"; 
                msg.innerHTML = '<i class="fas fa-exclamation-circle"></i> Please select a lesson';
            }
            return false;
        }
    }

    // Validate text fields (minimum 3 characters)
    function validateText(inputId, msgId, minLength = 3) {
        const msg = document.getElementById(msgId);
        const input = document.getElementById(inputId);
        if (!input) return false;

        const val = input.value.trim();
        if (val.length >= minLength) {
            input.style.border = "2px solid #28a745";
            if (msg) { 
                msg.style.color = "#28a745"; 
                msg.innerHTML = '<i class="fas fa-check-circle"></i> Valid';
            }
            return true;
        } else if (val.length > 0) {
            input.style.border = "2px solid #dc3545";
            if (msg) { 
                msg.style.color = "#dc3545"; 
                msg.innerHTML = '<i class="fas fa-exclamation-circle"></i> Minimum ' + minLength + ' characters required';
            }
            return false;
        } else {
            input.style.border = "2px solid #dc3545";
            if (msg) { 
                msg.style.color = "#dc3545"; 
                msg.innerHTML = '<i class="fas fa-exclamation-circle"></i> This field is required';
            }
            return false;
        }
    }

    // Validate number field (points)
    function validatePoints(inputId, msgId) {
        const msg = document.getElementById(msgId);
        const input = document.getElementById(inputId);
        if (!input) return false;

        const val = input.value.trim();
        const num = Number(val);

        if (val === "") {
            input.style.border = "2px solid #dc3545";
            if (msg) { 
                msg.style.color = "#dc3545"; 
                msg.innerHTML = '<i class="fas fa-exclamation-circle"></i> Points are required';
            }
            return false;
        }

        if (isNaN(num) || num < 1 || num > 100) {
            input.style.border = "2px solid #dc3545";
            if (msg) { 
                msg.style.color = "#dc3545"; 
                msg.innerHTML = '<i class="fas fa-exclamation-circle"></i> Points must be between 1 and 100';
            }
            return false;
        }

        input.style.border = "2px solid #28a745";
        if (msg) { 
            msg.style.color = "#28a745"; 
            msg.innerHTML = '<i class="fas fa-check-circle"></i> Valid points';
        }
        return true;
    }

    // Validate correct answer matches one of the options
    function validateCorrectAnswer() {
        const goodAnswer = document.getElementById('goodAnswer');
        const option1 = document.getElementById('option1');
        const option2 = document.getElementById('option2');
        const option3 = document.getElementById('option3');
        const msg = document.getElementById('goodAnswer-msg');

        if (!goodAnswer || !option1 || !option2) return false;

        const correctVal = goodAnswer.value.trim().toLowerCase();
        const opt1Val = option1.value.trim().toLowerCase();
        const opt2Val = option2.value.trim().toLowerCase();
        const opt3Val = option3 ? option3.value.trim().toLowerCase() : '';

        if (correctVal === '') {
            goodAnswer.style.border = "2px solid #dc3545";
            if (msg) {
                msg.style.color = "#dc3545";
                msg.innerHTML = '<i class="fas fa-exclamation-circle"></i> Correct answer is required';
            }
            return false;
        }

        if (correctVal === opt1Val || correctVal === opt2Val || (opt3Val && correctVal === opt3Val)) {
            goodAnswer.style.border = "2px solid #28a745";
            if (msg) {
                msg.style.color = "#28a745";
                msg.innerHTML = '<i class="fas fa-check-circle"></i> Matches an option';
            }
            return true;
        } else {
            goodAnswer.style.border = "2px solid #dc3545";
            if (msg) {
                msg.style.color = "#dc3545";
                msg.innerHTML = '<i class="fas fa-exclamation-circle"></i> Must match one of the options above';
            }
            return false;
        }
    }

    // Validate option3 (optional field)
    function validateOption3() {
        const input = document.getElementById('option3');
        const msg = document.getElementById('option3-msg');
        if (!input) return true;

        const val = input.value.trim();
        if (val.length === 0) {
            input.style.border = "";
            if (msg) {
                msg.innerHTML = '<i class="fas fa-info-circle" style="color: #6c757d;"></i> Optional';
            }
            return true;
        } else if (val.length >= 1) {
            input.style.border = "2px solid #28a745";
            if (msg) {
                msg.style.color = "#28a745";
                msg.innerHTML = '<i class="fas fa-check-circle"></i> Valid option';
            }
            return true;
        }
        return true;
    }

    // Event listeners
    document.getElementById('lessonId')?.addEventListener('change', function() {
        validations.lessonId = validateSelect('lessonId', 'lessonId-msg');
    });

    document.getElementById('questionText')?.addEventListener('input', function() {
        validations.questionText = validateText('questionText', 'questionText-msg', 10);
    });

    document.getElementById('option1')?.addEventListener('input', function() {
        validations.option1 = validateText('option1', 'option1-msg', 1);
        validateCorrectAnswer();
    });

    document.getElementById('option2')?.addEventListener('input', function() {
        validations.option2 = validateText('option2', 'option2-msg', 1);
        validateCorrectAnswer();
    });

    document.getElementById('option3')?.addEventListener('input', function() {
        validateOption3();
        validateCorrectAnswer();
    });

    document.getElementById('goodAnswer')?.addEventListener('input', function() {
        validations.goodAnswer = validateCorrectAnswer();
    });

    document.getElementById('points')?.addEventListener('input', function() {
        validations.points = validatePoints('points', 'points-msg');
    });

    // Form submission validation
    if (form) {
        form.addEventListener('submit', function(event) {
            // Validate all required fields
            validations.lessonId = validateSelect('lessonId', 'lessonId-msg');
            validations.questionText = validateText('questionText', 'questionText-msg', 10);
            validations.option1 = validateText('option1', 'option1-msg', 1);
            validations.option2 = validateText('option2', 'option2-msg', 1);
            validations.goodAnswer = validateCorrectAnswer();
            validations.points = validatePoints('points', 'points-msg');
            validateOption3();

            // Check if all required fields are valid
            const allValid = validations.lessonId && 
                           validations.questionText && 
                           validations.option1 && 
                           validations.option2 && 
                           validations.goodAnswer && 
                           validations.points;

            if (!allValid) {
                event.preventDefault();
                alert('Please correct all errors before submitting the form.');
                
                // Scroll to first error
                const firstError = document.querySelector('[style*="border: 2px solid rgb(220, 53, 69)"]');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstError.focus();
                }
            }
        });
    }
})();
</script>
</body>
</html>
