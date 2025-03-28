<?php
$rf = str_replace('www.', '', $_SERVER["SERVER_NAME"]);

// Database configuration
// Update the following variables with your database credentials.
$host = 'localhost'; // Database host (e.g., localhost)
$dbname = 'your_database_name'; // Name of your database
$username = 'your_username'; // Database username
$password = 'your_password'; // Database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

define("URLPATH", "https://" . $_SERVER["SERVER_NAME"] . "/003-beyond-ecom/");
define("urladmin", "https://" . $_SERVER["SERVER_NAME"] . "/003-beyond-ecom/admin/");
