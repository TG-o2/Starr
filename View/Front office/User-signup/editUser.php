<?php
require_once "../../../Controller/UserController.php";
require_once "../../../Model/User.php";
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Load session variables
$userId      = $_SESSION['user_id'];
$email       = $_SESSION['email'] ?? '';
$password    = $_SESSION['password'] ?? '';
$fname       = $_SESSION['fname'] ?? '';
$lname       = $_SESSION['lname'] ?? '';
$DOB         = $_SESSION['DOB'] ?? '';
$role        = $_SESSION['role'] ?? '';
$avatar      = $_SESSION['avatar'] ?? '';
$description = $_SESSION['description'] ?? '';
$star        = $_SESSION['starPoints'] ?? 0;

$controller = new UserController();
$errors = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get POST values
    $newFname = trim($_POST['fname'] ?? '');
    $newLname = trim($_POST['lname'] ?? '');
    $newEmail = trim($_POST['email'] ?? '');
    $newPassword = $_POST['password'] ?? '';
    $newDescription = trim($_POST['description'] ?? '');
    $user_id = $_POST['user_id'];

    // Check email uniqueness if changed
    if ($newEmail !== $email) {
        $existing = $controller->getUserByEmail($newEmail);
        if ($existing) {
            $errors[] = "This email is already taken!";
        }
    }

    // Handle avatar upload
    $newAvatar = $avatar; // keep old avatar by default
    if (!empty($_FILES['avatar']['name'])) {
        $avatarName = time() . "_" . basename($_FILES['avatar']['name']);
        $uploadPath = "../assets/img/userProfile/" . $avatarName;
        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadPath)) {
            $newAvatar = $avatarName;
        } else {
            $errors[] = "Failed to upload avatar!";
        }
    }

    if (empty($errors)) {
        // Hash password if changed, else keep old
        $passwordToSave = !empty($newPassword) ? password_hash($newPassword, PASSWORD_DEFAULT) : $password;

        // Create User object
        $user = new User(
            $userId,
            $newEmail,
            $passwordToSave,
            $newFname,
            $newLname,
            $DOB,
            $role,
            $newAvatar,
            $newDescription
        );

        // Update user in DB
        $controller->updateUser($user, $user_id);

        // Update session variables
        $_SESSION['fname'] = $newFname;
        $_SESSION['lname'] = $newLname;
        $_SESSION['email'] = $newEmail;
        $_SESSION['password'] = $passwordToSave;
        $_SESSION['description'] = $newDescription;
        $_SESSION['avatar'] = $newAvatar;

        header('Location: viewProfile.php');
        exit;
    }
}

// Handle delete request
if (isset($_GET['delete']) && $_GET['delete'] == 1) {
    $controller->deleteUser($userId);
    session_destroy();
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Starr - Edit Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@600&family=Lobster+Two:wght@700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries -->
    <link href="../assets/lib/animate/animate.min.css" rel="stylesheet">
    <link href="../assets/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- CSS -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">

    <style>
        .avatar-preview {
            width: 180px;
            height: 180px;
            object-fit: cover;
            border: 8px solid var(--primary);
            transition: all 0.3s;
        }
        .avatar-preview:hover { transform: scale(1.05); }
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(254, 93, 55, 0.25);
        }
    </style>
</head>
<body>
<div class="container-xxl p-0" style="background-color: #fbefdf;">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light sticky-top px-4 px-lg-5 py-lg-0">
        <a href="index.html" class="navbar-brand d-flex align-items-center">
            <h1 class="m-0 text-primary">
                <img src="../assets/img/starr.jpg" alt="Starr Logo" style="height:45px; margin-right:8px;">
                Starr
            </h1>
        </a>

        <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav mx-auto">
                <a href="index.html" class="nav-item nav-link">Home</a>
                <a href="about.html" class="nav-item nav-link">About Us</a>
                <a href="classes.html" class="nav-item nav-link">Classes</a>
                <a href="contact.html" class="nav-item nav-link">Contact Us</a>
            </div>

            <a href="?delete=1" class="btn btn-danger rounded-pill px-3 d-none d-lg-block">
                <i class="fa fa-user me-2"></i>Delete Account
            </a>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="container-fluid py-5 mb-5 text-white text-center"
         style="background: linear-gradient(rgba(0,0,0,.4), rgba(0,0,0,.4)), url('../assets/img/carousel-1.jpg') center/cover no-repeat;">
        <div class="container py-5">
            <h1 class="display-3 mb-3 animated slideInDown">Edit Profile</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center text-uppercase">
                    <li class="breadcrumb-item"><a class="text-white" href="index.html">Home</a></li>
                    <li class="breadcrumb-item"><a class="text-white" href="viewProfile.php">Profile</a></li>
                    <li class="breadcrumb-item text-white active">Edit</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Error messages -->
    <div class="container">
        <?php if (!empty($errors)) : ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $err) echo htmlspecialchars($err) . "<br>"; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Edit Profile Form -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="bg-light rounded p-5 wow fadeInUp">

                        <form action="" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="user_id" value="<?= htmlspecialchars($userId) ?>">

                            <!-- Avatar -->
                            <div class="text-center mb-5">
                                <img src="../assets/img/userProfile/<?= htmlspecialchars($avatar) ?>" 
                                     id="avatarPreview" class="rounded-circle shadow avatar-preview" alt="Avatar">
                                <div class="mt-3">
                                    <label class="btn btn-primary rounded-pill px-4">
                                        Change Avatar
                                        <input type="file" name="avatar" accept="image/*" hidden onchange="previewAvatar(event)">
                                    </label>
                                </div>
                            </div>

                            <div class="row g-4">
                                <!-- First & Last Name -->
                                <div class="col-md-6">
                                    <label class="form-label">First Name</label>
                                    <input type="text" name="fname" class="form-control form-control-lg" value="<?= htmlspecialchars($fname) ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" name="lname" class="form-control form-control-lg" value="<?= htmlspecialchars($lname) ?>">
                                </div>

                                <!-- Email & Password -->
                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control form-control-lg" value="<?= htmlspecialchars($email) ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control form-control-lg" placeholder="Enter new password to change">
                                </div>

                                <!-- About Me -->
                                <div class="col-12">
                                    <label class="form-label">About Me</label>
                                    <textarea name="description" class="form-control" rows="5"><?= htmlspecialchars($description) ?></textarea>
                                </div>

                                <!-- Buttons -->
                                <div class="col-12 text-center mt-4">
                                    <button id="subButton" type="submit" class="btn btn-primary rounded-pill py-3 px-5">Save Changes</button>
                                    <a href="viewProfile.php" class="btn btn-outline-secondary rounded-pill py-3 px-5 ms-3">Cancel</a>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
<script src="../assets/js/editUser.js"></script>
// Avatar live preview
function previewAvatar(event) {
    const reader = new FileReader();
    reader.onload = function() {
        document.getElementById('avatarPreview').src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
}
</script>

</body>
</html>
