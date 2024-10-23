<?php

$host = 'localhost';  
$db = 'u547284030_kitm';    
$user = 'u547284030_kitm'; 
$pass = 'g1=Vb&ARg';  
$port = 3306; 

   
$mysqli = new mysqli($host, $user, $pass, $db, $port);


if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>
