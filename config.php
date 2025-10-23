
<?php

$host = '127.0.0.1';
$user = 'root';
$pass = '';
$db   = 'your_database_name';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die('Connect error: ' . $conn->connect_error);
}
