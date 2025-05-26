<?php
require_once 'db_conn.php';
require_once 'error_handler.php';
require_once 'klasat/User.php';
require_once 'klasat/Admin.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ruajtja e sugjerimit në fajll logs/suggestions.txt
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['artist_name'])) {
    $artist = trim(strip_tags($_POST['artist_name']));

    if (strlen($artist) < 2) {
        echo "<div class='alert alert-danger'>Emri i artistit është shumë i shkurtër.</div>";
        exit;
    }

    if (!isset($_SESSION['user'])) {
        echo "<div class='alert alert-danger'>Duhet të jeni të kyçur për të sugjeruar artist.</div>";
        exit;
    }

    $userId = $_SESSION['user']->id;
    $today = date("Y-m-d");
    $filename = "logs/suggestions.txt";

    if (!file_exists("logs")) {
        mkdir("logs", 0777, true);
    }

    // Kontrollo nëse ka sugjeruar sot
    if (file_exists($filename)) {
        $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos($line, "[$today") !== false && strpos($line, "| user:$userId") !== false) {
                echo "<div class='alert alert-warning'>Ju keni dërguar tashmë një sugjerim sot.</div>";
                exit;
            }
        }
    }

    // Shto sugjerimin në fajll
    $entry = "[" . date("Y-m-d H:i:s") . "] " . $artist . " | user:$userId\n";
    if (file_put_contents($filename, $entry, FILE_APPEND)) {
        echo "<div class='alert alert-success'>Faleminderit! Sugjerimi u ruajt me sukses.</div>";
    } else {
        echo "<div class='alert alert-danger'>Gabim gjatë ruajtjes së sugjerimit.</div>";
    }
    exit;
}



// --- Funksionaliteti për votim për artistin e preferuar ---
if (isset($_POST['vote_artist'])) {
    $artist = $_POST['vote_artist'];
    $user_id = $_SESSION['user']->id ?? null;

    if ($user_id && !empty($artist)) {
        $check = $conn->prepare("SELECT id FROM artist_votes WHERE user_id = ?");
        $check->bind_param("i", $user_id);
        $check->execute();
        $check->store_result();

        if ($check->num_rows === 0) {
            $stmt = $conn->prepare("INSERT INTO artist_votes (user_id, artist_name) VALUES (?, ?)");
            $stmt->bind_param("is", $user_id, $artist);
            if ($stmt->execute()) {
                echo "success";
            } else {
                echo "error_db";
            }
            $stmt->close();
        } else {
            echo "already_voted";
        }
        $check->close();
    } else {
        echo "invalid";
    }
    exit();
}

// --- Leximi i votave me AJAX ---
if (isset($_GET['get_votes'])) {
    $results = $conn->query("SELECT artist_name, COUNT(*) as votes FROM artist_votes GROUP BY artist_name");
    $votes = [];
    while ($row = $results->fetch_assoc()) {
        $votes[$row['artist_name']] = (int)$row['votes'];
    }
    header('Content-Type: application/json');
    echo json_encode($votes);
    exit();
}
