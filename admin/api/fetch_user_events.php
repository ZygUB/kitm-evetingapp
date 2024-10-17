<?php
session_start();
require 'db.php';

header('Content-Type: application/json');

if (!isset($_GET['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'User ID not provided.']);
    exit();
}

$user_id = intval($_GET['user_id']);

if ($stmt = $mysqli->prepare("SELECT id, name, date, location, category, status FROM events WHERE user_id = ?")) {
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $events = [];
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }
        echo json_encode(['success' => true, 'events' => $events]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to execute query.']);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to prepare statement.']);
}
?>
