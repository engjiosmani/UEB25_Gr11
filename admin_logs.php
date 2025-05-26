<?php
require_once 'klasat/User.php';
require_once 'klasat/Admin.php';
require_once 'error_handler.php';

session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']->role !== 'admin') {
    echo "<div style='color:red; text-align:center; margin-top:20px; font-weight:bold;'>
        Ky seksion është vetëm për administratorin.
    </div>";
    exit;
}

$filepath = "logs/contact_log.txt";
$logEntries = [];

if (file_exists($filepath) && filesize($filepath) > 0) {
    $lines = file($filepath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $logEntries[] = $line;
    }
} else {
    $logEntries[] = "Nuk ka log për të shfaqur.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Log i Mesazheve</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f5cf97, #ff6126);
            font-family: 'Arial', sans-serif;
            min-height: 100vh;
            padding: 40px 0;
        }
        .container {
            max-width: 960px;
            background: #fff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(255, 84, 27, 0.3);
        }
        h2 {
            color: #444;
            margin-bottom: 10px;
            text-align: center;
        }
        .subtitle {
            color: #ff6126;
            text-align: center;
            font-weight: bold;
            margin-bottom: 30px;
        }
        .log-entry {
            background-color: #222;
            color: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 10px;
            font-family: monospace;
            font-size: 14px;
        }
        .log-entry:last-child {
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Log i Mesazheve të Kontaktit</h2>
        <p class="subtitle">(Këto të dhëna janë ruajtur automatikisht në fajllin <code>logs/contact_log.txt</code> për qëllime auditimi)</p>
        <?php foreach ($logEntries as $entry): ?>
            <div class="log-entry">
                <?= htmlspecialchars($entry) ?>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
