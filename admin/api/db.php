<?php

$host = 'localhost';  
$db = 'dbname';    
$user = 'dbuser'; 
$pass = 'password';  
$port = 3306; 

   
$mysqli = new mysqli($host, $user, $pass, $db, $port);


if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>
