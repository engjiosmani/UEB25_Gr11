<?php
require_once 'db_conn.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Jo i autorizuar");
}

$user_id = $_GET['id'] ?? null;

if (!$user_id) {
    die("ID e përdoruesit mungon.");
}

// 1. Fshi biletat
$stmt1 = $conn->prepare("DELETE FROM tickets WHERE user_id = ?");
if ($stmt1) {
    $stmt1->bind_param("i", $user_id);
    $stmt1->execute();
    $stmt1->close();
} else {
    die("Gabim në prepare për tickets: " . $conn->error);
}

// 2. Fshi mesazhet e kontaktit
$stmt2 = $conn->prepare("DELETE FROM contact_messages WHERE user_id = ?");
if ($stmt2) {
    $stmt2->bind_param("i", $user_id);
    $stmt2->execute();
    $stmt2->close();
} else {
    die("Gabim në prepare për contact_messages: " . $conn->error);
}

// 3. Fshi votat në artist_votes (nëse tabela ekziston)
$stmt3 = $conn->prepare("DELETE FROM artist_votes WHERE user_id = ?");
if ($stmt3) {
    $stmt3->bind_param("i", $user_id);
    $stmt3->execute();
    $stmt3->close();
} 

// 4. Fshi vetë përdoruesin
$stmt4 = $conn->prepare("DELETE FROM users WHERE id = ? AND role != 'admin'");
if ($stmt4) {
    $stmt4->bind_param("i", $user_id);
    if ($stmt4->execute()) {
        $stmt4->close();
        header("Location: admin_dashboard.php?user_deleted=true");
        exit();
    } else {
        die("Gabim gjatë fshirjes së përdoruesit.");
    }
} else {
    die("Gabim në prepare për users: " . $conn->error);
}
