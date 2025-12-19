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
  <ul class="navbar-nav bg-gradient-success sidebar sidebar-dark accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="Dashboard.php">
      <div class="sidebar-brand-icon rotate-n-15"><i class="fas fa-chalkboard-teacher"></i></div>
      <div class="sidebar-brand-text mx-3">Teacher Starr</div>
    </a>
    <hr class="sidebar-divider my-0">
    <li class="nav-item"><a class="nav-link" href="../Teacher Dashboard/Dashboard.php"><i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a></li>
    <hr class="sidebar-divider">
    <div class="sidebar-heading">Education Corner</div>
    <li class="nav-item"><a class="nav-link" href="lessonList_direct.php"><i class="fas fa-book"></i><span>Lessons</span></a></li>
    <li class="nav-item active"><a class="nav-link" href="questionList_direct.php"><i class="fas fa-question-circle"></i><span>Questions</span></a></li>
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
                <label for="question">Question Text <span class="text-danger">*</span></label>
                <textarea class="form-control" id="question" name="question" rows="4" placeholder="Enter the question text"><?= htmlspecialchars($question['question'] ?? $question['questionText'] ?? '') ?></textarea>
                <span class="text-sm d-block mt-1" id="question-msg"></span>
              </div>

              <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="difficulty">Difficulty</label>
                  <select class="form-control" id="difficulty" name="difficulty">
                    <option value="easy" <?= isset($question) && ($question['difficulty'] ?? '') == 'easy' ? 'selected' : '' ?>>Easy</option>
                    <option value="medium" <?= isset($question) && ($question['difficulty'] ?? '') == 'medium' ? 'selected' : '' ?>>Medium</option>
                    <option value="hard" <?= isset($question) && ($question['difficulty'] ?? '') == 'hard' ? 'selected' : '' ?>>Hard</option>
                  </select>
                </div>
                <div class="form-group col-md-4">
                  <label for="time_limit">Time Limit (seconds)</label>
                  <input type="number" class="form-control" id="time_limit" name="time_limit" min="10" max="600" value="<?= htmlspecialchars($question['time_limit'] ?? 60) ?>" placeholder="60">
                  <small class="form-text text-muted">10-600 seconds</small>
                </div>
                <div class="form-group col-md-4">
                  <label for="points">Points <span class="text-danger">*</span></label>
                  <input type="number" class="form-control" id="points" name="points" min="1" max="100" value="<?= htmlspecialchars($question['points'] ?? 5) ?>" placeholder="5">
                </div>
              </div>

              <hr>
              <h6 class="text-primary"><i class="fas fa-list-ul"></i> Answer Options</h6>
              
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="optionA">Option A <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="optionA" name="optionA" value="<?= htmlspecialchars($question['optionA'] ?? $question['option1'] ?? '') ?>" placeholder="First answer option">
                  <span class="text-sm d-block mt-1" id="optionA-msg"></span>
                </div>
                <div class="form-group col-md-6">
                  <label for="optionB">Option B <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="optionB" name="optionB" value="<?= htmlspecialchars($question['optionB'] ?? $question['option2'] ?? '') ?>" placeholder="Second answer option">
                  <span class="text-sm d-block mt-1" id="optionB-msg"></span>
                </div>
              </div>

              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="optionC">Option C</label>
                  <input type="text" class="form-control" id="optionC" name="optionC" value="<?= htmlspecialchars($question['optionC'] ?? $question['option3'] ?? '') ?>" placeholder="Third answer option (optional)">
                  <span class="text-sm d-block mt-1" id="optionC-msg"></span>
                </div>
                <div class="form-group col-md-6">
                  <label for="optionD">Option D</label>
                  <input type="text" class="form-control" id="optionD" name="optionD" value="<?= htmlspecialchars($question['optionD'] ?? '') ?>" placeholder="Fourth answer option (optional)">
                  <span class="text-sm d-block mt-1" id="optionD-msg"></span>
                </div>
              </div>

              <div class="form-group">
                <label for="goodAnswer">Correct Answer <span class="text-danger">*</span></label>
                <select class="form-control" id="goodAnswer" name="goodAnswer">
                  <option value="">-- Select Correct Answer --</option>
                  <option value="A" <?= isset($question) && ($question['goodAnswer'] ?? '') == 'A' ? 'selected' : '' ?>>A</option>
                  <option value="B" <?= isset($question) && ($question['goodAnswer'] ?? '') == 'B' ? 'selected' : '' ?>>B</option>
                  <option value="C" <?= isset($question) && ($question['goodAnswer'] ?? '') == 'C' ? 'selected' : '' ?>>C</option>
                  <option value="D" <?= isset($question) && ($question['goodAnswer'] ?? '') == 'D' ? 'selected' : '' ?>>D</option>
                </select>
                <small class="form-text text-muted"><i class="fas fa-info-circle"></i> Select which option (A, B, C, or D) is correct.</small>
                <span class="text-sm d-block mt-1" id="goodAnswer-msg"></span>
              </div>

              <div class="form-group">
                <label for="explanation">Explanation (Optional)</label>
                <textarea class="form-control" id="explanation" name="explanation" rows="3" placeholder="Explain why this is the correct answer (shown after quiz completion)"><?= htmlspecialchars($question['explanation'] ?? '') ?></textarea>
                <small class="form-text text-muted">Help students learn by explaining the correct answer.</small>
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
        question: false,
        optionA: false,
        optionB: false,
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

    // Validate correct answer selection
    function validateCorrectAnswer() {
        const goodAnswer = document.getElementById('goodAnswer');
        const msg = document.getElementById('goodAnswer-msg');

        if (!goodAnswer) return false;

        if (goodAnswer.value === '') {
            goodAnswer.style.border = "2px solid #dc3545";
            if (msg) {
                msg.style.color = "#dc3545";
                msg.innerHTML = '<i class="fas fa-exclamation-circle"></i> Please select the correct answer';
            }
            return false;
        }

        goodAnswer.style.border = "2px solid #28a745";
        if (msg) {
            msg.style.color = "#28a745";
            msg.innerHTML = '<i class="fas fa-check-circle"></i> Correct answer selected';
        }
        return true;
    }

    // Event listeners
    document.getElementById('lessonId')?.addEventListener('change', function() {
        validations.lessonId = validateSelect('lessonId', 'lessonId-msg');
    });

    document.getElementById('question')?.addEventListener('input', function() {
        validations.question = validateText('question', 'question-msg', 10);
    });

    document.getElementById('optionA')?.addEventListener('input', function() {
        validations.optionA = validateText('optionA', 'optionA-msg', 1);
    });

    document.getElementById('optionB')?.addEventListener('input', function() {
        validations.optionB = validateText('optionB', 'optionB-msg', 1);
    });

    document.getElementById('goodAnswer')?.addEventListener('change', function() {
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
            validations.question = validateText('question', 'question-msg', 10);
            validations.optionA = validateText('optionA', 'optionA-msg', 1);
            validations.optionB = validateText('optionB', 'optionB-msg', 1);
            validations.goodAnswer = validateCorrectAnswer();
            validations.points = validatePoints('points', 'points-msg');

            // Check if all required fields are valid
            const allValid = validations.lessonId && 
                           validations.question && 
                           validations.optionA && 
                           validations.optionB && 
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
