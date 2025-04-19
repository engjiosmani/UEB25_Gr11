<?php
class User {
    public $fullname;
    protected $email;
    private $password;
    public $birthday;

    public function __construct($fullname, $email, $password, $birthday) {
        $this->fullname = $fullname;
        $this->email = $email;
        $this->password = $password;
        $this->birthday = $birthday;
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

    public function displayInfo() {
        echo "Përdoruesi u regjistrua me sukses!<br>";
        echo "Emri i plotë: $this->fullname <br>";
        echo "Email:" . $this->getEmail() ." <br>";
        echo "Data e lindjes: $this->birthday <br>";
    }

    public function login($email, $password) {
        return $this->getEmail() === $email && $this->getPassword() === $password;
    }
    public function getName() {
        return $this->fullname;
    }
    
    
}
?>
