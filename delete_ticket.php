<?php
require_once 'db_conn.php';
require_once 'klasat/User.php';
session_start();

if (!isset($_SESSION['user'])) {
    die("Jo i autorizuar.");
}

$user = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $ticket_id = intval($_POST['id']);

    // Merr user_id e biletës
    $stmt = $conn->prepare("SELECT user_id FROM tickets WHERE id = ?");
    $stmt->bind_param("i", $ticket_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $ticket = $result->fetch_assoc();

    if (!$ticket) {
        die("Bileta nuk ekziston.");
    }

    if ($user->role !== 'admin' && $user->id != $ticket['user_id']) {
        die("Nuk ke të drejtë të fshish këtë biletë.");
    }

    $stmt = $conn->prepare("DELETE FROM tickets WHERE id = ?");
    $stmt->bind_param("i", $ticket_id);
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Gabim gjatë fshirjes.";
    }
}
