<?php
include 'config.php';  // 你的 config
if ($conn) {
    echo "資料庫連線成功！";
} else {
    echo "連線失敗：" . mysqli_connect_error();
}
?>
