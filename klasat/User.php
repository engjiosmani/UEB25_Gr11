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
    
}
?>