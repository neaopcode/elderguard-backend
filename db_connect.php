<?php

$servername = getenv('MYSQLHOST') ? getenv('MYSQLHOST') : 'localhost';
$username = getenv('MYSQLUSER') ? getenv('MYSQLUSER') : 'root';
$password = getenv('MYSQLPASSWORD') ? getenv('MYSQLPASSWORD') : '';
$dbname = getenv('MYSQLDATABASE') ? getenv('MYSQLDATABASE') : 'test_db';
$port = getenv('MYSQLPORT') ? getenv('MYSQLPORT') : 3306;

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die(json_encode(array(
        "success" => false,
        "message" => "Connection failed: " . $conn->connect_error
    )));
}

$conn->set_charset("utf8mb4");
?>
