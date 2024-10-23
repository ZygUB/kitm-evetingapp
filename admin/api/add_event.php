<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $date = $_POST['date'] ?? '';
    $location = $_POST['location'] ?? '';
    $category = $_POST['category'] ?? '';
    $status = $_POST['status'] ?? 'pending';

    // Check required fields
    if (empty($name) || empty($date) || empty($location) || empty($category)) {
        echo json_encode(['error' => 'All fields are required.']);
        exit;
    }

    $stmt = $mysqli->prepare("INSERT INTO events (name, date, location, category, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $date, $location, $category, $status);

    if ($stmt->execute()) {
        $event_id = $stmt->insert_id; // Get the newly inserted event ID

        // Handle file uploads
        if (!empty($_FILES['photos']['name'][0])) {
            $upload_dir = __DIR__ . '/../uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            foreach ($_FILES['photos']['name'] as $key => $photo_name) {
                $photo_tmp_name = $_FILES['photos']['tmp_name'][$key];
                $photo_path = $upload_dir . basename($photo_name);

                if (move_uploaded_file($photo_tmp_name, $photo_path)) {
                    // Insert photo path into database
                    $photo_stmt = $mysqli->prepare("INSERT INTO photos (event_id, photo_path) VALUES (?, ?)");
                    $photo_stmt->bind_param("is", $event_id, $photo_path);
                    $photo_stmt->execute();
                    $photo_stmt->close();
                }
            }
        }

        echo json_encode(['success' => 'Event added']);
    } else {
        echo json_encode(['error' => 'Failed to add event: ' . $stmt->error]);
    }

    $stmt->close();
}
$mysqli->close();
?>
