<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db.php';

$input = json_decode(file_get_contents('php://input'), true); // Decode the JSON input
$id = $input['id'] ?? null; // Get the 'id' from the decoded input

if (!$id) {
    echo json_encode(['error' => 'Event ID is required.']);
    exit;
}

$stmt = $mysqli->prepare("DELETE FROM events WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(['success' => 'Event deleted']);
} else {
    echo json_encode(['error' => 'Failed to delete event: ' . $stmt->error]);
}

$stmt->close();
$mysqli->close();
?>
