<?php
require_once 'db_conn.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}


if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    
     if ($_SESSION['user_id'] == $id) {
        echo "You can't delete yourself!";
      exit();
    }

    
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php#users");
    } else {
        echo "Gabim gjatë fshirjes!";
    }

    $stmt->close();
}
?>
