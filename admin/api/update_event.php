<?php

header('Content-Type: application/json');
include 'db.php';

$id = $_POST['id'];
$status = $_POST['status'];

$stmt = $mysqli->prepare("UPDATE events SET status = ? WHERE id = ?");
$stmt->bind_param("si", $status, $id);

if ($stmt->execute()) {
    echo json_encode(['success' => 'Event updated']);
} else {
    echo json_encode(['error' => 'Failed to update event']);
}

$stmt->close();
$mysqli->close();
?>
