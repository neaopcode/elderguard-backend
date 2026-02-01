<?php
// 引入連線設定
require_once 'db_connect.php';

$message = "";

// 如果有收到 POST 請求 (代表使用者按了送出)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];
    $full_name = $_POST['full_name'];

    // 簡單的 SQL 寫入指令
    $sql = "INSERT INTO users (user_id, username, password, phone, full_name) 
            VALUES ('$user_id', '$username', '$password', '$phone', '$full_name')";

    if ($conn->query($sql) === TRUE) {
        $message = "<h3 style='color:green;'>✅ 成功！資料已寫入資料庫！</h3>";
    } else {
        $message = "<h3 style='color:red;'>❌ 失敗：" . $conn->error . "</h3>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>資料庫寫入測試</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        input { display: block; margin: 10px 0; padding: 5px; width: 300px; }
        button { padding: 10px 20px; background: #007bff; color: white; border: none; cursor: pointer; }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>

    <h2>🛠️ Elder Guard 資料庫測試工具</h2>
    <p>這是一個用來測試 Database 是否能正常寫入的工具。</p>
    
    <?php echo $message; ?>

    <form method="post" action="">
        <label>User ID (模擬 ID):</label>
        <input type="text" name="user_id" value="<?php echo 'u' . rand(1000,9999); ?>" required>
        
        <label>Username (帳號):</label>
        <input type="text" name="username" placeholder="輸入帳號" required>
        
        <label>Password (密碼):</label>
        <input type="text" name="password" placeholder="輸入密碼" required>
        
        <label>Full Name (全名):</label>
        <input type="text" name="full_name" placeholder="輸入姓名" required>
        
        <label>Phone (電話):</label>
        <input type="text" name="phone" placeholder="輸入電話" required>
        
        <button type="submit">送出資料</button>
    </form>

    <hr>
    <h3>📊 目前資料庫中的 Users:</h3>
    <ul>
    <?php
    $result = $conn->query("SELECT * FROM users ORDER BY id DESC LIMIT 5");
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<li>ID: " . $row["user_id"] . " | Name: " . $row["full_name"] . " | Phone: " . $row["phone"] . "</li>";
        }
    } else {
        echo "<li>(目前沒有資料)</li>";
    }
    ?>
    </ul>

</body>
</html>
