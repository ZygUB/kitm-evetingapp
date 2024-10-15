<?php

header('Content-Type: application/json');
include 'db.php';

$name = $_POST['name'];
$date = $_POST['date'];
$location = $_POST['location'];
$status = $_POST['status'] ?? 'pending';

$stmt = $mysqli->prepare("INSERT INTO events (name, date, location, status) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $date, $location, $status);

if ($stmt->execute()) {
    echo json_encode(['success' => 'Event added', 'id' => $stmt->insert_id]);
} else {
    echo json_encode(['error' => 'Failed to add event']);
}

$stmt->close();
$mysqli->close();
?>
