<?php
session_start();
require_once "../../../Controller/UserController.php";
require_once "../../../Model/User.php";

// Messages
$error = '';
$resendSuccess = '';
$resendError = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $resend   = isset($_POST['resend_verification']);

    $controller = new UserController();

    // === RESEND VERIFICATION ===
    if ($resend && !empty($email)) {
        if ($controller->resendVerificationEmail($email)) {
            $resendSuccess = "Verification email sent! Check your inbox and spam folder.";
        } else {
            $resendError = "Failed to send email. Account may already be verified or not exist.";
        }
    }
    // === NORMAL LOGIN ===
    else {
        $user = $controller->getUserByEmail($email);

        if ($user && password_verify($password, $user['password'])) {

            // 1. BANNED → BLOCK
            if ($user['is_banned'] == 1) {
                $error = "Your account has been banned. Please contact support.";
            }
            // 2. NOT APPROVED → BLOCK (except admin)
            elseif ($user['is_approved'] == 0 && $user['role'] !== 'admin') {
                $error = "Your account is pending admin approval. Please wait.";
            }
            // 3. NOT VERIFIED → BLOCK
            elseif ($user['verified'] == 0) {
                $error = "Please verify your email first! Check your inbox (and spam folder).";
            }
            // 4. SUCCESS → LOGIN
            else {
                $_SESSION['user_id']      = $user['user_id'];
                $_SESSION['email']        = $user['email'];
                $_SESSION['fname']        = $user['fname'];
                $_SESSION['lname']        = $user['lname'];
                $_SESSION['DOB']          = $user['DOB'];
                $_SESSION['role']         = $user['role'];
                $_SESSION['avatar']       = $user['avatar'] ?? 'default-avatar.png';
                $_SESSION['description']  = $user['description'] ?? '';
                $_SESSION['starPoints']   = $user['starPoints'] ?? 0;

                header('Location: ../template/index.html');
                exit;
            }
        } else {
            $error = "Invalid email or password!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Starr - Login</title>
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/css/style.css" rel="stylesheet">
  <style>
    .left-panel { 
      background: linear-gradient(135deg, #56ab2f, #a8e6cf); 
      min-height: 100vh; 
      position: relative;
    }
    .left-panel::before {
      content: "";
      position: absolute;
      top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(0,0,0,0.12);
    }
    .right-container {
      position: relative;
      overflow: hidden;                     
    }
    .right-image {
      width: 100%;
      height: 100vh;
      object-fit: cover;
      filter: blur(5px);                  
      transition: filter 0.5s ease;
    }
    .right-container:hover .right-image {
      filter: blur(2px);
    }
    .right-overlay {
      position: absolute;
      top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(0, 50, 0, 0.25);
    }
  </style>
</head>
<body class="m-0">

<div class="row g-0 min-vh-100">
  <div class="col-lg-6 left-panel d-flex align-items-center justify-content-center position-relative">
    <div class="position-relative z-10 text-center text-lg-start px-4" style="max-width: 420px;">
      <img src="../assets/img/starr.jpg" alt="Starr Logo" class="img-fluid mb-4 rounded shadow" style="height: 100px;">
      <h2 class="text-white fw-bold mb-4">Welcome Back!</h2>

      <!-- Success / Error Messages -->
      <?php if($resendSuccess): ?>
        <div class="alert alert-success"><?= htmlspecialchars($resendSuccess) ?></div>
      <?php endif; ?>
      <?php if($resendError): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($resendError) ?></div>
      <?php endif; ?>
      <?php if(!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>

        <!-- Show Resend Button Only When Email Not Verified -->
        <?php if (strpos($error, 'verify your email') !== false): ?>
          <div class="alert alert-info mt-3">
            Didn't receive the email?
            <form method="POST" class="d-inline ms-2">
              <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
              <button type="submit" name="resend_verification" class="btn btn-sm btn-light fw-bold">
                Resend Verification Email
              </button>
            </form>
          </div>
        <?php endif; ?>
      <?php endif; ?>

      <form id="myform" method="POST">
        <div class="mb-3">
          <input name="email" id="email" value="<?= htmlspecialchars($email) ?>" 
                 class="form-control form-control-lg rounded-pill" placeholder="Enter your email" required>
        </div>
        <div class="mb-4">
          <input name="password" id="password" type="password" 
                 class="form-control form-control-lg rounded-pill" placeholder="Enter your password" required>
        </div>
        <button id="subButton" type="submit" class="btn btn-success btn-lg rounded-pill w-100 shadow-lg fw-bold">
          Login
        </button>
      </form>

      <p class="text-white mt-4 mb-0">
        New here? <a href="signup.php" class="text-white fw-bold text-decoration-underline">Create an account!</a>
      </p>
    </div>
  </div>

  <div class="col-lg-6 d-none d-lg-block p-0 right-container">
    <img src="../assets/img/G1201615082.webp" class="right-image" alt="Happy Kids">
    <div class="right-overlay"></div>
  </div>
</div>

</body>
</html>