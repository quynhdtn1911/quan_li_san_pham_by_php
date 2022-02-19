<?php
class User{
    private $id;
    private $username;
    private $password;
    private $fullname;
    private $tel;
    private $email;
    private $address;
    private $role;

    public function setId($id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function getUsername() {
        return $this->username;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setFullname($fullname) {
        $this->fullname = $fullname;
    }

    public function getFullname() {
        return $this->fullname;
    }

    public function setTel($tel) {
        $this->tel = $tel;
    }

    public function getTel() {
        return $this->tel;
    }

    public function setAddress($address) {
        $this->address = $address;
    }

    public function getAddress() {
        return $this->address;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setRole($role) {
        $this->role = $role;
    }

    public function getRole() {
        return $this->role;
    }
}