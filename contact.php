<?php
require_once 'db_conn.php';
require_once 'error_handler.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["contact-name"];
    $email = $_POST["contact-email"];
    $company = $_POST["contact-company"];
    $message = $_POST["contact-message"];

    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, company, message) VALUES (?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("ssss", $name, $email, $company, $message);
        $stmt->execute();
        $stmt->close();

        $_SESSION['message_sent'] = true;
        header("Location: index.php#section_6");
        exit;
    } else {
        echo "<div class='alert alert-danger text-center'>Nuk u dërgua mesazhi.</div>";
    }
}
?>
