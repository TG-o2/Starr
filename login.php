<?php
session_start();
require_once "../../../Controller/UserController.php";
require_once "../../../Model/User.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $controller = new UserController();
    $user = $controller->getUserByEmail($email);

    if ($user) {
        //$password = password_hash($password, PASSWORD_DEFAULT);
        if (password_verify($password, $user['password'])) { 
             $_SESSION['user_id'] = $user['user_id'];
             $_SESSION['email'] = $user['email'];
             $_SESSION['password'] = $user['password'];
             $_SESSION['fname'] = $user['fname'];
             $_SESSION['lname'] = $user['lname'];
             $_SESSION['DOB'] = $user['DOB'];
             $_SESSION['role'] = $user['role'];
             $_SESSION['avatar'] = $user['avatar'];
             $_SESSION['description'] = $user['description'];
             $_SESSION['starPoints'] = $user['starPoints'];    
            header('Location: ../template/index.html');
            exit;
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "Email not found!";
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

      <?php if(!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
      <?php endif; ?>

      <form id="myform" method="POST" >
        <div class="mb-3">
          <input name="email" id="email" class="form-control form-control-lg rounded-pill" placeholder="Enter your email" >
        </div>
        <div class="mb-4">
          <input name="password" id="password" type="password" class="form-control form-control-lg rounded-pill" placeholder="Enter your password" >
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
<!--<script src="../assets/js/login.js"></script>-->
</body>
</html>
