<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

require 'db_connect.php';

$json_data = file_get_contents("php://input");
$data = json_decode($json_data, true);

$response = array();

if ($data) {
    $user_id = isset($data['userId']) ? $conn->real_escape_string($data['userId']) : '';
    
    $activity_id = isset($data['id']) ? $conn->real_escape_string($data['id']) : '';
    $name = isset($data['name']) ? $conn->real_escape_string($data['name']) : '';
    $type = isset($data['type']) ? $conn->real_escape_string($data['type']) : '';
    $description = isset($data['description']) ? $conn->real_escape_string($data['description']) : '';
    $start_time = isset($data['startTime']) ? $conn->real_escape_string($data['startTime']) : 0;
    $end_time = isset($data['endTime']) ? $conn->real_escape_string($data['endTime']) : 0;

    if (empty($activity_id) || empty($user_id)) {
        $response['success'] = false;
        $response['message'] = "Missing ID or UserID";
    } else {
        $sql = "INSERT INTO activities (activity_id, name, type, description, start_time, end_time, user_id_fk) 
                VALUES ('$activity_id', '$name', '$type', '$description', '$start_time', '$end_time', '$user_id')
                ON DUPLICATE KEY UPDATE 
                name='$name', type='$type', description='$description', start_time='$start_time', end_time='$end_time'";

        if ($conn->query($sql) === TRUE) {
            $response['success'] = true;
            $response['message'] = "Activity saved";
        } else {
            $response['success'] = false;
            $response['message'] = "DB Error: " . $conn->error;
        }
    }
} else {
    $response['success'] = false;
    $response['message'] = "Invalid JSON data";
}

echo json_encode($response);
$conn->close();
?>
