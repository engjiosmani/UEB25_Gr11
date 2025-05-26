<?php
session_start();
require_once 'klasat/Admin.php';
require_once 'error_handler.php';

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
    <style>
        body { font-family: Arial; background-color: #f2f2f2; }
        .container { max-width: 900px; margin: 40px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px #ccc; }
        h2 { text-align: center; color: #333; }
        .log-entry { border-bottom: 1px solid #ccc; padding: 10px 0; font-size: 14px; color: #444; }
        .log-entry:last-child { border-bottom: none; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Log i Mesazheve të Kontaktit</h2>
        <?php foreach ($logEntries as $entry): ?>
            <div class="log-entry"><?= htmlspecialchars($entry) ?></div>
        <?php endforeach; ?>
    </div>
</body>
</html>
