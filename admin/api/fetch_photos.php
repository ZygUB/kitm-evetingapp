<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db.php';

$event_id = $_GET['event_id'] ?? null;

if (!$event_id) {
    echo json_encode(['error' => 'Event ID is required.']);
    exit;
}

$stmt = $mysqli->prepare("SELECT photo_path FROM photos WHERE event_id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();

$photos = [];
while ($row = $result->fetch_assoc()) {
    $photos[] = ['photo_path' => $row['photo_path']];
}

echo json_encode($photos);

$stmt->close();
$mysqli->close();
?>
