<?php
require_once 'db_conn.php';
require_once 'klasat/User.php';
session_start();

if (!isset($_SESSION['user'])) {
    die("Jo i autorizuar.");
}

$user = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ticket_id = intval($_POST['ticket_id']);
    $ticket_type = $_POST['ticket_type'];
    $num_tickets = intval($_POST['num_tickets']);

    // Llogarit çmimin për biletë
    $price_per_ticket = 0;
    if ($ticket_type === 'Early Bird') {
        $price_per_ticket = 120;
    } elseif ($ticket_type === 'Standard') {
        $price_per_ticket = 240;
    }

    $total_price = $price_per_ticket * $num_tickets;

    
    $stmt = $conn->prepare("SELECT user_id FROM tickets WHERE id = ?");
    $stmt->bind_param("i", $ticket_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $ticket = $result->fetch_assoc();

    if (!$ticket) {
        die("Bileta nuk ekziston.");
    }

    if ($user->role !== 'admin' && $user->id != $ticket['user_id']) {
        die("Nuk ke të drejtë të përditësosh këtë biletë.");
    }

    $stmt = $conn->prepare("UPDATE tickets SET ticket_type = ?, num_tickets = ?, total_price = ? WHERE id = ?");
    $stmt->bind_param("sidi", $ticket_type, $num_tickets, $total_price, $ticket_id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Gabim gjatë përditësimit.";
    }
}
