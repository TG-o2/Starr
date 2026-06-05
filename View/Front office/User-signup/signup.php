<?php
session_start();
require_once "../../../Controller/UserController.php";
require_once "../../../Model/User.php";

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Basic required fields validation
    $required = ['fname', 'lname', 'email', 'password', 'DOB', 'role'];
    foreach ($required as $field) {
        if (empty(trim($_POST[$field]))) {
            $errors[] = ucfirst($field) . " is required.";
        }
    }

    $email = trim($_POST['email'] ?? '');
    if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (!empty($_POST['password']) && strlen($_POST['password']) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }

    // Avatar upload
    $avatarName = null;
    if (!empty($_FILES['avatar']['name']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $avatarName = time() . "_" . basename($_FILES['avatar']['name']);
        $uploadPath = "../assets/img/userProfile/" . $avatarName;
        if (!move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadPath)) {
            $errors[] = "Failed to upload avatar.";
            $avatarName = null;
        }
    }

    // If errors, store and redirect back
    if (!empty($errors)) {
        $_SESSION['signup_errors'] = $errors;
        $_SESSION['old_input'] = $_POST;
        header('Location: signup.php');
        exit;
    }

    // Check if email already exists
    $controller = new UserController();
    if ($controller->getUserByEmail($email)) {
        $_SESSION['signup_errors'] = ["This email is already registered!"];
        $_SESSION['old_input'] = $_POST;
        header('Location: signup.php');
        exit;
    }

    $role = strtolower(trim($_POST['role'] ?? 'student'));
    if (!in_array($role, ['student', 'teacher', 'parent', 'kid'], true)) {
      $errors[] = 'Please select a valid role.';
    }

    if (!empty($errors)) {
      $_SESSION['signup_errors'] = $errors;
      $_SESSION['old_input'] = $_POST;
      header('Location: signup.php');
      exit;
    }

    // Generate username from email (part before @)
    $username = strtolower(explode('@', $email)[0]);

    $user = new User(
      uniqid("USR_"),                 // user_id
      $_POST['password'],             // password
      $_POST['fname'],                // fname
      $_POST['lname'],                // lname
      $_POST['DOB'],                  // DOB
      null,                           // profilePicture
      $_POST['description'] ?? '',    // description
      $username,                      // username (auto-generated from email)
      $email,                         // email
      $role,                          // role
      $avatarName,                    // avatar
      0,                              // verified (requires email verification)
      0,                              // is_banned
      0,                              // is_approved (0 - needs admin approval for teachers)
      null,                           // verification_token (set inside controller)
      null                            // approval_token
    );

    try {
      $controller->addUserWithVerification($user);
      unset($_SESSION['old_input']);
      $_SESSION['signup_success'] = "Account created! Please check your email (including spam) for the verification link.";
      header('Location: login.php');
      exit;
    } catch (Exception $e) {
      $_SESSION['signup_errors'] = ["We could not create your account right now. Please try again."];
      $_SESSION['old_input'] = $_POST;
      header('Location: signup.php');
      exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Starr - Create Account</title>
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/css/style.css" rel="stylesheet">
  <style>
    body { 
      background: linear-gradient(135deg, #56ab2f, #a8e6cf); 
      min-height: 100vh;
    }
  </style>
</head>
<body class="d-flex align-items-center min-vh-100">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-6 col-xl-5">

      <div class="text-center mb-5">
        <img src="../assets/img/starr.jpg" alt="Starr Logo" class="img-fluid rounded shadow" style="height: 110px;">
        <h2 class="text-white fw-bold mt-3">Create an Account</h2>
      </div>

      <!-- Show validation errors -->
      <?php if (isset($_SESSION['signup_errors'])): ?>
        <div class="alert alert-danger">
          <ul class="mb-0">
            <?php foreach ($_SESSION['signup_errors'] as $err): ?>
              <li><?= htmlspecialchars($err) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
        <?php unset($_SESSION['signup_errors']); ?>
      <?php endif; ?>

      <form method="POST" enctype="multipart/form-data">
        <div class="row g-3">
          <div class="col-md-6">
            <input name="fname" class="form-control form-control-lg rounded-pill" placeholder="First name" 
                   value="<?= htmlspecialchars($_SESSION['old_input']['fname'] ?? '') ?>" required>
          </div>
          <div class="col-md-6">
            <input name="lname" class="form-control form-control-lg rounded-pill" placeholder="Last name" 
                   value="<?= htmlspecialchars($_SESSION['old_input']['lname'] ?? '') ?>" required>
          </div>
        </div>

        <div class="mt-3">
          <input name="email" type="email" class="form-control form-control-lg rounded-pill" placeholder="Email" 
                 value="<?= htmlspecialchars($_SESSION['old_input']['email'] ?? '') ?>" required>
        </div>

        <div class="mt-3">
          <input name="password" type="password" class="form-control form-control-lg rounded-pill" placeholder="Password" required>
        </div>

        <div class="mt-3">
          <input name="DOB" type="date" class="form-control form-control-lg rounded-pill" 
                 value="<?= htmlspecialchars($_SESSION['old_input']['DOB'] ?? '') ?>" required>
        </div>

        <div class="mt-3">
          <select name="role" class="form-select form-select-lg rounded-pill" required>
            <option value="" <?= empty($_SESSION['old_input']['role']) ? 'selected' : '' ?> disabled>Select role</option>
            <option value="Student" <?= ($_SESSION['old_input']['role'] ?? '') === 'Student' ? 'selected' : '' ?>>Student</option>
            <option value="Teacher" <?= ($_SESSION['old_input']['role'] ?? '') === 'Teacher' ? 'selected' : '' ?>>Teacher</option>
            <option value="Parent" <?= ($_SESSION['old_input']['role'] ?? '') === 'Parent' ? 'selected' : '' ?>>Parent</option>
            <option value="Kid" <?= ($_SESSION['old_input']['role'] ?? '') === 'Kid' ? 'selected' : '' ?>>Kid</option>
          </select>
        </div>

        <div class="mt-3">
          <input name="avatar" type="file" class="form-control form-control-lg rounded-pill" accept="image/*">
        </div>

        <div class="mt-3">
          <textarea name="description" class="form-control rounded-4" placeholder="Describe yourself (optional)"><?= htmlspecialchars($_SESSION['old_input']['description'] ?? '') ?></textarea>
        </div>

        <?php unset($_SESSION['old_input']); ?>

        <div class="d-grid mt-4">
          <button type="submit" id="subButton" class="btn btn-success btn-lg rounded-pill shadow fw-bold">
            Sign Up
          </button>
        </div>
      </form>

      <p class="text-center mt-4 mb-0">
        Already have an account? <a href="login.php" class="text-success fw-bold">Login here!</a>
      </p>

      </div>
    </div>
  </div>

<script src="../assets/js/createAccountJS.js"></script>
<script src="../js/main.js"></script>
</body>
</html>