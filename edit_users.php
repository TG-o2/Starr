<?php
require_once "../../../Controller/UserController.php";

$controller = new UserController();
$message = "";

// Get user ID from URL
if (!isset($_GET['id'])) {
    die("User ID missing.");
}
$user_id = $_GET['id'];

// Fetch current user data
$userData = null;
$allUsers = $controller->readUsers();
foreach ($allUsers as $u) {
    if ($u['user_id'] == $user_id) {
        $userData = $u;
        break;
    }
}
if (!$userData) {
    die("User not found.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $fname = $_POST['fname'] ?? '';
    $lname = $_POST['lname'] ?? '';
    $DOB = $_POST['DOB'] ?? '';
    $role = $_POST['role'] ?? '';
    $description = $_POST['description'] ?? '';

    // Handle avatar upload
    $avatar = $userData['avatar']; // keep old avatar by default
    if (!empty($_FILES['avatar']['name'])) {
        $avatarName = time() . "_" . basename($_FILES['avatar']['name']);
        $uploadPath = "../../assets/img/userProfile/" . $avatarName;
        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadPath)) {
            $avatar = $avatarName;
        } else {
            $message = "Failed to upload new avatar.";
        }
    }

    // Optional password update
    $password = $_POST['password'] ?? '';

    // Create User object
    $userObj = new User(
        $user_id,
        $email,
        $password, // empty password will keep old one
        $fname,
        $lname,
        $DOB,
        $role,
        $avatar,
        $description
    );

    try {
        $controller->updateUser($userObj, $user_id);
        $message = "User updated successfully!";
        // Reload updated user data
        $userData = (array)$controller->getUserByEmail($email);
    } catch (Exception $e) {
        $message = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Edit User</title>

<link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
<link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top">

<div id="wrapper">
    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
        <!-- Add your sidebar HTML here, same as in your template -->
    </ul>
    <!-- End Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
        <div class="container-fluid mt-4">

            <h1 class="h3 mb-4 text-gray-800">Edit User</h1>

            <?php if ($message): ?>
                <div class="alert alert-info"><?= $message ?></div>
            <?php endif; ?>

            <div class="card shadow">
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($userData['email']) ?>" required>
                        </div>

                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" name="fname" class="form-control" value="<?= htmlspecialchars($userData['fname']) ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" name="lname" class="form-control" value="<?= htmlspecialchars($userData['lname']) ?>" required>
                        </div>

                        <div class="form-group">
                            <label>DOB</label>
                            <input type="date" name="DOB" class="form-control" value="<?= $userData['DOB'] ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Role</label>
                            <select name="role" class="form-control" required>
                                <option value="admin" <?= $userData['role']=='admin'?'selected':'' ?>>Admin</option>
                                <option value="kid" <?= $userData['role']=='kid'?'selected':'' ?>>Kid</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control"><?= htmlspecialchars($userData['description']) ?></textarea>
                        </div>

                        <div class="form-group">
                            <label>Password (leave blank to keep old)</label>
                            <input type="password" name="password" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Avatar</label><br>
                            <img src="../../assets/img/userProfile/<?= $userData['avatar'] ?>" width="80" class="rounded mb-2"><br>
                            <input type="file" name="avatar" class="form-control-file">
                        </div>

                        <button type="submit" class="btn btn-primary">Update User</button>
                        <a href="list_users.php" class="btn btn-secondary">Back to list</a>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="../assets/vendor/jquery/jquery.min.js"></script>
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="../assets/js/sb-admin-2.min.js"></script>
</body>
</html>
