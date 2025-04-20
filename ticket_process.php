<?php
define("EARLY_BIRD_PRICE", 120);
define("STANDARD_PRICE", 240);
define("MAX_TICKETS", 10);
function  validateAndFormatPhone($phone) {
    $phone = preg_replace("/\s+/", "", $phone);
    if (preg_match("/^\d{9}$/", $phone)) {
        // Formatim me viza: xxx-xxx-xxx
        return substr($phone, 0, 3) . '-' . substr($phone, 3, 3) . '-' . substr($phone, 6, 3);
    } else {
        return false; // nuk është valid
    }
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
    $num_tickets = (int)$_POST['ticket-form-number'];
    $message = $_POST['ticket-form-message'];

    $error_msg = ''; 

    $formatted_phone = validateAndFormatPhone($phone);
    if ($formatted_phone === false) {
        $error_msg .= "The phone number is in an incorrect format.<br>";
    }
    if (empty($name)) {
        $error_msg .= "Name is required.<br>";
      } elseif(!preg_match("/^[a-zA-Z\s]+$/", $name)) {
        $error_msg .= "Name can only contain letters and spaces.<br>";
    }
    
      if (empty($email)) {
        $error_msg .= "Email is required.<br>";
    } elseif(!preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email)) {
        $error_msg .= "Please enter a valid email address.<br>";
    }

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