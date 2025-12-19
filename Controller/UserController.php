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

require_once __DIR__ . '/../View/Front office/assets/vendor/phpmailer/PHPMailer.php';
require_once __DIR__ . '/../View/Front office/assets/vendor/phpmailer/SMTP.php';
require_once __DIR__ . '/../View/Front office/assets/vendor/phpmailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class UserController {

    public function addUser(User $user) {
        $pdo = Config::getConnexion();
        $sql = "INSERT INTO user (
            password, fname, lname, DOB, profilePicture, description, username, email, 
            role, avatar, verified, is_banned, is_approved, verification_token, approval_token
        ) VALUES (
            :password, :fname, :lname, :DOB, :profilePicture, :description, :username, :email,
            :role, :avatar, :verified, :is_banned, :is_approved, :verification_token, :approval_token
        )";
        
        $query = $pdo->prepare($sql);
        $query->execute([
            ':password' => password_hash($user->getPassword(), PASSWORD_DEFAULT),
            ':fname' => $user->getFname(),
            ':lname' => $user->getLname(),
            ':DOB' => $user->getDOB(),
            ':profilePicture' => $user->getProfilePicture(),
            ':description' => $user->getDescription(),
            ':username' => $user->getUsername(),
            ':email' => $user->getEmail(),
            ':role' => $user->getRole() ?? 'student',
            ':avatar' => $user->getAvatar(),
            ':verified' => $user->getVerified() ?? 0,
            ':is_banned' => $user->getIsBanned() ?? 0,
            ':is_approved' => $user->getIsApproved() ?? 0,
            ':verification_token' => $user->getVerificationToken(),
            ':approval_token' => $user->getApprovalToken()
        ]);

        return $pdo->lastInsertId();
    }

    public function readUsers() {
        $pdo = Config::getConnexion();
        $sql = "SELECT * FROM user ORDER BY fname";
        $query = $pdo->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserByEmail($email) {
        $pdo = Config::getConnexion();
        $sql = "SELECT * FROM user WHERE email = :email";
        $query = $pdo->prepare($sql);
        $query->execute(['email' => $email]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function updateUser(User $user, $user_id) {
        $pdo = Config::getConnexion();

        // Check if email is already used by another user
        $sql = "SELECT COUNT(*) FROM user WHERE email = :email AND user_id != :user_id";
        $query = $pdo->prepare($sql);
        $query->execute(['email' => $user->getEmail(), 'user_id' => $user_id]);
        if ($query->fetchColumn() > 0) {
            throw new Exception("Email already exists.");
        }

        // Handle password hashing
        $hashedPassword = !empty($user->getPassword())
            ? password_hash($user->getPassword(), PASSWORD_DEFAULT)
            : $pdo->query("SELECT password FROM user WHERE user_id = '$user_id'")->fetchColumn();

        $sql = "UPDATE user SET 
                password=:password, fname=:fname, lname=:lname, DOB=:DOB, 
                profilePicture=:profilePicture, description=:description, username=:username,
                email=:email, role=:role, avatar=:avatar,
                verified=:verified, is_approved=:is_approved, is_banned=:is_banned,
                verification_token=:verification_token, approval_token=:approval_token
                WHERE user_id=:user_id";

        $query = $pdo->prepare($sql);
        $query->execute([
            ':password' => $hashedPassword,
            ':fname' => $user->getFname(),
            ':lname' => $user->getLname(),
            ':DOB' => $user->getDOB(),
            ':profilePicture' => $user->getProfilePicture(),
            ':description' => $user->getDescription(),
            ':username' => $user->getUsername(),
            ':email' => $user->getEmail(),
            ':role' => $user->getRole(),
            ':avatar' => $user->getAvatar(),
            ':verified' => $user->getVerified(),
            ':is_approved' => $user->getIsApproved(),
            ':is_banned' => $user->getIsBanned(),
            ':verification_token' => $user->getVerificationToken(),
            ':approval_token' => $user->getApprovalToken(),
            ':user_id' => $user_id
        ]);

        // Update session if user is updating their own profile
        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $user_id) {
            $_SESSION['email'] = $user->getEmail();
            $_SESSION['fname'] = $user->getFname();
            $_SESSION['lname'] = $user->getLname();
            $_SESSION['username'] = $user->getUsername();
            $_SESSION['role'] = $user->getRole();
            $_SESSION['avatar'] = $user->getAvatar();
            $_SESSION['description'] = $user->getDescription();
            $_SESSION['verified'] = $user->getVerified();
        }
    }

    public function deleteUser($user_id) {
        $pdo = Config::getConnexion();
        $pdo->prepare("DELETE FROM user WHERE user_id = ?")->execute([$user_id]);

        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $user_id) {
            session_unset();
            session_destroy();
        }
    }

    public function addUserWithVerification(User $user) {
        $pdo = Config::getConnexion();

        $token = bin2hex(random_bytes(32));

        $sql = "INSERT INTO user (
            password, fname, lname, DOB, profilePicture, description, username, email, role, 
            avatar, verified, is_banned, is_approved, verification_token, approval_token
        ) VALUES (
            :password, :fname, :lname, :DOB, :profilePicture, :description, :username, :email, :role,
            :avatar, :verified, :is_banned, :is_approved, :verification_token, :approval_token
        )";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':password' => password_hash($user->getPassword(), PASSWORD_DEFAULT),
            ':fname' => $user->getFname(),
            ':lname' => $user->getLname(),
            ':DOB' => $user->getDOB(),
            ':profilePicture' => $user->getProfilePicture(),
            ':description' => $user->getDescription(),
            ':username' => $user->getUsername(),
            ':email' => $user->getEmail(),
            ':role' => $user->getRole() ?? 'student',
            ':avatar' => $user->getAvatar(),
            ':verified' => 0,
            ':is_banned' => 0,
            ':is_approved' => 0,
            ':verification_token' => $token,
            ':approval_token' => null
        ]);

        // Generate verification URL dynamically - works on localhost, IP, or domain
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $verifyLink = "{$protocol}://{$host}/Starr/View/Front%20office/User-signup/verify.php?token={$token}";

        $mail = new PHPMailer(true);
        try {
            $mail->SMTPDebug = 0;
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

        return $pdo->lastInsertId();
    }

    public function resendVerificationEmail($email) {
        $pdo = Config::getConnexion();

        $sql = "SELECT * FROM user WHERE email = :email AND verified = 0";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) return false;

        $token = bin2hex(random_bytes(32));
        $update = $pdo->prepare("UPDATE user SET verification_token = ? WHERE user_id = ?");
        $update->execute([$token, $user['user_id']]);

        // Generate verification URL dynamically - works on localhost, IP, or domain
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $verifyLink = "{$protocol}://{$host}/Starr/View/Front%20office/User-signup/verify.php?token={$token}";

        $mail = new PHPMailer(true);
        try {
            $mail->SMTPDebug = 0;                     // Disable verbose debug output
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

    public function resetVerified($user_id) {
        $pdo = Config::getConnexion();
        $sql = "UPDATE user SET verified = 0 WHERE user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);
    }


    public function searchAndFilterUsers($filters = []) {
        $pdo = Config::getConnexion();

        $sql = "SELECT * FROM user WHERE 1=1";
        $params = [];

        if (!empty($filters['search'])) {
            $search = '%' . trim($filters['search']) . '%';
            $sql .= " AND (user_id LIKE ? OR email LIKE ? OR CONCAT(fname, ' ', lname) LIKE ? OR CONCAT(lname, ' ', fname) LIKE ?)";
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
        }

        if (!empty($filters['role'])) {
            $sql .= " AND role = ?";
            $params[] = $filters['role'];
        }

        if (isset($filters['status']) && $filters['status'] !== '') {
            switch ($filters['status']) {
                case 'active':
                    $sql .= " AND is_banned = 0 AND is_approved = 1";
                    break;
                case 'pending':
                    $sql .= " AND is_banned = 0 AND is_approved = 0";
                    break;
                case 'banned':
                    $sql .= " AND is_banned = 1";
                    break;
            }
        }

        if ($filters['approved'] !== '' && $filters['approved'] !== null) {
            $sql .= " AND is_approved = ?";
            $params[] = $filters['approved'];
        }

        $sql .= " ORDER BY user_id DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
// End of class

?>