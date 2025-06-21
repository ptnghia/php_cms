<?php
$rf = str_replace('www.', '', $_SERVER["SERVER_NAME"]);

$refix      = $config['database']['refix'] = "db_";
$host       = $config['database']['servername'] = 'localhost';
$username   = $config['database']['username'] = 'root';
$password   = $config['database']['password'] = 'eox_NUMEZB9g';
$database   = $config['database']['database'] = 'lt_014';

try {
    $conn = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("MariaDB connection failed: " . $e->getMessage());
}

define("URLPATH", "https://" . $_SERVER["SERVER_NAME"] . "/014-dichthuatbienxanh/");
define("urladmin", "https://" . $_SERVER["SERVER_NAME"] . "/014-dichthuatbienxanh/admin/");