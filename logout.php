<?php
session_start();

// Fshij të gjitha variablat e sesionit
$_SESSION = [];

// Fshij cookie nëse ekziston
if (isset($_COOKIE['user_email'])) {
    setcookie('user_email', '', time() - 3600, "/"); // skado cookie
}

// Unset dhe destroy sesionin
session_unset();
session_destroy();

// Ridrejto në faqen kryesore
header("Location: index.php");
exit();
