<?php
$rf = str_replace('www.', '', $_SERVER["SERVER_NAME"]);

$refix      = $config['database']['refix'] = "db_";
$host       = $config['database']['servername'] = 'localhost';
$username   = $config['database']['username'] = 'root';
$password   = $config['database']['password'] = '';
$dbname     = $config['database']['database'] = 'lt_007';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

define("URLPATH", "https://" . $_SERVER["SERVER_NAME"] . "/007-cozycandles/");
define("urladmin", "https://" . $_SERVER["SERVER_NAME"] . "/007-cozycandles/admin/");