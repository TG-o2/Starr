<?php
require_once "../../../config/config.php"; 
require_once "../../../Controller/UserController.php";

if (!isset($_GET['token']) || empty($_GET['token'])) {
    die("Invalid link.");
}

$token = $_GET['token'];
global $pdo;

$sql = "SELECT user_id FROM user WHERE verification_token = :token AND verified = 0";
$stmt = $pdo->prepare($sql);
$stmt->execute(['token' => $token]);
$user = $stmt->fetch();

if ($user) {
    // Mark as verified
    $update = $pdo->prepare("UPDATE user SET verified = 1, verification_token = NULL WHERE user_id = :id");
    $update->execute(['id' => $user['user_id']]);

    $msg = "Your email has been successfully verified! You can now log in.";
} else {
    $msg = "This link is invalid or already used.";
}

header("Location: login.php?msg=" . urlencode($msg));
exit;
?>