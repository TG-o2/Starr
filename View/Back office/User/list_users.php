<?php
require_once "../../../Controller/UserController.php";

$controller = new UserController();

// Handle delete + keep filters
if (isset($_GET['delete'])) {
    $deleteId = $_GET['delete'];
    $controller->deleteUser($deleteId);
    
    $queryString = http_build_query(array_diff_key($_GET, ['delete' => '']));
    header("Location: list_users.php?" . $queryString);
    exit;
}

// Define filters (safe from undefined warnings)
$filters = [
    'search'   => trim($_GET['search'] ?? ''),
    'role'     => $_GET['role'] ?? '',
    'status'   => $_GET['status'] ?? '',
    'approved' => $_GET['approved'] ?? ''
];

// Extract for easier use in HTML (this eliminates all undefined variable warnings)
$search   = $filters['search'];
$role     = $filters['role'];
$status   = $filters['status'];
$approved = $filters['approved'];

// Fetch users with filters
$users = $controller->searchAndFilterUsers($filters);
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

    <!-- Sidebar -->
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
        <li class="nav-item"><a class="nav-link" href="Review-list.html">View reports</a></li>
        <li class="nav-item"><a class="nav-link" href="../Admin Dashboard/Dashboard.html">Admin Dashboard</a></li>
        <hr class="sidebar-divider d-none d-md-block">
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>
    </ul>

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

            <!-- SEARCH & FILTER FORM -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">Search & Filters</h6>
                </div>
                <div class="card-body">
                    <form method="GET" action="list_users.php" class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Search by ID, Email or Name..." 
                                   value="<?= htmlspecialchars($search) ?>">
                        </div>
                        <div class="col-md-2">
                            <select name="role" class="form-control">
                                <option value="">All Roles</option>
                                <option value="user" <?= $role === 'user' ? 'selected' : '' ?>>User</option>
                                <option value="admin" <?= $role === 'admin' ? 'selected' : '' ?>>Admin</option>
                                <option value="moderator" <?= $role === 'moderator' ? 'selected' : '' ?>>Moderator</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-control">
                                <option value="">All Status</option>
                                <option value="active" <?= $status === 'active' ? 'selected' : '' ?>>Active</option>
                                <option value="pending" <?= $status === 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="banned" <?= $status === 'banned' ? 'selected' : '' ?>>Banned</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="approved" class="form-control">
                                <option value="">Approval Status</option>
                                <option value="1" <?= $approved === '1' ? 'selected' : '' ?>>Approved</option>
                                <option value="0" <?= $approved === '0' ? 'selected' : '' ?>>Not Approved</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Search</button>
                        </div>
                    </form>

                    <?php if ($search || $role || $status !== '' || $approved !== ''): ?>
                        <div class="mt-3">
                            <a href="list_users.php" class="btn btn-outline-secondary btn-sm">Clear All Filters</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Users Table -->
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
                                <?php if (empty($users)): ?>
                                    <tr>
                                        <td colspan="10" class="text-center text-muted py-5">
                                            <i class="fas fa-inbox fa-3x mb-3"></i><br>
                                            No users found matching your criteria.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($users as $u): ?>
                                        <tr>
                                            <td>
                                                <a href="edit_users.php?id=<?= $u['user_id'] ?>" class="btn btn-sm btn-warning mb-1">Edit</a>
                                                <a href="list_users.php?delete=<?= $u['user_id'] ?>&<?= http_build_query($_GET) ?>" 
                                                   onclick="return confirm('Delete this user permanently?');"
                                                   class="btn btn-sm btn-danger">Delete</a>
                                            </td>
                                            <td><?= htmlspecialchars($u['user_id']) ?></td>
                                            <td>
                                                <img src="../../Front office/assets/img/userProfile/<?= htmlspecialchars($u['avatar'] ?? 'default.png') ?>" 
                                                     width="50" height="50" class="rounded-circle" alt="Avatar">
                                            </td>
                                            <td><?= htmlspecialchars($u['email']) ?></td>
                                            <td><?= htmlspecialchars($u['fname'] . ' ' . $u['lname']) ?></td>
                                            <td><span class="badge badge-info"><?= ucfirst($u['role']) ?></span></td>
                                            <td>
                                                <?php if ($u['is_banned']): ?>
                                                    <span class="badge badge-danger">BANNED</span>
                                                <?php elseif (!$u['is_approved']): ?>
                                                    <span class="badge badge-warning text-dark">PENDING</span>
                                                <?php else: ?>
                                                    <span class="badge badge-success">ACTIVE</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($u['is_approved']): ?>
                                                    <a href="approve_user.php?id=<?= $u['user_id'] ?>&action=unapprove&<?= http_build_query($_GET) ?>" 
                                                       class="btn btn-sm btn-outline-secondary">Approved</a>
                                                <?php else: ?>
                                                    <a href="approve_user.php?id=<?= $u['user_id'] ?>&<?= http_build_query($_GET) ?>" 
                                                       class="btn btn-sm btn-success"
                                                       onclick="return confirm('Approve this user?');">Approve</a>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($u['is_banned']): ?>
                                                    <a href="toggle_ban.php?id=<?= $u['user_id'] ?>&action=unban&<?= http_build_query($_GET) ?>" 
                                                       class="btn btn-sm btn-warning">Unban</a>
                                                <?php else: ?>
                                                    <a href="toggle_ban.php?id=<?= $u['user_id'] ?>&action=ban&<?= http_build_query($_GET) ?>" 
                                                       class="btn btn-sm btn-danger"
                                                       onclick="return confirm('Ban this user?');">Ban</a>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= htmlspecialchars($u['starPoints'] ?? 0) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

       