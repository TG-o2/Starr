<?php
/**
 * Teacher Dashboard Access Point
 * Redirects users based on their role to appropriate dashboard
 */
session_start();

if (!isset($_SESSION['user_id'])) {
    // Not logged in - redirect to login
    header('Location: ../Front office/User-signup/login.php');
    exit;
}

$role = $_SESSION['role'] ?? '';

if ($role === 'teacher') {
    // Redirect teacher to teacher dashboard
    header('Location: ../Back office/Teacher Dashboard/Dashboard.php');
    exit;
} elseif ($role === 'admin') {
    // Redirect admin to admin dashboard
    header('Location: ../Back office/Admin Dashboard/Dashboard.php');
    exit;
} else {
    // Regular student - redirect to front office
    header('Location: ../Front office/index.html');
    exit;
}
?>
