# EvetingApp

EvetingApp is an events managment web app. (Currently it's in alpha 1.0)

## Installation

To install eventingapp you need:
phpmyadmin, mySQL.

Installation:
First you need to create database,
Second in your database click SQL and write this

```sql
CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    date DATE NOT NULL,
    location VARCHAR(255) NOT NULL,
    status VARCHAR(50) DEFAULT 'pending'
);
CREATE TABLE users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user', 
    status ENUM('active', 'banned') DEFAULT 'active'
);
```
after this go to https://bcrypt-generator.com/ write your admin password and choose rounds 10 then come back and write this command, but change values
```sql
INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`, `status`) 
VALUES (NULL, 'admin', 'your hashed password', 'youremail@gmail.com', 'admin', 'active');
```
after this upload the files into hosting and go to /admin/api/db.php and change these to your database info
```php
$host = 'localhost';  
$db = 'dbname';    
$user = 'dbuser'; 
$pass = 'password';  
$port = 3306; 
```
and you are done, enjoy

## Usage

You can ban users, manage events and add new, and other people can manage them as well

## WARNING
THIS IS ALPHA VERSION 1.0, AND YOU ADD THIS WEBSITE ON YOUR RISK!
