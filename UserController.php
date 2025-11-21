<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../Model/User.php';
class UserController {

    // Add a new user
    public function addUser($user) {
        global $pdo;

        $sql = "INSERT INTO user 
            (user_id, email, password, fname, lname, DOB, role, avatar, description, starPoints)
            VALUES 
            (:user_id, :email, :password, :fname, :lname, :DOB, :role, :avatar, :description, 0)";

        try {
            $query = $pdo->prepare($sql);
            $query->execute([
                'user_id' => $user->getUserId(),
                'email' => $user->getEmail(),
                'password' => password_hash($user->getPassword(), PASSWORD_DEFAULT),
                'fname' => $user->getFname(),
                'lname' => $user->getLname(),
                'DOB' => $user->getDOB(),
                'role' => $user->getRole(),
                'avatar' => $user->getAvatar(),
                'description' => $user->getDescription() ?? ''
            ]);
            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Read all users
    public function readUsers() {
        global $pdo;
        $sql = "SELECT * FROM user";
        try {
            $query = $pdo->prepare($sql);
            $query->execute();
            return $query->fetchAll();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Update a user by ID
    public function updateUser($user, $user_id) {
        global $pdo;

        $sql = "UPDATE user SET
                email = :email,
                password = :password,
                fname = :fname,
                lname = :lname,
                DOB = :DOB,
                role = :role,
                avatar = :avatar,
                description = :description
                WHERE user_id = :user_id";
        try {
            $query = $pdo->prepare($sql);
            $query->execute([
                'user_id' => $user_id,
                'email' => $user->getEmail(),
                'password' => password_hash($user->getPassword(), PASSWORD_DEFAULT),
                'fname' => $user->getFname(),
                'lname' => $user->getLname(),
                'DOB' => $user->getDOB(),
                'role' => $user->getRole(),
                'avatar' => $user->getAvatar(),
                'description' => $user->getDescription() ?? ''
            ]);
            echo $query->rowCount() . " record(s) UPDATED successfully <br>";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Delete a user by ID
    public function deleteUser($user_id) {
        global $pdo;

        $sql = "DELETE FROM user WHERE user_id = :user_id";
        try {
            $query = $pdo->prepare($sql);
            $query->execute(['user_id' => $user_id]);
            echo $query->rowCount() . " record(s) DELETED successfully <br>";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Optional: get single user by ID
    public function getUserById($user_id) {
        global $pdo;
        $sql = "SELECT * FROM user WHERE user_id = :user_id";
        try {
            $query = $pdo->prepare($sql);
            $query->execute(['user_id' => $user_id]);
            return $query->fetch();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>