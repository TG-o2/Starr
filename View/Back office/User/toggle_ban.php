<?php
session_start();
require_once "../../../Controller/UserController.php";


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Access denied. Admins only.");
}

$user_id = $_GET['id'] ?? '';
$action  = $_GET['action'] ?? '';

if (!$user_id || !in_array($action, ['ban', 'unban'])) {
    die("Invalid request.");
}

$status = ($action === 'ban') ? 1 : 0;

global $pdo;
$pdo->prepare("UPDATE user SET is_banned = ? WHERE user_id = ?")
    ->execute([$status, $user_id]);

header("Location: list_users.php?msg=" . ($status ? "User banned" : "User unbanned"));
exit;
?>