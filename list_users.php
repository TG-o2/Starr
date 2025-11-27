<?php
require_once "../../../Controller/UserController.php";

$controller = new UserController();


if (isset($_GET['delete'])) {
    $deleteId = $_GET['delete'];
    $controller->deleteUser($deleteId);

    // Redirect to avoid deleting twice on refresh
    header("Location: list_users.php?deleted=1");
    exit;
}


$users = $controller->readUsers();
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Users List</title>

    <!-- Custom fonts -->
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">

    <!-- Custom styles -->
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body id="page-top">

<div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
            <div class="sidebar-brand-icon rotate-n-15">
                <i class="fas fa-laugh-wink"></i>
            </div>
            <div class="sidebar-brand-text mx-3">Admin Starr</div>
        </a>

        <hr class="sidebar-divider my-0">

        <li class="nav-item active">
            <a class="nav-link">
                <span>Users List</span>
            </a>
        </li>

        <hr class="sidebar-divider">

        <div class="sidebar-heading">Work</div>

        <li class="nav-item">
            <a class="nav-link" href="Review-list.html">
                <i class="fas fa-fw fa-chart-area"></i>
                <span>View reports</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="../Admin Dashboard/Dashboard.html">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Admin Dashboard</span>
            </a>
        </li>

        <hr class="sidebar-divider d-none d-md-block">

        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>

    </ul>
    <!-- End Sidebar -->


    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <div class="container-fluid mt-4">

            <h1 class="h3 mb-4 text-gray-800">Users List</h1>

            <?php if (isset($_GET['updated'])): ?>
                <div class="alert alert-success">User updated successfully!</div>
            <?php endif; ?>

            <?php if (isset($_GET['deleted'])): ?>
                <div class="alert alert-warning">User deleted successfully!</div>
            <?php endif; ?>

            <div class="card shadow">
                <div class="card-body">

                    <table class="table table-bordered table-striped">
                        <thead class="bg-primary text-white">
                        <tr>
                            <th>Actions</th>
                            <th>ID</th>
                            <th>Avatar</th>
                            <th>Email</th>
                            <th>Full Name</th>
                            <th>DOB</th>
                            <th>Role</th>
                            <th>Description</th>
                            <th>Star Points</th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php foreach ($users as $u): ?>
                            <tr>

                                <!-- ACTION BUTTONS -->
                                <td>
                                    <a href="edit_users.php?id=<?= $u['user_id'] ?>"
                                       class="btn btn-sm btn-warning mb-1">Edit</a>

                                    <a href="list_users.php?delete=<?= $u['user_id'] ?>"
                                       onclick="return confirm('Delete this user?');"
                                       class="btn btn-sm btn-danger">Delete</a>
                                </td>

                                <td><?= $u['user_id'] ?></td>

                                <td>
                                    <img src="../../Front office/assets/img/userProfile/<?= $u['avatar'] ?>"
                                         width="60" height="60" class="rounded">
                                </td>

                                <td><?= $u['email'] ?></td>

                                <td><?= $u['fname'] . ' ' . $u['lname'] ?></td>

                                <td><?= $u['DOB'] ?></td>

                                <td><?= ucfirst($u['role']) ?></td>

                                <td><?= htmlspecialchars($u['description']) ?></td>

                                <td><?= $u['starPoints'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>

                    </table>

                </div>
            </div>

        </div>

    </div>
</div>

<!-- Scroll to Top -->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Ready to Leave?</h5>
            <button class="close" type="button" data-dismiss="modal"><span>×</span></button>
        </div>
        <div class="modal-body">Select "Logout" to end your session.</div>
        <div class="modal-footer">
            <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <a class="btn btn-primary" href="login.html">Logout</a>
        </div>
    </div></div>
</div>

<!-- Scripts -->
<script src="../assets/vendor/jquery/jquery.min.js"></script>
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="../assets/js/sb-admin-2.min.js"></script>

</body>
</html>
