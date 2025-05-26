<?php
require_once 'db_conn.php';
session_start();


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Jo i autorizuar");
}

$ticket_id = $_GET['id'] ?? $_POST['id'] ?? null;

if (!$ticket_id) {
    die("ID e biletës mungon.");
}

$stmt = $conn->prepare("DELETE FROM tickets WHERE id = ?");
$stmt->bind_param("i", $ticket_id);

if ($stmt->execute()) {
    header("Location: admin_dashboard.php?deleted=true");
    exit();
} else {
    die("Gabim gjatë fshirjes.");
}
?>
