<?php

$host = 'localhost';  
$db = 'dbname';    
$user = 'dbusername'; 
$pass = 'dbpassword';  
$port = 3306; 

   
$mysqli = new mysqli($host, $user, $pass, $db, $port);


if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>
