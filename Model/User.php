<?php

class User {

    // ======== ATTRIBUTES ========
    private $user_id;
    private $password;
    private $fname;
    private $lname;
    private $DOB;
    private $profilePicture;
    private $description;
    private $username;
    private $email;
    private $role;
    private $avatar;
    private $verified;
    private $is_banned;
    private $is_approved;
    private $verification_token;
    private $approval_token;

    // ======== CONSTRUCTOR ========
    public function __construct(
        $user_id = null,
        $password = null,
        $fname = null,
        $lname = null,
        $DOB = null,
        $profilePicture = null,
        $description = null,
        $username = null,
        $email = null,
        $role = 'student',
        $avatar = null,
        $verified = 0,
        $is_banned = 0,
        $is_approved = 0,
        $verification_token = null,
        $approval_token = null
    ) {
        $this->user_id = $user_id;
        $this->password = $password;
        $this->fname = $fname;
        $this->lname = $lname;
        $this->DOB = $DOB;
        $this->profilePicture = $profilePicture;
        $this->description = $description;
        $this->username = $username;
        $this->email = $email;
        $this->role = $role ?? 'student';
        $this->avatar = $avatar;
        $this->verified = $verified ?? 0;
        $this->is_banned = $is_banned ?? 0;
        $this->is_approved = $is_approved ?? 0;
        $this->verification_token = $verification_token;
        $this->approval_token = $approval_token;
    }

    // ======== GETTERS ========
    public function getUserId() {
        return $this->user_id;
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

    public function getProfilePicture() {
        return $this->profilePicture;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getRole() {
        return $this->role;
    }

    public function getAvatar() {
        return $this->avatar;
    }

    public function getVerified() {
        return $this->verified;
    }

    public function getIsBanned() {
        return $this->is_banned;
    }

    public function getIsApproved() {
        return $this->is_approved;
    }

    public function getVerificationToken() {
        return $this->verification_token;
    }

    public function getApprovalToken() {
        return $this->approval_token;
    }

    // ======== SETTERS ========
    public function setPassword($password) {
        $this->password = $password;
    }

    public function setFname($fname) {
        $this->fname = $fname;
    }

    public function setLname($lname) {
        $this->lname = $lname;
    }

    public function setDOB($DOB) {
        $this->DOB = $DOB;
    }

    public function setProfilePicture($profilePicture) {
        $this->profilePicture = $profilePicture;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setRole($role) {
        $this->role = $role;
    }

    public function setAvatar($avatar) {
        $this->avatar = $avatar;
    }

    public function setVerified($verified) {
        $this->verified = $verified;
    }

    public function setIsBanned($is_banned) {
        $this->is_banned = $is_banned;
    }

    public function setIsApproved($is_approved) {
        $this->is_approved = $is_approved;
    }

    public function setVerificationToken($verification_token) {
        $this->verification_token = $verification_token;
    }

    public function setApprovalToken($approval_token) {
        $this->approval_token = $approval_token;
    }

    // ======== HELPER METHODS ========
    public function isApproved() {
        return (bool) $this->is_approved;
    }

    public function isBanned() {
        return (bool) $this->is_banned;
    }

    public function isVerified() {
        return (bool) $this->verified;
    }

    public function getFullName() {
        return trim(($this->fname ?? '') . ' ' . ($this->lname ?? ''));
    }

}
