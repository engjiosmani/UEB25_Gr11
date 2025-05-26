<?php
require_once 'error_handler.php';
require_once 'klasat/User.php';
require_once 'db_conn.php';

define("EARLY_BIRD_PRICE", 120);
define("STANDARD_PRICE", 240);
define("MAX_TICKETS", 10);

function pastroMesazhin(&$mesazhi) {
    $mesazhi = trim($mesazhi);
    $mesazhi = strip_tags($mesazhi);
    $mesazhi = preg_replace('/\s+/', ' ', $mesazhi); 
}


function validateAndFormatPhone($phone) {
    $digitsOnly = preg_replace("/\D+/", "", $phone);
    if (str_starts_with($digitsOnly, "383")) {
        $digitsOnly = substr($digitsOnly, 3);
    }
    if (preg_match("/^\d{6,9}$/", $digitsOnly)) {
        if (strlen($digitsOnly) === 9) {
            return substr($digitsOnly, 0, 3) . '-' . substr($digitsOnly, 3, 3) . '-' . substr($digitsOnly, 6, 3);
        } elseif (strlen($digitsOnly) === 6) {
            return substr($digitsOnly, 0, 3) . '-' . substr($digitsOnly, 3, 3);
        } else {
            return $digitsOnly;
        }
    }
    return false;
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

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user = $_SESSION['user'] ?? null;
if (!$user) {
    echo "<p style='color:red;'>User session missing.</p>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $user->fullname ?? '';
    $email = $user->getEmail() ?? '';
    $phone = $user->phone ?? '';
    $ticket_type = $_POST['ticket_type'] ?? '';
    $num_tickets = $_POST['num_tickets'] ?? '';
    $message = $_POST['message'] ?? '';

     pastroMesazhin($message);

    $errors = [];

    $formatted_phone = validateAndFormatPhone($phone);
    if (!$formatted_phone) $errors[] = "Phone format invalid.";
    if (empty($name)) $errors[] = "Name is required.";
    if (empty($email)) $errors[] = "Email is required.";
    if (empty($num_tickets)) {
        $errors[] = "Number of tickets is required.";
    } elseif (!preg_match("/^[1-9]$|^10$/", $num_tickets)) {
        $errors[] = "Tickets must be between 1 and 10.";
    }
    if (empty($ticket_type)) $errors[] = "Ticket type is required.";

    if (!empty($errors)) {
        echo "<div style='color:red;'>";
        foreach ($errors as $e) echo $e . "<br>";
        echo "</div>";
        exit();
    }

    $total_price = calculateTotalPrice($ticket_type, $num_tickets);
    $stmt = $conn->prepare("INSERT INTO tickets (user_id, ticket_type, num_tickets, message, total_price) VALUES (?, ?, ?, ?, ?)");
    $user_id = $user->id;
    $stmt->bind_param("isisd", $user_id, $ticket_type, $num_tickets, $message, $total_price);
    $stmt->execute();

   if ($stmt->affected_rows > 0) {
    $_SESSION['ticket_success'] = "Thank you, {$name}! You purchased {$num_tickets} ticket(s) for <strong>{$ticket_type}</strong>. Total: \${$total_price}.";
    header("Location: index.php"); 
    exit();
} else {
    $_SESSION['ticket_error'] = "Ticket could not be saved.";
    header("Location: ticket.php");
    exit();
}


    $stmt->close();
}
?>
