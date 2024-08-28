<?php
// db.php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'pets_db';

$connection = new mysqli($host, $user, $password, $database);

if ($connection->connect_error) {
    die(json_encode(['error' => 'Database connection failed: ' . $connection->connect_error]));
}
?>
