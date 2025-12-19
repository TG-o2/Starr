<?php
session_start();

require_once "../../../Controller/UserController.php";
require_once "../../../Model/User.php";

/* ================== reCAPTCHA keys ================== */
$recaptcha_site_key   = '6LelwSgsAAAAAL6gG2O5vwCAdAk4xqTCWOIsTiQJ';
$recaptcha_secret_key = '6LelwSgsAAAAALkv6i11JDjmNAW3IVWrpZqvwAAI';

/* ================== Variables ================== */
$error = '';
$resendSuccess = '';
$resendError = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $resend   = isset($_POST['resend_verification']);

    /* ================== CAPTCHA ================== */
    $recaptcha_response = $_POST['g-recaptcha-response'] ?? '';

    if (empty($recaptcha_response)) {
        $error = "Please complete the CAPTCHA.";
    } else {
        $verify = file_get_contents(
            "https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret_key}&response={$recaptcha_response}"
        );
        $captcha_result = json_decode($verify);

        if (!$captcha_result || $captcha_result->success !== true) {
            $error = "CAPTCHA failed. Are you a robot?";
        }
    }

    $controller = new UserController();

    if ($resend && !empty($email)) {
        if ($controller->resendVerificationEmail($email)) {
            $resendSuccess = "Verification email sent! Check your inbox and spam folder.";
        } else {
            $resendError = "Failed to send email. Account may already be verified or not exist.";
        }
    }

    else {
        $user = $controller->getUserByEmail($email);

        if ($user && password_verify($password, $user['password'])) {

            if ((int)$user['verified'] === 0) {
              // Auto-resend verification email when unverified
              if ($controller->resendVerificationEmail($email)) {
                $error = "Your account is not verified. A new verification email has been sent to <strong>{$email}</strong>. Please check your inbox and spam folder.";
              } else {
                $error = "Account requires verification, but email sending failed. Please try again later.";
              }
            }
            elseif ((int)$user['is_banned'] === 1) {
                $error = "Your account has been banned. Please contact support.";
            }

            elseif ((int)$user['is_approved'] === 0 && $user['role'] !== 'admin') {
                $error = "Your account is pending admin approval. Please wait.";
            }

            else {
                // Successful login
                $_SESSION['user_id']     = $user['user_id'];
                $_SESSION['email']       = $user['email'];
                $_SESSION['fname']       = $user['fname'];
                $_SESSION['lname']       = $user['lname'];
                $_SESSION['DOB']         = $user['DOB'];
                $_SESSION['role']        = $user['role'];
                $_SESSION['avatar']      = $user['avatar'] ?? 'default-avatar.png';
                $_SESSION['description'] = $user['description'] ?? '';
                $_SESSION['starPoints']  = $user['starPoints'] ?? 0;

                header('Location: ../index.html');
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        body { margin: 0; font-family: 'Segoe UI', sans-serif; }
        .left-panel { background: linear-gradient(135deg, #28a745, #20c997); position: relative; }
        .left-panel::before { content: ""; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.12); }
        .right-container { position: relative; overflow: hidden; }
        .right-image { width: 100%; height: 100vh; object-fit: cover; filter: blur(5px); transition: filter 0.5s ease; }
        .right-container:hover .right-image { filter: blur(2px); }
        .right-overlay { position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 50, 0, 0.25); }
    </style>
</head>
<body class="m-0">

<div class="row g-0 min-vh-100">
  <div class="col-lg-6 left-panel d-flex align-items-center justify-content-center position-relative">
    <div class="position-relative z-10 text-center text-lg-start px-4" style="max-width: 420px;">
      <img src="../assets/img/starr.jpg" alt="Starr Logo" class="img-fluid mb-4 rounded shadow" style="height: 100px;">
      <h2 class="text-white fw-bold mb-4">Welcome Back!</h2>

      <!-- Signup success message -->
      <?php if (isset($_SESSION['signup_success'])): ?>
        <div class="alert alert-success text-center fw-bold">
          <?= htmlspecialchars($_SESSION['signup_success']) ?>
        </div>
        <?php unset($_SESSION['signup_success']); ?>
      <?php endif; ?>

      <!-- Logged out message -->
      <?php if (isset($_GET['logged_out'])): ?>
        <div class="alert alert-info text-center">
          You have been logged out successfully.
        </div>
      <?php endif; ?>

      <!-- Verification feedback from verify.php -->
      <?php if (isset($_GET['msg'])): ?>
        <div class="alert <?= strpos($_GET['msg'], 'successfully') !== false ? 'alert-success' : 'alert-danger' ?> text-center">
          <?= htmlspecialchars(urldecode($_GET['msg'])) ?>
        </div>
      <?php endif; ?>

      <!-- Manual resend success -->
      <?php if($resendSuccess): ?>
        <div class="alert alert-success"><?=htmlspecialchars($resendSuccess)?></div>
      <?php endif; ?>

      <!-- Manual resend error -->
      <?php if($resendError): ?>
        <div class="alert alert-danger"><?=htmlspecialchars($resendError)?></div>
      <?php endif; ?>

      <!-- Main login error + auto-resend info -->
      <?php if(!empty($error)): ?>
        <div class="alert alert-danger"><?=htmlspecialchars($error)?></div>

        <!-- Show manual resend button if needed -->
        <?php if (strpos($error, 'verification') !== false || strpos($error, 'session has expired') !== false): ?>
          <div class="alert alert-info mt-3">
            Didn't receive the email?
            <form method="POST" class="d-inline ms-2">
              <input type="hidden" name="email" value="<?=htmlspecialchars($email)?>">
              <button type="submit" name="resend_verification" class="btn btn-sm btn-light fw-bold">
                Resend Verification Email
              </button>
            </form>
          </div>
        <?php endif; ?>
      <?php endif; ?>

      <form id="myform" method="POST">
        <div class="mb-3">
          <input name="email" id="email" value="<?=htmlspecialchars($email)?>" 
                 class="form-control form-control-lg rounded-pill" placeholder="Enter your email" required>
        </div>
        <div class="mb-3">
          <input name="password" id="password" type="password" 
                 class="form-control form-control-lg rounded-pill" placeholder="Enter your password" required>
        </div>

        <!-- reCAPTCHA v2 Checkbox -->
        <div class="mb-4 text-center">
          <div class="g-recaptcha" data-sitekey="<?=$recaptcha_site_key?>"></div>
        </div>

        <button type="submit" class="btn btn-success btn-lg rounded-pill w-100 shadow-lg fw-bold">
          Login
        </button>
      </form>

      <p class="text-white mt-4 mb-0">
        New here? <a href="signup.php" class="text-white fw-bold text-decoration-underline">Create an account!</a>
      </p>
    </div>
  </div>

  <div class="col-lg-6 d-none d-lg-block p-0 right-container">
    <img src="../assets/img/carousel-4.jpg" class="right-image" alt="Happy Kids">
    <div class="right-overlay"></div>
  </div>
</div>

</body>
</html>