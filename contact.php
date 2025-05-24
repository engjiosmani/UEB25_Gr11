<?php
require_once 'error_handler.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["contact-name"];
    $email = $_POST["contact-email"];
    $company = $_POST["contact-company"];
    $message = $_POST["contact-message"];

    echo "Mesazhi u dërgua me sukses!";
    header("Refresh: 3; url=index.php");
    exit;
}
?>
