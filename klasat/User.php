<?php
class User {
    public $name;
    public $email;
    public $password;
    public $dob;

    public function __construct($name, $email, $password, $dob) {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->dob = $dob;
    }

    public function register() {
        return "Përdoruesi {$this->name} u regjistrua me sukses!";
    }

    public function login($email, $password) {
        return $email === $this->email && $password === $this->password;
    }
    
    // Getters
    public function getName() {
        return $this->name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getDob() {
        return $this->dob;
    }

    // Setters
    public function setName($name) {
        $this->name = $name;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setDob($dob) {
        $this->dob = $dob;
    }
}
?>