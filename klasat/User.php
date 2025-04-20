<?php
class User {
    public $fullname;
    protected $email;
    private $password;
    public $dob;

    public function __construct($fullname, $email, $password, $dob) {
        $this->fullname = $fullname;
        $this->email = $email;
        $this->password = $password;
        $this->dob = $dob;
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
        echo "Data e lindjes: $this->dob <br>";
    }

    public function login($email, $password) {
        return $this->getEmail() === $email && $this->getPassword() === $password;
    }
    public function getName() {
        return $this->fullname;
    }
    
    
}
?>
