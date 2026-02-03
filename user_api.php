<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

require 'db_connect.php';

$response = ["success" => false];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // --- 新增：處理 Update Name ---
    if (isset($_POST['action']) && $_POST['action'] === 'update_name') {
        if (isset($_POST['user_id']) && isset($_POST['full_name'])) {
            $userId = $conn->real_escape_string($_POST['user_id']);
            $fullName = $conn->real_escape_string($_POST['full_name']);

            $sql = "UPDATE users SET full_name = '$fullName' WHERE user_id = '$userId'";
            
            if ($conn->query($sql) === TRUE) {
                $response["success"] = true;
                $response["message"] = "Name updated successfully";
            } else {
                $response["message"] = "DB Error updating name";
                $response["error"] = $conn->error;
            }
        } else {
            $response["message"] = "Missing user_id or full_name";
        }
        echo json_encode($response);
        $conn->close();
        exit;
    }
    // ----------------------------

    // 處理登入 (Login)
    if (isset($_POST['phone']) && isset($_POST['password'])) {
        $phone = $conn->real_escape_string($_POST['phone']);
        $password = $conn->real_escape_string($_POST['password']);

        // 注意：正式環境建議使用 password_verify() 而不是明文比對
        $sql = "SELECT * FROM users WHERE phone='$phone' AND password='$password' LIMIT 1";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $response["success"] = true;
            $response["message"] = "Login successful";
            $response["data"] = $result->fetch_assoc();
        } else {
            $response["success"] = false;
            $response["message"] = "Invalid credentials";
            $response["error"] = "Invalid credentials";
        }

        echo json_encode($response);
        $conn->close();
        exit;
    }

    // 處理註冊 (Registration) - 讀取 JSON Body
    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input) {
        $response["message"] = "Invalid JSON";
        $response["error"] = "Invalid JSON";
        echo json_encode($response);
        $conn->close();
        exit;
    }

    $user_id   = $input['user_id']   ?? $input['userId']   ?? null;
    $username  = $input['username']  ?? null;
    $password  = $input['password']  ?? null;
    $full_name = $input['full_name'] ?? $input['fullName'] ?? null;
    $phone     = $input['phone']     ?? null;

    if (!$user_id || !$phone || !$username || !$password) {
        $response["message"] = "Missing data";
        $response["error"] = "Missing data";
        echo json_encode($response);
        $conn->close();
        exit;
    }

    $user_id   = $conn->real_escape_string($user_id);
    $username  = $conn->real_escape_string($username);
    $password  = $conn->real_escape_string($password);
    $full_name = $conn->real_escape_string($full_name ?? "");
    $phone     = $conn->real_escape_string($phone);

    $sql = "INSERT INTO users (user_id, username, password, full_name, phone)
            VALUES ('$user_id', '$username', '$password', '$full_name', '$phone')";

    if ($conn->query($sql) === TRUE) {
        $response["success"] = true;
        $response["message"] = "User registered successfully";
    } else {
        $response["success"] = false;
        $response["message"] = "DB Error";
        $response["error"] = $conn->error;
    }

    echo json_encode($response);
    $conn->close();
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['phone'])) {
        $phone = $conn->real_escape_string($_GET['phone']);
        $sql = "SELECT * FROM users WHERE phone='$phone' LIMIT 1";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $response["success"] = true;
            $response["data"] = $result->fetch_assoc();
        } else {
            $response["success"] = false;
            $response["message"] = "User not found";
            $response["error"] = "User not found";
        }
    } else if (isset($_GET['user_id'])) {
        $user_id = $conn->real_escape_string($_GET['user_id']);
        $sql = "SELECT * FROM users WHERE user_id='$user_id' LIMIT 1";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $response["success"] = true;
            $response["data"] = $result->fetch_assoc();
        } else {
            $response["success"] = false;
            $response["message"] = "User not found";
            $response["error"] = "User not found";
        }
    } else {
        $response["success"] = false;
        $response["message"] = "Missing query parameter";
        $response["error"] = "Missing query parameter";
    }

    echo json_encode($response);
    $conn->close();
    exit;
}

$response["message"] = "Unsupported request method";
$response["error"] = "Unsupported request method";
echo json_encode($response);
$conn->close();
?>
