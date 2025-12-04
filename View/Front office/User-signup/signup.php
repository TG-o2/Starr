<?php
require_once "../../../Controller/UserController.php";
require_once "../../../Model/User.php";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $avatarName = null;
   if (!empty($_FILES['avatar']['name'])) {
    $avatarName = time() . "_" . $_FILES['avatar']['name'];

    $uploadPath = "../assets/img/userProfile/" . $avatarName;

    move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadPath);

    
}


$user = new User(
    uniqid("USR_"),           
    $_POST['email'],
    $_POST['password'],
    $_POST['fname'],
    $_POST['lname'],
    $_POST['DOB'],
    $_POST['role'],
    $avatarName,
    $_POST['description'],
    0,       
    0         
);

    $controller = new UserController();
    $controller->addUserWithVerification($user);

    header('Location: login.php');
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
  <div class="row g-3">
    <div class="col-md-6">
      <input name="fname" class="form-control form-control-lg rounded-pill" placeholder="First name">
    </div>
    <div class="col-md-6">
      <input name="lname" class="form-control form-control-lg rounded-pill" placeholder="Last name">
    </div>
  </div>

  <div class="mt-3">
    <input name="email" class="form-control form-control-lg rounded-pill" placeholder="Email" >
  </div>

  <div class="mt-3">
    <input name="password" type="password" class="form-control form-control-lg rounded-pill" placeholder="Password" >
  </div>

  <div class="mt-3">
    <input name="DOB" type="date" class="form-control form-control-lg rounded-pill" >
  </div>

  <div class="mt-3">
    <select name="role" class="form-select form-select-lg rounded-pill" >
      <option value="" selected disabled>Select role</option>
      <option value="Teacher">Teacher</option>
      <option value="Parent">Parent</option>
      <option value="Kid">Kid</option>
    </select>
  </div>

  <div class="mt-3">
    <input name="avatar" type="file" class="form-control form-control-lg rounded-pill">
  </div>

  <div class="mt-3">
    <textarea name="description" class="form-control rounded-4" placeholder="Describe yourself"></textarea>
  </div>

  <div class="d-grid mt-4">
    <button 
    type="submit" 
    id="subButton" 
    class="btn btn-success btn-lg rounded-pill shadow fw-bold">
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
  </div>
</div>

<script src="../assets/js/createAccountJS.js"></script>
<script src="../js/main.js"></script>
</body>
</html>