<?php
require_once 'db_conn.php';
require_once 'error_handler.php';
require_once 'klasat/User.php';
require_once 'klasat/Admin.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['theme'])) {
    setcookie('theme', $_POST['theme'], time() + (86400 * 30), "/");
    echo "changed";
    exit();
}

// --- Funksionaliteti për votim për artistin e preferuar ---
if (isset($_POST['vote_artist'])) {
    $artist = $_POST['vote_artist'];
    $user_id = $_SESSION['user']->id ?? null;

    if ($user_id && !empty($artist)) {
        // Kontrollo nëse përdoruesi ka votuar më parë
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
?>
