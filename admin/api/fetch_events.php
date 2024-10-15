<?php

header('Content-Type: application/json');
include 'db.php'; 

$sql = "SELECT * FROM events";
$result = $mysqli->query($sql);

if (!$result) {
    echo json_encode(['error' => 'Failed to fetch events']);
    exit;
}

$events = [];
while ($row = $result->fetch_assoc()) {
    $events[] = $row;
}

echo json_encode($events);

$mysqli->close();
?>
