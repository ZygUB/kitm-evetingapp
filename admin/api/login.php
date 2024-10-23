<?php
session_start();
require 'db.php';

$response = ['success' => false, 'error' => ''];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $username = $mysqli->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    
    $result = $mysqli->query("SELECT * FROM users WHERE username='$username' AND status='active'");
    
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        
        if (password_verify($password, $user['password'])) {
            
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            $response['success'] = true; 
            $response['role'] = $user['role']; 
        } else {
            $response['error'] = 'Incorrect password.';
        }
    } else {
        $response['error'] = 'User not found or banned.';
    }

    
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
