<?php
include 'db.php';

$event_id = $_GET['event_id'];

if (empty($event_id)) {
    echo json_encode(['error' => 'Invalid event ID']);
    exit;
}

$stmt = $mysqli->prepare("SELECT photo_path FROM photos WHERE event_id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();

$photos = [];
while ($row = $result->fetch_assoc()) {
    $photos[] = ['photo_path' => str_replace(__DIR__ . '/../', '', $row['photo_path'])];  // Relative path for the browser
}

echo json_encode($photos);

$stmt->close();
$mysqli->close();
?>
