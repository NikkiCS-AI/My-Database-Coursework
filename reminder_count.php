<?php


session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);


$servername = "localhost"; 
$username = "root";  
$password = "root"; 
$dbname = "coursework"; 
if (!isset($_SESSION['User_id'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit();
}

$userId = intval($_SESSION['User_id']);


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    
    http_response_code(500);
    echo "Database connection failed: " . $conn->connect_error;
    exit();
}


$sql = "SELECT COUNT(*) AS reminder_count FROM reminder WHERE Reminder_status = 'Pending'AND User_id='$userId'";
$result = $conn->query($sql);

if (!$result) {
    http_response_code(500);
    echo "Query error: " . $conn->error;
    $conn->close();
    exit();
}

$row = $result->fetch_assoc();
$count = $row['reminder_count'] ?? 0;

echo $count;

$conn->close();
?>
