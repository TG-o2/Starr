<?php
require_once "../../../Controller/UserController.php";
require_once "../../../Model/User.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user1 = new User(
        null, // id / user_id is auto-increment in DB
        $_POST['email'],
        $_POST['password'],
        $_POST['fname'],
        $_POST['lname'],
        $_POST['DOB'],
        $_POST['role'],
        $_FILES['avatar']['name'] ?? null,
        $_POST['description'] ?? null
    );

    $userController1 = new UserController();
    $userController1->addUser($user1);

    header('Location: /Starr/profile.php');
    exit;
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
<body class="d-flex align-items-center">

<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-xl-5">

      <div class="text-center mb-5">
        <img src="../assets/img/starr.jpg" alt="Starr Logo" class="img-fluid rounded shadow" style="height: 110px;">
        <h2 class="text-white fw-bold mt-3">Create an Account</h2>
      </div>
 <form method="POST" enctype="multipart/form-data">
  <div class="card shadow-lg border-0 rounded-4">
    <div class="card-body p-5">

      <div class="row g-3">
        <div class="col-md-6">
          <input id="fname" name="fname" class="form-control form-control-lg rounded-pill" placeholder="First name">
        </div>
        <div class="col-md-6">
          <input id="lname" name="lname" class="form-control form-control-lg rounded-pill" placeholder="Last name">
        </div>
      </div>

      <div class="mt-3">
        <input id="email" name="email" type="email" class="form-control form-control-lg rounded-pill" placeholder="Email address">
      </div>
      <div class="mt-3">
        <input id="password" name="password" type="password" class="form-control form-control-lg rounded-pill" placeholder="Password">
      </div>
      <div class="mt-3">
        <input id="age" name="age" type="number" class="form-control form-control-lg rounded-pill" placeholder="Age">
      </div>
      
      <div class="mt-3">
        <select id="person" name="person" class="form-select form-select-lg rounded-pill">
          <option value="" selected disabled>Select type</option>
          <option>Teacher</option>
          <option>Parent</option>
          <option>Kid</option>
        </select>
      </div>

      <div class="d-grid mt-4">
        <button id="subButton" type="submit" class="btn btn-success btn-lg rounded-pill shadow fw-bold">Sign Up</button>
      </div>

    </div>
  </div>
</form>
<p class="text-center mt-4 mb-0">
  Already have an account? <a href="login.html" class="text-success fw-bold">Login here!</a>
</p>

        </div>
      </div>
    </div>
  </div>
</div>

<script src="../assets/js/createAccountJS.js"></script>
<script src="../js/main.js"></script>
</body>
</html>