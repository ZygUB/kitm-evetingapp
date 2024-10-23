<?php
require 'db.php';

$response = ['success' => false, 'error' => ''];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $mysqli->real_escape_string($_POST['username']);
    $email = $mysqli->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $checkUser = $mysqli->query("SELECT * FROM users WHERE username='$username' OR email='$email'");
    if ($checkUser->num_rows > 0) {
        $response['error'] = 'Username or Email already exists.';
    } else {
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
        if ($mysqli->query($sql)) {
            $response['success'] = true;
        } else {
            $response['error'] = 'Error: ' . $mysqli->error;
        }
    }
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
