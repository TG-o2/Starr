<?php
// Basic admin access guard for back office pages
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$role = isset($_SESSION['role']) ? strtolower((string)$_SESSION['role']) : '';
if ($role !== 'admin') {
    http_response_code(403);
    exit('Access denied. Admin role required.');
}
