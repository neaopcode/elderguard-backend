<?php
header("Content-Type: application/json; charset=UTF-8");
require_once 'db_connect.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['user_id']) && isset($_POST['latitude']) && isset($_POST['longitude'])) {
        
        $user_id = $_POST['user_id'];
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];
        $timestamp = time();
        
        $stmt = $conn->prepare("INSERT INTO fall_alerts (user_id_fk, latitude, longitude, timestamp, status) VALUES (?, ?, ?, ?, 'detected')");
        
        if ($stmt) {
            $stmt->bind_param("sddi", $user_id, $latitude, $longitude, $timestamp);
            
            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = "Fall alert recorded successfully";
                $response['alert_id'] = $stmt->insert_id;
            } else {
                $response['success'] = false;
                $response['message'] = "Database error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $response['success'] = false;
            $response['message'] = "Statement preparation failed";
        }
    } else {
        $response['success'] = false;
        $response['message'] = "Missing required parameters (user_id, latitude, longitude)";
    }
} else {
    $response['success'] = false;
    $response['message'] = "Invalid request method";
}

echo json_encode($response);
$conn->close();
?>
