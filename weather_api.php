<?php
$apiKey = "804904a1c3c6554a833f2c1e64deefc1"; 
$city = urlencode("New York");
$url = "https://api.openweathermap.org/data/2.5/weather?q=$city&appid=$apiKey&units=metric";

$response = file_get_contents($url);

if ($response !== false) {
    header('Content-Type: application/json');
    echo $response;
} else {
    echo json_encode(["error" => "Gabim gjatë kërkesës."]);
}
