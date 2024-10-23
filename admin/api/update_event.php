<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db.php';

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'] ?? null;
$status = $data['status'] ?? null;

if (!$id || !$status) {
    echo json_encode(['error' => 'ID and status are required.']);
    exit;
}

$stmt = $mysqli->prepare("UPDATE events SET status = ? WHERE id = ?");
$stmt->bind_param("si", $status, $id);

if ($stmt->execute()) {
    echo json_encode(['success' => 'Event updated']);
} else {
    echo json_encode(['error' => 'Failed to update event: ' . $stmt->error]);
}

$stmt->close();
$mysqli->close();
?>
