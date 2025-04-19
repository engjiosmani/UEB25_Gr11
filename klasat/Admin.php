<?php
require_once 'User.php';

class Admin extends User {
    public $role;

    public function __construct($fullname, $email, $password, $dob, $role = "admin") {
        parent::__construct($fullname, $email, $password, $dob);
        $this->role = $role;
    }

    public function displayAdminInfo($return = false) {
        $info = "<div style='text-align:center;'>
                    <h3>Admin Profile</h3>
                    Name: {$this->fullname}<br>
                    Email (protected): {$this->email}<br>
                    Role: admin
                 </div>";
        if ($return) {
            return $info;
        } else {
            echo $info;
        }
    }
}
?>
