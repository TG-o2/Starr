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
    $is_approved = isset($_POST['is_approved']) ? 1 : 0;
    $is_banned = isset($_POST['is_banned']) ? 1 : 0;

    // Handle avatar upload
    $avatar = $userData['avatar'];
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

    // Create User object with new fields
    $userObj = new User(
        $user_id,
        $email,
        $password,
        $fname,
        $lname,
        $DOB,
        $role,
        $avatar,
        $description,
        $is_approved,
        $is_banned
    );

    try {
        $controller->updateUser($userObj, $user_id);
        $message = "User updated successfully!";
        // Refresh data
        foreach ($controller->readUsers() as $u) {
            if ($u['user_id'] == $user_id) {
                $userData = $u;
                break;
            }
        }
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
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

    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../Front office/assets/css/style.css" rel="stylesheet">
</head>
<body id="page-top">

<div id="wrapper">
    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
        <!-- Your sidebar here -->
    </ul>

    <div id="content-wrapper" class="d-flex flex-column">
        <div class="container-fluid mt-4">

            <h1 class="h3 mb-4 text-gray-800">Edit User</h1>

            <?php if ($message): ?>
                <div class="alert <?= strpos($message, 'successfully') !== false ? 'alert-success' : 'alert-danger' ?>">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">User ID: <?= htmlspecialchars($userData['user_id']) ?></h6>
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
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
                                    <label>Date of Birth</label>
                                    <input type="date" name="DOB" class="form-control" value="<?= $userData['DOB'] ?>" required>
                                </div>

                                <div class="form-group">
                                    <label>Role</label>
                                    <select name="role" class="form-control" required>
                                        <option value="admin" <?= $userData['role']=='admin' ? 'selected' : '' ?>>Admin</option>
                                        <option value="kid" <?= $userData['role']=='kid' ? 'selected' : '' ?>>Kid</option>
                                        <option value="Teacher" <?= $userData['role']=='Teacher' ? 'selected' : '' ?>>Teacher</option>
                                        <option value="Parent" <?= $userData['role']=='Parent' ? 'selected' : '' ?>>Parent</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($userData['description'] ?? '') ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label>New Password (leave blank to keep current)</label>
                                    <input type="password" name="password" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Current Avatar</label><br>
                                    <img src="../../assets/img/userProfile/<?= htmlspecialchars($userData['avatar'] ?? 'default-avatar.png') ?>" 
                                         width="120" class="img-thumbnail rounded-circle mb-3" alt="Avatar">
                                    <br>
                                    <input type="file" name="avatar" class="form-control-file" accept="image/*">
                                    <small class="text-muted">Leave empty to keep current avatar</small>
                                </div>

                                <!-- Approval Status -->
                                <div class="form-group mt-4">
                                    <label class="font-weight-bold text-primary">Account Approval</label>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="approveSwitch" name="is_approved" 
                                               <?= $userData['is_approved'] ? 'checked' : '' ?>>
                                        <label class="custom-control-label" for="approveSwitch">
                                            <?php if ($userData['is_approved']): ?>
                                                <span class="text-success font-weight-bold">Approved - User can log in</span>
                                            <?php else: ?>
                                                <span class="text-warning font-weight-bold">Pending - Waiting for approval</span>
                                            <?php endif; ?>
                                        </label>
                                    </div>
                                </div>

                                <!-- Ban Status -->
                                <div class="form-group mt-4">
                                    <label class="font-weight-bold text-danger">Ban Status</label>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="banSwitch" name="is_banned" 
                                               <?= $userData['is_banned'] ? 'checked' : '' ?>>
                                        <label class="custom-control-label" for="banSwitch">
                                            <?php if ($userData['is_banned']): ?>
                                                <span class="text-danger font-weight-bold">BANNED - Cannot log in</span>
                                            <?php else: ?>
                                                <span class="text-success font-weight-bold">Active - Normal access</span>
                                            <?php endif; ?>
                                        </label>
                                    </div>
                                    <?php if ($userData['is_banned']): ?>
                                        <small class="text-danger">This user is currently banned from logging in.</small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="text-center">
                            <button type="submit" class="btn btn-lg btn-primary px-5">
                                <i class="fas fa-save"></i> Update User
                            </button>
                            <a href="list_users.php" class="btn btn-secondary btn-lg px-5">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="../assets/vendor/jquery/jquery.min.js"></script>
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/sb-admin-2.min.js"></script>
</body>
</html>