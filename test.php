<?php
// test.php

// 1. 開啟錯誤顯示
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. 正確引入 db_connect.php (不是 config.php)
require_once 'db_connect.php'; 

// 3. 檢查 $conn 是否存在
if (isset($conn) && $conn instanceof mysqli) {
    echo "Database connection successful! <br>";
    echo "Host: " . getenv('MYSQLHOST') . "<br>";
    echo "Database: " . getenv('MYSQLDATABASE');
} else {
    echo "Database connection FAILED. Variable \$conn is missing.";
}
?>
