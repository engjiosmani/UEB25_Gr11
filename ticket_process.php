<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['ticket-form-name'];
    $email = $_POST['ticket-form-email'];
    $phone = $_POST['ticket-form-phone'];
    $ticket_type = $_POST['TicketForm'] ?? 'Not selected';
    $num_tickets = $_POST['ticket-form-number'];
    $message = $_POST['ticket-form-message'];

    $error_msg = ''; 

    if (empty($num_tickets)) {
        $error_msg .= "The number of tickets is required.<br>";
    } elseif (!preg_match("/^[1-9]$|^10$/", $num_tickets)) {
        $error_msg .= "The number of tickets must be between 1 and 10.<br>";
    }

    if (empty($ticket_type)) {
        $error_msg .= "Please select a ticket type.<br>";
    }

    if (empty($error_msg)) {
        echo "<h2>Thank you, $name!</h2>";
        echo "<p>You have ordered $num_tickets ticket(s) for the $ticket_type category.</p>";
    } else {
        echo "<p style='color:red;'>$error_msg</p>";
    }
}
?>