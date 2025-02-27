<?php
$host = 'localhost';
$db = 'check_in';
$user = 'root';
$pass = '';

$connection = new mysqli($host, $user, $pass, $db);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// session_start();
?>