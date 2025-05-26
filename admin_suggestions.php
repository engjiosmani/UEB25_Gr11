<?php
$filename = 'logs/suggestions.txt';
echo "<h2><i class='bi bi-star-fill text-warning'></i> Sugjerimet për Artistët 2026</h2>";
if (file_exists($filename)) {
    $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (count($lines) === 0) {
        echo "<p class='text-muted'>Nuk ka ende sugjerime.</p>";
    } else {
        echo "<ul class='list-group'>";
        foreach ($lines as $line) {
            echo "<li class='list-group-item'>" . htmlspecialchars($line) . "</li>";
        }
        echo "</ul>";
    }
} else {
    echo "<p class='text-muted'>Nuk është krijuar fajlli i sugjerimeve.</p>";
}
?>
