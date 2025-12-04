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
            header('Location: ../Admin Dashboard/Dashboard.html');
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

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Admin Back Office - Login</title>

  <!-- Custom fonts for this template -->
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../Front office/assets/css/style.css" rel="stylesheet">
    <style>
     /* Optional: different image for login page */
    .bg-login-image {
      background: url('../../Front\ office/assets/img/starr.jpg');
      background-position: center;
      background-size: cover;
    }
    </style>
</head>

<body class="bg-gradient-primary">

  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-10 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
              <div class="col-lg-6">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Admin Login</h1>
                  </div>
                  <form class="user" method="POST">
                     <?php if (!empty($error)): ?>
                      <div class="alert alert-danger text-center">
                        <strong><?php echo htmlspecialchars($error); endif; ?></strong>
                      </div>
                    <div class="form-group">
                      <input name="email" type="email" class="form-control form-control-user" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Enter Email Address...">
                    </div>
                    <div class="form-group">
                      <input type="password" name="password" class="form-control form-control-user" id="exampleInputPassword" placeholder="Password">
                    </div>
                    <div class="form-group">
                      <div class="custom-control custom-checkbox small">
                        <input type="checkbox" class="custom-control-input" id="customCheck">
                        <label class="custom-control-label" for="customCheck">Remember Me</label>
                      </div>
                    </div>
                            <button id="subButton" type="submit" class="btn btn-primary btn-user btn-block">
                               Login
                            </button>
               
                  </form>
                  <hr>
                  <div class="text-center">
                    <a class="small" href="forgot-password.html">Forgot Password?</a>
                  </div>
                  <div class="text-center">
                    <a class="small" href="createAdminUser.php">Create a New Admin Account!</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>

  <!-- Bootstrap core JavaScript -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Custom scripts for all pages -->
  <script src="../assets/js/sb-admin-2.min.js"></script>
  <script src="../assets/js/loginAdmin.js"></script>

</body>

</html>