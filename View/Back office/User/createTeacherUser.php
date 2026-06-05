<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . "/../../../config/config.php";
require_once __DIR__ . "/../../../Controller/UserController.php";
require_once __DIR__ . "/../../../Model/User.php";

$controller = new UserController();
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $fname = trim($_POST['fname'] ?? '');
    $lname = trim($_POST['lname'] ?? '');

    if (empty($fname)) {
        $message = "First name is required!";
    } elseif (empty($lname)) {
        $message = "Last name is required!";
    } elseif (empty($email)) {
        $message = "Email is required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format!";
    } elseif (empty($password)) {
        $message = "Password is required!";
    } elseif (strlen($password) < 6) {
        $message = "Password must be at least 6 characters!";
    } elseif (empty($_FILES['profile_image']['name'])) {
        $message = "Profile image is required!";
    } else {
        if ($controller->getUserByEmail($email)) {
            $message = "Email already exists!";
        } else {
            $avatarName = null;
            if ($_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
                $avatarName = time() . "_" . basename($_FILES['profile_image']['name']);
                $uploadPath = "../../../assets/img/userProfile/" . $avatarName;
                if (!is_dir("../../../assets/img/userProfile")) {
                    mkdir("../../../assets/img/userProfile", 0777, true);
                }
                if (!move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadPath)) {
                    $message = "Failed to upload profile image!";
                }
            } else {
                $message = "Error uploading file!";
            }

            if (empty($message)) {
                $userId = uniqid("USR_");
                $DOB = date('Y-m-d');
                $username = strtolower(explode('@', $email)[0]);

                $user = new User(
                    $userId,
                    $password,
                    $fname,
                    $lname,
                    $DOB,
                    null,
                    "",
                    $username,
                    $email,
                    "teacher",
                    $avatarName,
                    1,
                    0,
                    1
                );

                try {
                    $controller->addUser($user);
                    header('Location: loginAdmin.php');
                    exit;
                } catch (Exception $e) {
                    $message = "Error creating account: " . $e->getMessage();
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Teacher Account</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,700" rel="stylesheet">
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../Front office/assets/css/style.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0f5132 0%, #14532d 48%, #052e1b 100%);
        }

        .bg-register-image {
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, rgba(7, 71, 41, 0.9), rgba(5, 46, 27, 0.96)), url('../assets/img/several-students-having-fun-carousel.jpg') center/cover;
            min-height: 100%;
        }

        .bg-register-image::before {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top, rgba(255, 255, 255, 0.12), transparent 45%);
        }

        .image-placeholder-card {
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

        .image-preview-frame {
            width: 100%;
            aspect-ratio: 1 / 1;
            border-radius: 20px;
            overflow: hidden;
            margin-bottom: 16px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.18);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .image-preview-frame img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: none;
        }

        .image-preview-empty {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 20px;
            color: rgba(255, 255, 255, 0.9);
        }

        .image-placeholder-icon {
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

        .image-placeholder-title {
            font-size: 1.05rem;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .image-placeholder-text {
            font-size: 0.875rem;
            line-height: 1.45;
            color: rgba(255, 255, 255, 0.82);
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
                <div class="col-lg-5 d-none d-lg-block bg-register-image">
                    <div class="image-placeholder-card">
                        <div class="image-preview-frame">
                            <img id="profileImagePreview" alt="Selected profile image preview">
                            <div id="profileImagePlaceholder" class="image-preview-empty">
                                <div class="image-placeholder-icon" aria-hidden="true">
                                    <svg width="42" height="42" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4 6.75A2.75 2.75 0 0 1 6.75 4h10.5A2.75 2.75 0 0 1 20 6.75v10.5A2.75 2.75 0 0 1 17.25 20H6.75A2.75 2.75 0 0 1 4 17.25V6.75Z" stroke="white" stroke-width="1.6"/>
                                        <path d="M8.25 10.25a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z" fill="white"/>
                                        <path d="M5.5 16.5 9.2 12.8c.45-.45 1.15-.48 1.64-.08l1.57 1.26c.44.35 1.07.36 1.52.03l3.56-2.65 2.01 2.06v3.08A1.25 1.25 0 0 1 18.25 18H5.75A1.25 1.25 0 0 1 4.5 16.75v-.25c0-.38.15-.74.42-1Z" fill="white" fill-opacity="0.9"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="image-placeholder-title">Teacher Profile Preview</div>
                        <div class="image-placeholder-text">Upload a profile image to give the teacher account form the same polished feel as the admin version.</div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">Create Teacher Account</h1>
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

                            <button id="subButton" type="submit" class="btn btn-success btn-user btn-block">
                                Create Teacher Account
                            </button>
                        </form>

                        <hr>
                        <div class="text-center">
                            <a class="small" href="loginAdmin.php">Back to Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    var profilePreviewUrl = null;

    $(".custom-file-input").on("change", function() {
        var file = this.files && this.files[0];
        var fileName = $(this).val().split("\\").pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);

        if (!file) {
            $('#profileImagePreview').hide().attr('src', '');
            $('#profileImagePlaceholder').show();
            return;
        }

        if (profilePreviewUrl) {
            URL.revokeObjectURL(profilePreviewUrl);
        }

        profilePreviewUrl = URL.createObjectURL(file);
        $('#profileImagePreview').attr('src', profilePreviewUrl).show();
        $('#profileImagePlaceholder').hide();
    });
</script>
<script src="../assets/js/createAdminUser.js"></script>
