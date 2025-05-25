<?php
require_once 'User.php';

class Admin extends User {
    public int $id;
    public $role;

    public function __construct($fullname, $email, $password, $dob, $phone, $role = "admin") {
        parent::__construct($fullname, $email, $password, $dob, $phone);
        $this->role = $role;
    }
}
?>

