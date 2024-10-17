<?php
include 'db.php';

$event_id = $_GET['event_id'];

if (empty($event_id)) {
    echo json_encode(['error' => 'Invalid event ID']);
    exit;
}

$stmt = $mysqli->prepare("SELECT * FROM events WHERE id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $event = $result->fetch_assoc();

    $photo_stmt = $mysqli->prepare("SELECT photo_path FROM photos WHERE event_id = ?");
    $photo_stmt->bind_param("i", $event_id);
    $photo_stmt->execute();
    $photo_result = $photo_stmt->get_result();

    $photos = [];
    while ($row = $photo_result->fetch_assoc()) {
        $photos[] = [
            'photo_path' => './admin/uploads/' . basename($row['photo_path'])
        ];
    }

    $event['photos'] = $photos;

    echo json_encode($event); 
} else {
    echo json_encode(['error' => 'Event not found']);
}

$stmt->close();
$mysqli->close();
?>
