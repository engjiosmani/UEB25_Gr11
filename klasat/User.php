<?php
class User {
   
    public int $id;
    public string $fullname;
    protected string $email;
    private string $password;
    public string $phone;
    public $role; 
    public string $dob;

    public function __construct($fullname, $email, $password, $dob, $phone,$role) {
    $this->fullname = $fullname;
    $this->email = $email;
    $this->password = $password;
    $this->dob = $dob;
    $this->role=$role;
    $this->phone = $phone;
}

public function getPhone() {
    return $this->phone;
}

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }
    public function login($email, $password) {
        return $this->getEmail() === $email && $this->getPassword() === $password;
    }
    public function getName() {
        return $this->fullname;
    }
    
    
}
?>
