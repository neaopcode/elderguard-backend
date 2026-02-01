<?php
// 開啟錯誤顯示 (Debug用)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

$conn->query("SET time_zone = '+08:00'");

$conn->set_charset("utf8mb4");
?>


