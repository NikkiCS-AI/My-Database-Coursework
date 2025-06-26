<?php 
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "root";
$database = "coursework";

$userId = intval($_SESSION['User_id']);

// DB connection
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "DB connection failed"]);
    exit();
}

// Handle GET
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $sql = "SELECT lead.Lead_id, lead.Customer_id, lead.User_id, users.Name, lead.status, lead.follow_up_date, lead.lead_notes
            FROM lead
            INNER JOIN users ON users.User_id = lead.User_id";


    $result = $conn->query($sql);
    $leads = [];

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $leads[] = $row;
        }
    }

    echo json_encode(["status" => "success", "data" => $leads]);
    $conn->close();
    exit();
}

echo json_encode(["status" => "error", "message" => "Invalid request"]);
$conn->close();
?>
