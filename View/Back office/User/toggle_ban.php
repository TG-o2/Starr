<?php
require_once __DIR__ . '/../admin_guard.php';
require_once "../../../Controller/UserController.php";

$user_id = $_GET['id'] ?? '';
$action  = $_GET['action'] ?? '';

if (!$user_id || !in_array($action, ['ban', 'unban'])) {
    die("Invalid request.");
}

$status = ($action === 'ban') ? 1 : 0;

require_once '../../../config/config.php';
$pdo = Config::getConnexion();
$pdo->prepare("UPDATE user SET is_banned = ? WHERE user_id = ?")
    ->execute([$status, $user_id]);

header("Location: list_users.php?msg=" . ($status ? "User banned" : "User unbanned"));
exit;
?>