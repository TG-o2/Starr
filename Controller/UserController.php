<?php

if (file_exists(__DIR__ . '/../.env')) {
    $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#') continue;
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
    }
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../Model/User.php';

// FIXED PATHS — THESE WORK 100% ON YOUR COMPUTER
require_once __DIR__ . '/../View/Front office/assets/vendor/phpmailer/PHPMailer.php';
require_once __DIR__ . '/../View/Front office/assets/vendor/phpmailer/SMTP.php';
require_once __DIR__ . '/../View/Front office/assets/vendor/phpmailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class UserController {

    public function addUser(User $user) {
        global $pdo;
        $sql = "INSERT INTO user (user_id, email, password, fname, lname, DOB, role, avatar, description, starPoints)
                VALUES (:user_id, :email, :password, :fname, :lname, :DOB, :role, :avatar, :description, 0)";
        $query = $pdo->prepare($sql);
        $query->execute([
            'user_id'     => $user->getUserId(),
            'email'       => $user->getEmail(),
            'password'    => password_hash($user->getPassword(), PASSWORD_DEFAULT),
            'fname'       => $user->getFname(),
            'lname'       => $user->getLname(),
            'DOB'         => $user->getDOB(),
            'role'        => $user->getRole(),
            'avatar'      => $user->getAvatar(),
            'description' => $user->getDescription() ?? ''
        ]);
    }

    public function readUsers() {
        global $pdo;
        $sql = "SELECT * FROM user ORDER BY fname";
        $query = $pdo->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserByEmail($email) {
        global $pdo;
        $sql = "SELECT * FROM user WHERE email = :email";
        $query = $pdo->prepare($sql);
        $query->execute(['email' => $email]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function updateUser(User $user, $user_id) {
        global $pdo;

        $sql = "SELECT COUNT(*) FROM user WHERE email = :email AND user_id != :user_id";
        $query = $pdo->prepare($sql);
        $query->execute(['email' => $user->getEmail(), 'user_id' => $user_id]);
        if ($query->fetchColumn() > 0) {
            throw new Exception("Email already exists.");
        }

        $hashedPassword = !empty($user->getPassword())
            ? password_hash($user->getPassword(), PASSWORD_DEFAULT)
            : $pdo->query("SELECT password FROM user WHERE user_id = '$user_id'")->fetchColumn();

        $sql = "UPDATE user SET 
                email=:email, password=:password, fname=:fname, lname=:lname,
                DOB=:DOB, role=:role, avatar=:avatar, description=:description,
                is_approved=:is_approved, is_banned=:is_banned
                WHERE user_id=:user_id";

        $query = $pdo->prepare($sql);
        $query->execute([
            'email'        => $user->getEmail(),
            'password'     => $hashedPassword,
            'fname'        => $user->getFname(),
            'lname'        => $user->getLname(),
            'DOB'          => $user->getDOB(),
            'role'         => $user->getRole(),
            'avatar'       => $user->getAvatar(),
            'description'  => $user->getDescription() ?? '',
            'is_approved'  => $user->getIsApproved(),
            'is_banned'    => $user->getIsBanned(),
            'user_id'      => $user_id
        ]);

        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $user_id) {
            $_SESSION['email'] = $user->getEmail();
            $_SESSION['fname'] = $user->getFname();
            $_SESSION['lname'] = $user->getLname();
            $_SESSION['role']  = $user->getRole();
            $_SESSION['avatar'] = $user->getAvatar();
            $_SESSION['description'] = $user->getDescription();
        }
    }

    public function deleteUser($user_id) {
        global $pdo;
        $pdo->prepare("DELETE FROM user WHERE user_id = ?")->execute([$user_id]);

        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $user_id) {
            session_unset();
            session_destroy();
        }
    }

    public function addUserWithVerification(User $user) {
        global $pdo;

        $token = bin2hex(random_bytes(32));

        $sql = "INSERT INTO user (
            user_id, email, password, fname, lname, DOB, role, avatar, description,
            starPoints, verified, verification_token, is_approved, is_banned
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0, 0, ?, 0, 0)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $user->getUserId(),
            $user->getEmail(),
            password_hash($user->getPassword(), PASSWORD_DEFAULT),
            $user->getFname(),
            $user->getLname(),
            $user->getDOB(),
            $user->getRole(),
            $user->getAvatar(),
            $user->getDescription() ?? '',
            $token
        ]);

        $verifyLink = "http://192.168.1.208/Starr/View/Front%20office/User-signup/verify.php?token={$token}";

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['GMAIL_USER'];
            $mail->Password   = $_ENV['GMAIL_APP_PASSWORD'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom($_ENV['GMAIL_USER'], 'Starr');
            $mail->addAddress($user->getEmail());
            $mail->isHTML(true);
            $mail->Subject = 'Verify Your Starr Account';
            $mail->Body    = "
                <h2>Hello {$user->getFname()}!</h2>
                <p>Thank you for joining Starr!</p>
                <p style='text-align:center;margin:40px 0;'>
                    <a href='$verifyLink' style='padding:16px 36px;background:#28a745;color:white;text-decoration:none;border-radius:10px;font-size:18px;'>
                        Verify My Email
                    </a>
                </p>
            ";

            $mail->send();
        } catch (Exception $e) {
            error_log("PHPMailer Error: " . $mail->ErrorInfo);
        }
    }

    public function resendVerificationEmail($email) {
        global $pdo;

        $sql = "SELECT * FROM user WHERE email = :email AND verified = 0";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) return false;

        $token = bin2hex(random_bytes(32));
        $update = $pdo->prepare("UPDATE user SET verification_token = ? WHERE user_id = ?");
        $update->execute([$token, $user['user_id']]);

        $verifyLink = "http://192.168.1.208/Starr/View/Front%20office/User-signup/verify.php?token={$token}";

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['GMAIL_USER'];
            $mail->Password   = $_ENV['GMAIL_APP_PASSWORD'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom($_ENV['GMAIL_USER'], 'Starr');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Resend: Verify Your Starr Account';
            $mail->Body    = "
                <h2>Hello {$user['fname']}!</h2>
                <p>Click below to verify your email:</p>
                <p style='text-align:center;'>
                    <a href='$verifyLink' style='padding:16px 36px;background:#28a745;color:white;text-decoration:none;border-radius:10px;'>
                        Verify Email
                    </a>
                </p>
            ";

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Resend failed: " . $mail->ErrorInfo);
            return false;
        }
    }
}
?>