<?php
require_once "../../../config/config.php"; 
require_once "../../../Controller/UserController.php";

if (!isset($_GET['token']) || empty(trim($_GET['token']))) {
    $msg = "Invalid or missing verification token.";
    header("Location: login.php?msg=" . urlencode($msg));
    exit;
}

$token = trim($_GET['token']);

global $pdo;

$sql = "SELECT user_id FROM user WHERE verification_token = :token AND verified = 0";
$stmt = $pdo->prepare($sql);
$stmt->execute(['token' => $token]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    $update = $pdo->prepare("UPDATE user SET verified = 1, verification_token = NULL WHERE user_id = :id");
    $update->execute(['id' => $user['user_id']]);
    $msg = "Your email has been successfully verified! You can now log in.";
} else {
    $msg = "This verification link is invalid or has already been used.";
}

header("Location: login.php?msg=" . urlencode($msg));
exit;
?>