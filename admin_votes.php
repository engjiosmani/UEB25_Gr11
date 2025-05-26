<?php
require_once 'db_conn.php';
session_start();

// Sigurohu që është admin
if (!isset($_SESSION['user']) || $_SESSION['user']->role !== 'admin') {
    header("Location: login.php");
    exit;
}

$result = $conn->query("SELECT artist_name, COUNT(*) as votes FROM artist_votes GROUP BY artist_name");

echo "<h2>Rezultatet e Votimit për Artistë</h2>";
echo "<table border='1'><tr><th>Artist</th><th>Numri i Votave</th></tr>";
while ($row = $result->fetch_assoc()) {
    echo "<tr><td>{$row['artist_name']}</td><td>{$row['votes']}</td></tr>";
}
echo "</table>";
?>
