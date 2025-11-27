<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../Model/User.php';

class UserController {

    // Create / Add user
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

    // Read all users
    public function readUsers() {
        global $pdo;

        $sql = "SELECT * FROM user";
        $query = $pdo->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }

     /*// Update a user by ID
    public function updateUser(User $user, $user_id) {
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

        $query = $pdo->prepare($sql);
        $query->execute([
            'user_id'     => $user_id,
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

    // Delete a user by ID
   /* public function deleteUser($user_id) {
        global $pdo;

        $sql = "DELETE FROM user WHERE user_id = :user_id";
        $query = $pdo->prepare($sql);
        $query->execute(['user_id' => $user_id]);
    }

*/

public function getUserByEmail($email) {
    global $pdo; 

    $sql = "SELECT * FROM user WHERE email = :email";
    $query = $pdo->prepare($sql);
    $query->execute(['email' => $email]);
    return $query->fetch(PDO::FETCH_ASSOC); 
}


// ==============================
// UPDATE USER (WITH EMAIL CHECK)
// ==============================
public function updateUser(User $user, $user_id) {
    global $pdo;

    /* 1. Check if the new email already exists for another user */
    $sql = "SELECT COUNT(*) FROM user WHERE email = :email AND user_id != :user_id";
    $query = $pdo->prepare($sql);
    $query->execute([
        'email'    => $user->getEmail(),
        'user_id'  => $user_id
    ]);

    if ($query->fetchColumn() > 0) {
        throw new Exception("Email already exists. Choose another one.");
    }

    /* 2. Handle password: if it's empty -> keep old password */
    if (empty($user->getPassword())) {
        $sqlPass = "SELECT password FROM user WHERE user_id = :user_id";
        $passQuery = $pdo->prepare($sqlPass);
        $passQuery->execute(['user_id' => $user_id]);
        $hashedPassword = $passQuery->fetchColumn();
    } else {
        $hashedPassword = password_hash($user->getPassword(), PASSWORD_DEFAULT);
    }

    /* 3. Update user */
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

    $query = $pdo->prepare($sql);
    $query->execute([
        'email'       => $user->getEmail(),
        'password'    => $hashedPassword,
        'fname'       => $user->getFname(),
        'lname'       => $user->getLname(),
        'DOB'         => $user->getDOB(),
        'role'        => $user->getRole(),
        'avatar'      => $user->getAvatar(),
        'description' => $user->getDescription() ?? '',
        'user_id'     => $user_id
    ]);

    /* 4. Update session with new info */
    $_SESSION['email']       = $user->getEmail();
    $_SESSION['fname']       = $user->getFname();
    $_SESSION['lname']       = $user->getLname();
    $_SESSION['DOB']         = $user->getDOB();
    $_SESSION['role']        = $user->getRole();
    $_SESSION['avatar']      = $user->getAvatar();
    $_SESSION['description'] = $user->getDescription();
}



// ==============================
// DELETE USER
// ==============================
public function deleteUser($user_id) {
    global $pdo;

    $sql = "DELETE FROM user WHERE user_id = :user_id";
    $query = $pdo->prepare($sql);
    $query->execute(['user_id' => $user_id]);

    // Destroy session if the logged user deletes himself
    if (isset($_SESSION['user_id']) && $_SESSION['user_id'] === $user_id) {
        session_unset();
        session_destroy();
    }
}



}
?>
