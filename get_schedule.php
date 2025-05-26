<?php
require_once 'db_conn.php'; // lidhja me DB
header('Content-Type: application/json');

$day = $_GET['day'] ?? null;
$where = $day ? "WHERE event_day=?" : "";
$stmt = $conn->prepare("SELECT * FROM events $where");
if ($day) $stmt->bind_param("s", $day);
$stmt->execute();
$result = $stmt->get_result();
$events = [];
while ($row = $result->fetch_assoc()) {
    $events[] = $row;
}
echo json_encode($events);
?>
