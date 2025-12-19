<?php
session_start();
require_once "../../../Controller/UserController.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Access denied.");
}

$user_id = $_GET['id'] ?? '';
if (!$user_id) die("Invalid user.");

$pdo = Config::getConnexion();
$pdo->prepare("UPDATE user SET is_approved = 1 WHERE user_id = ?")
    ->execute([$user_id]);

header("Location: list_users.php?msg=User approved successfully!");
exit;
?>