<?php
require_once "../../../Controller/UserController.php";

$controller = new UserController();

// Handle delete
if (isset($_GET['delete'])) {
    $deleteId = $_GET['delete'];
    $controller->deleteUser($deleteId);
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

    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../Front office/assets/css/style.css" rel="stylesheet">
</head>
<body id="page-top">

<div id="wrapper">

    <!-- Sidebar (unchanged) -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
            <div class="sidebar-brand-icon rotate-n-15">
                <i class="fas fa-laugh-wink"></i>
            </div>
            <div class="sidebar-brand-text mx-3">Admin Starr</div>
        </a>
        <hr class="sidebar-divider my-0">
        <li class="nav-item active"><a class="nav-link"><span>Users List</span></a></li>
        <hr class="sidebar-divider">
        <div class="sidebar-heading">Work</div>
        <li class="nav-item"><a class="nav-link" href="Review-list.html"><i class="fas fa-fw fa-chart-area"></i><span>View reports</span></a></li>
        <li class="nav-item"><a class="nav-link" href="../Admin Dashboard/Dashboard.html"><i class="fas fa-fw fa-tachometer-alt"></i><span>Admin Dashboard</span></a></li>
        <hr class="sidebar-divider d-none d-md-block">
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>
    </ul>
    <!-- End Sidebar -->

    <div id="content-wrapper" class="d-flex flex-column">
        <div class="container-fluid mt-4">

            <h1 class="h3 mb-4 text-gray-800">Users List</h1>

            <?php if (isset($_GET['updated'])): ?>
                <div class="alert alert-success">User updated successfully!</div>
            <?php endif; ?>
            <?php if (isset($_GET['deleted'])): ?>
                <div class="alert alert-warning">User deleted successfully!</div>
            <?php endif; ?>
            <?php if (isset($_GET['msg'])): ?>
                <div class="alert alert-info"><?= htmlspecialchars($_GET['msg']) ?></div>
            <?php endif; ?>

            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th width="180">Actions</th>
                                    <th>ID</th>
                                    <th>Avatar</th>
                                    <th>Email</th>
                                    <th>Full Name</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Approval</th>
                                    <th>Ban</th>
                                    <th>Star Points</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $u): ?>
                                    <tr>
                                        <!-- ACTIONS -->
                                        <td>
                                            <a href="edit_users.php?id=<?= $u['user_id'] ?>" class="btn btn-sm btn-warning mb-1" title="Edit">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a href="list_users.php?delete=<?= $u['user_id'] ?>" 
                                               onclick="return confirm('Delete this user permanently?');"
                                               class="btn btn-sm btn-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>

                                        <td><?= htmlspecialchars($u['user_id']) ?></td>

                                        <td>
                                            <img src="../../Front office/assets/img/userProfile/<?= htmlspecialchars($u['avatar'] ?? 'default.png') ?>" 
                                                 width="50" height="50" class="rounded-circle" alt="Avatar">
                                        </td>

                                        <td><?= htmlspecialchars($u['email']) ?></td>
                                        <td><?= htmlspecialchars($u['fname'] . ' ' . $u['lname']) ?></td>
                                        <td><span class="badge badge-info"><?= ucfirst($u['role']) ?></span></td>

                                        <!-- ACCOUNT STATUS -->
                                        <td>
                                            <?php if ($u['is_banned']): ?>
                                                <span class="badge badge-danger">BANNED</span>
                                            <?php elseif (!$u['is_approved']): ?>
                                                <span class="badge badge-warning text-dark">PENDING</span>
                                            <?php else: ?>
                                                <span class="badge badge-success">ACTIVE</span>
                                            <?php endif; ?>
                                        </td>

                                        <!-- APPROVAL BUTTON -->
                                        <td>
                                            <?php if ($u['is_approved']): ?>
                                                <a href="approve_user.php?id=<?= $u['user_id'] ?>&action=unapprove" 
                                                   class="btn btn-sm btn-outline-secondary" 
                                                   title="Revoke approval">
                                                    Approved
                                                </a>
                                            <?php else: ?>
                                                <a href="approve_user.php?id=<?= $u['user_id'] ?>" 
                                                   class="btn btn-sm btn-success" 
                                                   onclick="return confirm('Approve this user? They will be able to log in.');">
                                                    Approve
                                                </a>
                                            <?php endif; ?>
                                        </td>

                                        <!-- BAN BUTTON -->
                                        <td>
                                            <?php if ($u['is_banned']): ?>
                                                <a href="toggle_ban.php?id=<?= $u['user_id'] ?>&action=unban" 
                                                   class="btn btn-sm btn-warning">
                                                    Unban
                                                </a>
                                            <?php else: ?>
                                                <a href="toggle_ban.php?id=<?= $u['user_id'] ?>&action=ban" 
                                                   class="btn btn-sm btn-danger"
                                                   onclick="return confirm('Ban this user? They will NOT be able to log in.');">
                                                    Ban
                                                </a>
                                            <?php endif; ?>
                                        </td>

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
</div>

<!-- Scripts -->
<script src="../assets/vendor/jquery/jquery.min.js"></script>
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="../assets/js/sb-admin-2.min.js"></script>
</body>
</html>