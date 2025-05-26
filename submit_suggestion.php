<?php
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['artist_name'])) {
    $artist = trim($_POST['artist_name']);
    $artist = strip_tags($artist);

    if (strlen($artist) < 2) {
        echo "<div class='alert alert-danger'>Emri i artistit është shumë i shkurtër.</div>";
        exit;
    }

    $filename = "logs/suggestions.txt";
    if (!file_exists("logs")) {
        mkdir("logs", 0777, true);
    }

    $entry = "[" . date("Y-m-d H:i:s") . "] " . $artist . "\n";
    if (file_put_contents($filename, $entry, FILE_APPEND)) {
        echo "<div class='alert alert-success'>Faleminderit! Sugjerimi u ruajt me sukses.</div>";
    } else {
        echo "<div class='alert alert-danger'>Gabim gjatë ruajtjes së sugjerimit.</div>";
    }
}
?>
