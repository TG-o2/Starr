<?php
require_once "../../../config/config.php";
require_once "../../../Controller/UserController.php";
require_once "../../../Model/User.php";

$controller = new UserController();
$message = "";

// Show success after redirect
/*if (isset($_GET['success'])) {
    $message = "Admin account created successfully!";
}*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $avatarName = null;
    if (!empty($_FILES['profile_image']['name']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $avatarName = time() . "_" . basename($_FILES['profile_image']['name']);
        $uploadPath = "../../../assets/img/userProfile/" . $avatarName;
        if (!is_dir("../../../assets/img/userProfile")) {
            mkdir("../../../assets/img/userProfile", 0777, true);
        }
        move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadPath);
    }

    $userId   = uniqid("USR_");
    $email    = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $fname    = $_POST['fname'] ?? '';
    $lname    = $_POST['lname'] ?? '';
    $DOB      = date('Y-m-d');

    if ($controller->getUserByEmail($email)) {
        $message = "Email already exists!";
    } else {
        $user = new User($userId, $email, $password, $fname, $lname, $DOB, "admin", $avatarName, "", 1, 0);

        try {
            $controller->addUser($user);
           header('Location: loginAdmin.php');
                exit;
        } catch (Exception $e) {
            $message = "Error: " . $e->getMessage();
        }
    }
    header('Location: loginAdmin.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Admin Account</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,700" rel="stylesheet">
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../Front office/assets/css/style.css" rel="stylesheet">
    <style>
        .bg-register-image {
            background: url('../assets/img/several-students-having-fun-carousel.jpg') center/cover;
        }
    </style>
</head>
<body class="bg-gradient-primary">

<div class="container">
    <?php if (!empty($message)): ?>
        <div class="alert <?= strpos($message, 'successfully') !== false ? 'alert-success' : 'alert-danger' ?> text-center mt-4">
            <strong><?= htmlspecialchars($message) ?></strong>
        </div>
    <?php endif; ?>

    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <div class="row">
                <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                <div class="col-lg-7">
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">Create Admin Account</h1>
                        </div>

                        <form class="user" method="POST" enctype="multipart/form-data">
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="text" name="fname" class="form-control form-control-user" placeholder="First Name" required>
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" name="lname" class="form-control form-control-user" placeholder="Last Name" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <input type="email" name="email" class="form-control form-control-user" placeholder="Email Address" required>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="password" name="password" class="form-control form-control-user" placeholder="Password" required>
                                </div>
                                <div class="col-sm-6">
                                    <div class="custom-file mt-2">
                                        <input type="file" name="profile_image" class="custom-file-input" id="customFile" required>
                                        <label class="custom-file-label" for="customFile">Choose profile image</label>
                                    </div>
                                </div>
                            </div>

                            <button id="subButton" type="submit" class="btn btn-primary btn-user btn-block">
                                Create Admin Account
                            </button>
                        </form>

                        <hr>
                        <div class="text-center">
                            <a class="small" href="loginAdmin.php">Already have an account? Login!</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });
</script>
<script src="../assets/js/createAdminUser.js"></script>
</body>
</html>