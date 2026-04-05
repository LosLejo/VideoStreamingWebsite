<?php
require_once 'config.php'; // Load environment variables

$host = DB_HOST;
$user = DB_USER;
$password = DB_PASSWORD;
$database = DB_NAME;

$mysqli = new mysqli($host, $user, $password, $database);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>
