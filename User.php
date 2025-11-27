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

    public function __construct($user_id, $email, $password, $fname, $lname, $DOB, $role, $avatar, $description) {
        $this->user_id = $user_id;
        $this->email = $email;
        $this->password = $password;
        $this->fname = $fname;
        $this->lname = $lname;
        $this->DOB = $DOB;
        $this->role = $role;
        $this->avatar = $avatar;
        $this->description = $description;
    }

    public function getUserId() {
        return $this->user_id;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getFname() {
        return $this->fname;
    }

    public function getLname() {
        return $this->lname;
    }

    public function getDOB() {
        return $this->DOB;
    }

    public function getRole() {
        return $this->role;
    }

    public function getAvatar() {
        return $this->avatar;
    }

    public function getDescription() {
        return $this->description;
    }
}
