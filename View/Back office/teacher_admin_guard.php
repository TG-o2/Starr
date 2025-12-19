<?php
// Access guard for back office pages accessible by both teachers and admins
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$role = isset($_SESSION['role']) ? strtolower((string)$_SESSION['role']) : '';
if ($role !== 'admin' && $role !== 'teacher') {
    http_response_code(403);
    exit('Access denied. Admin or Teacher role required.');
}
