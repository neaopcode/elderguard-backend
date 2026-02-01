<?php
header("Content-Type: application/json; charset=UTF-8");
require_once 'db_connect.php';

$response = array();

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($input['contact_name']) ? $input['contact_name'] : (isset($_POST['contact_name']) ? $_POST['contact_name'] : null);
    $phone = isset($input['contact_phone']) ? $input['contact_phone'] : (isset($_POST['contact_phone']) ? $_POST['contact_phone'] : null);
    $user_id = isset($input['user_id_fk']) ? $input['user_id_fk'] : (isset($_POST['user_id_fk']) ? $_POST['user_id_fk'] : null);
    
    
    if ($name && $phone && $user_id) {
        $sql = "INSERT INTO emergency_contacts (contact_name, contact_phone, user_id_fk) VALUES ('$name', '$phone', '$user_id')";
        
        if ($conn->query($sql) === TRUE) {
             $response['success'] = true;
             $response['message'] = "Contact added";
             $response['id'] = $conn->insert_id;
        } else {
             $response['success'] = false;
             $response['message'] = "Error: " . $conn->error;
        }
    } else {
        $response['success'] = false;
        $response['message'] = "Missing data: name=$name, phone=$phone, user_id=$user_id";
    }

} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['user_id_fk'])) {
        $user_id = $_GET['user_id_fk'];
        $sql = "SELECT * FROM emergency_contacts WHERE user_id_fk = '$user_id'";
        $result = $conn->query($sql);
        
        $contacts = array();
        while($row = $result->fetch_assoc()) {
            $contacts[] = $row;
        }
        
        $response['success'] = true;
        $response['data'] = $contacts;
    } else {
        $response['success'] = false;
        $response['message'] = "Missing user_id_fk";
    }
} else {
    $response['success'] = false;
    $response['message'] = "Invalid request method";
}

echo json_encode($response);
$conn->close();
?>
