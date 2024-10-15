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
```
after this upload the files into hosting and go to /admin/api/db.php and change these to your database info
```php
$host = 'localhost';  
$db = 'dbname';    
$user = 'dbuser'; 
$pass = 'password';  
$port = 3306; 
```
and you are done

## Usage

For now admins only can make events, and the auth is not made you can find admin in /admin/index.html

## WARNING
THIS IS ALPHA VERSION 1.0, AND YOU ADD THIS WEBSITE ON YOUR RISK!# kitm-evetingapp
