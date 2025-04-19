<?php
define("EARLY_BIRD_PRICE", 120);
define("STANDARD_PRICE", 240);
define("MAX_TICKETS", 10);
function formatPhoneNumber($phone) {
    $phone = preg_replace("/\D/", "", $phone);

    if (preg_match("/^(\d{3})(\d{3})(\d{3})$/", $phone, $matches)) {
        return $matches[1] . '-' . $matches[2] . '-' . $matches[3];
    }

    return $phone; 
}
function calculateTotalPrice($ticket_type, $num_tickets) {
    switch ($ticket_type) {
        case 'Early Bird':
            return $num_tickets * EARLY_BIRD_PRICE;
        case 'Standard':
            return $num_tickets * STANDARD_PRICE;
        default:
            return 0;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['ticket-form-name'];
    $email = $_POST['ticket-form-email'];
    $phone = $_POST['ticket-form-phone'];
    $ticket_type = $_POST['TicketForm'] ?? 'Not selected';
    $num_tickets = $_POST['ticket-form-number'];
    $message = $_POST['ticket-form-message'];

    $error_msg = ''; 

    $formatted_phone = formatPhoneNumber($phone);

    if (empty($num_tickets)) {
        $error_msg .= "The number of tickets is required.<br>";
    } elseif (!preg_match("/^[1-9]$|^10$/", $num_tickets)) {
        $error_msg .= "The number of tickets must be between 1 and 10.<br>";
    }

    if (empty($ticket_type)) {
        $error_msg .= "Please select a ticket type.<br>";
    }
    if (empty($error_msg)) {
        $total_price = calculateTotalPrice($ticket_type, $num_tickets);
        $order_details = array(
            'customer_name' => strtoupper($name), 
            'email' => $email,
            'phone' => $formatted_phone,
            'ticket_type' => $ticket_type,
            'num_tickets' => $num_tickets,
            'total_price' => $total_price
        );
   
        echo "<h2>Thank you, $name!</h2>";
        echo "<p>You have ordered $num_tickets ticket(s) for the $ticket_type category.</p>";
        echo "<p>Total price: $" . $total_price . "</p>";
        echo "<p>Your formatted phone number is: $formatted_phone</p>";

        echo "<pre>Order details: ";
        var_dump($order_details);
        echo "</pre>";

        $sorted = $order_details;
        sort($sorted);
        echo "<pre>sort():\n";
        print_r($sorted);
        echo "</pre>";

        $sorted = $order_details;
        rsort($sorted);
        echo "<pre>rsort():\n";
        print_r($sorted);
        echo "</pre>";

        $sorted = $order_details;
        asort($sorted);
        echo "<pre>asort():\n";
        print_r($sorted);
        echo "</pre>";
        
    } else {
        echo "<p style='color:red;'>$error_msg</p>";
    }
}
?>