<?php
class User {
    public $fullname;
    public $email;
    public $password;
    public $birthday;

    public function __construct($fullname, $email, $password, $birthday) {
        $this->fullname = $fullname;
        $this->email = $email;
        $this->password = $password;
        $this->birthday = $birthday;
    }

    public function displayInfo() {
        echo "Përdoruesi u regjistrua me sukses!<br>";
        echo "Emri i plotë: $this->fullname <br>";
        echo "Email: $this->email <br>";
        echo "Data e lindjes: $this->birthday <br>";
    }

    public function login($email, $password) {
        if ($this->email === $email && $this->password === $password) {
            echo "Kyçja me sukses! Mirë se vini, $this->fullname";
        } else {
            echo "Email ose fjalëkalim i gabuar.";
        }
    }
}
?>
