<?php
require_once 'User.php';

class Admin extends User {
    public $role;

    public function __construct($fullname, $email, $password, $dob, $role = "admin") {
        parent::__construct($fullname, $email, $password, $dob);
        $this->role = $role;
    }

    public function displayAdminInfo() {
        echo "<h3>Admin Profile</h3>";
        echo "Name: $this->fullname<br>";
        echo "Email (protected): " . $this->getEmail() . "<br>";
        echo "Role: $this->role<br>";
    }
}
?>
