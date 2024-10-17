<?php
include 'db.php';

$sql = "SELECT * FROM events";
$result = $mysqli->query($sql);

if (!$result) {
    echo json_encode(['error' => 'Failed to fetch events']);
    exit;
}

$events = [];
while ($row = $result->fetch_assoc()) {
    $events[] = [
        'id' => $row['id'],
        'title' => $row['name'],  
        'start' => $row['date'], 
        'extendedProps' => [
            'location' => $row['location'],
            'category' => $row['category'],
            'status' => $row['status']
        ]
    ];
}

echo json_encode($events);

$mysqli->close();
?>
