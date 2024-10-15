<?php

header('Content-Type: application/json');
include 'db.php';

$id = $_POST['id'];

$stmt = $mysqli->prepare("DELETE FROM events WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(['success' => 'Event deleted']);
} else {
    echo json_encode(['error' => 'Failed to delete event']);
}

$stmt->close();
$mysqli->close();
?>
