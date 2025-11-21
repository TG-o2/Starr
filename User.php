

<?php
class User {
    private $user_id;
    private $email;
    private $password;
    private $fname;
    private $lname;
    private $DOB;
    private $role;
    private $avatar;
    private $description;
    private $starPoints;

    public function getUserId() { return $this->user_id; }
    public function setUserId($user_id) { $this->user_id = $user_id; }

    public function getEmail() { return $this->email; }
    public function setEmail($email) { $this->email = $email; }

    public function getPassword() { return $this->password; }
    public function setPassword($password) { $this->password = $password; }

    public function getFname() { return $this->fname; }
    public function setFname($fname) { $this->fname = $fname; }

    public function getLname() { return $this->lname; }
    public function setLname($lname) { $this->lname = $lname; }

    public function getDOB() { return $this->DOB; }
    public function setDOB($DOB) { $this->DOB = $DOB; }

    public function getRole() { return $this->role; }
    public function setRole($role) { $this->role = $role; }

    public function getAvatar() { return $this->avatar; }
    public function setAvatar($avatar) { $this->avatar = $avatar; }

    public function getDescription() { return $this->description; }
    public function setDescription($description) { $this->description = $description; }

    public function getStarPoints() { return $this->starPoints; }
    public function setStarPoints($starPoints) { $this->starPoints = $starPoints; }
}
?>