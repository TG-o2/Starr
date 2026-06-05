<?php
session_start();
require_once __DIR__ . "/../../../Controller/UserController.php";
require_once __DIR__ . "/../../../Model/User.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $controller = new UserController();
    $user = $controller->getUserByEmail($email);

    if ($user) {
        $role = strtolower((string) ($user['role'] ?? ''));

        if ($role !== 'teacher') {
            $error = 'This account is not assigned to the teacher dashboard.';
        } elseif ((int)($user['is_banned'] ?? 0) === 1) {
            $error = 'This account is banned.';
        } elseif ((int)($user['is_approved'] ?? 1) !== 1) {
            $error = 'This account is not approved yet.';
        } elseif (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['fname'] = $user['fname'];
            $_SESSION['lname'] = $user['lname'];
            $_SESSION['DOB'] = $user['DOB'];
            $_SESSION['role'] = $role;
            $_SESSION['avatar'] = $user['avatar'] ?? 'default-avatar.png';
            $_SESSION['description'] = $user['description'] ?? '';
            $_SESSION['starPoints'] = $user['starPoints'] ?? 0;

            header('Location: Dashboard.php');
            exit;
        } else {
            $error = 'Incorrect password!';
        }
    } else {
        $error = 'Email not found!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Teacher Dashboard Login">
    <meta name="author" content="">

    <title>Teacher Login - Starr</title>

    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../Front office/assets/css/style.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #0f5132 0%, #14532d 48%, #052e1b 100%);
        }

        .bg-login-image {
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, rgba(7, 71, 41, 0.9), rgba(5, 46, 27, 0.96)), url('../../Front office/assets/img/starr.jpg') center/cover;
            min-height: 100%;
        }

        .bg-login-image::before {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top, rgba(255, 255, 255, 0.12), transparent 45%);
        }

        .login-badge-card {
            position: absolute;
            inset: 50% auto auto 50%;
            transform: translate(-50%, -50%);
            width: min(280px, calc(100% - 48px));
            padding: 28px 24px;
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.22);
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(10px);
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.2);
            text-align: center;
            color: #fff;
        }

        .login-badge-icon {
            width: 84px;
            height: 84px;
            margin: 0 auto 16px;
            border-radius: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.14);
            border: 1px dashed rgba(255, 255, 255, 0.4);
        }

        .login-badge-title {
            font-size: 1.05rem;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .login-badge-text {
            font-size: 0.875rem;
            line-height: 1.45;
            color: rgba(255, 255, 255, 0.82);
        }
    </style>
</head>

<body class="bg-gradient-success">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12 col-md-9">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-lg-6 d-none d-lg-block bg-login-image">
                            <div class="login-badge-card">
                                <div class="login-badge-icon" aria-hidden="true">
                                    <svg width="42" height="42" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 13.5a5 5 0 1 0 0-10 5 5 0 0 0 0 10Z" stroke="white" stroke-width="1.6"/>
                                        <path d="M4.5 20.25c0-3.45 3.37-6.25 7.5-6.25s7.5 2.8 7.5 6.25" stroke="white" stroke-width="1.6" stroke-linecap="round"/>
                                    </svg>
                                </div>
                                <div class="login-badge-title">Teacher Login</div>
                                <div class="login-badge-text">Enter your teacher account to access the dashboard, manage content, and review your activity.</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Teacher Login</h1>
                                </div>

                                <form class="user" method="POST">
                                    <?php if (!empty($error)): ?>
                                        <div class="alert alert-danger text-center">
                                            <strong><?php echo htmlspecialchars($error); ?></strong>
                                        </div>
                                    <?php endif; ?>

                                    <div class="form-group">
                                        <input name="email" type="email" class="form-control form-control-user" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Enter Email Address..." required>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" name="password" class="form-control form-control-user" id="exampleInputPassword" placeholder="Password" required>
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox small">
                                            <input type="checkbox" class="custom-control-input" id="customCheck">
                                            <label class="custom-control-label" for="customCheck">Remember Me</label>
                                        </div>
                                    </div>
                                    <button id="subButton" type="submit" class="btn btn-success btn-user btn-block">
                                        Login
                                    </button>
                                </form>

                                <hr>
                                <div class="text-center">
                                    <a class="small" href="loginAdmin.php">Admin Login</a>
                                </div>
                                <div class="text-center">
                                    <a class="small" href="../User/createTeacherUser.php">Create a New Teacher Account!</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/sb-admin-2.min.js"></script>
</body>

</html>
