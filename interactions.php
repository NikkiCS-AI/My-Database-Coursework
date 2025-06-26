<?php 
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "root";
$database = "coursework";

// Check if the user is logged in
if (!isset($_SESSION['User_id'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit();
}

$userId = intval($_SESSION['User_id']);

// Connect to the database
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "DB connection failed"]);
    exit();
}

// Handle POST (add interaction)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get form data for the interaction
    $customer_id = intval($_POST['customer-id']);  // Customer ID must exist
    $type = $conn->real_escape_string($_POST['type']);
    $date = $conn->real_escape_string($_POST['date']);
    $description = $conn->real_escape_string($_POST['description']);
    

    // Insert interaction for an existing customer
    $sql = "INSERT INTO interaction (Customer_id, type,date, description, User_id)
            VALUES ('$customer_id', '$type', '$date','$description','$userId')";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Interaction added successfully"]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Failed to insert interaction: " . $conn->error]);
    }

    $conn->close();
    exit();
}

// Handle PUT (update interaction)
if ($_SERVER["REQUEST_METHOD"] === "PUT") {
    parse_str(file_get_contents("php://input"), $_PUT);
    $id = intval($_PUT['id']);  // Interaction ID to update 
    $customer_id = $conn->real_escape_string($_PUT['customerid']);
    $type = $conn->real_escape_string($_PUT['type']);
    $date = $conn->real_escape_string($_PUT['date']);
    $description = $conn->real_escape_string($_PUT['description']);

    // Update the interaction based on Interaction_id
    $sql = "UPDATE interaction SET Customer_id='$customer_id',description='$description', date='$date', type='$type' WHERE Interaction_id = $id AND User_id = $userId";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Interaction updated"]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Update failed: " . $conn->error
        ]);
    }
    $conn->close();
    exit();
}

// Handle DELETE (delete interaction)
if ($_SERVER["REQUEST_METHOD"] === "DELETE") {
    parse_str(file_get_contents("php://input"), $_DELETE);
    $id = intval($_DELETE['id']);  // Interaction ID to delete

    // Delete interaction by Interaction_id
    $sql = "DELETE FROM interaction WHERE Interaction_id = $id AND User_id = $userId";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Interaction deleted"]);
    } else {
        echo json_encode([
            "status" => "error", "message" => "Delete failed: " . $conn->error]);
    }
    $conn->close();
    exit();
}

// Handle GET (fetch interactions)
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    // Fetch all interactions for the logged-in user
    $sql = "SELECT 
                i.Customer_id,i.Interaction_id, i.description, i.type, i.date, c.name 
            FROM interaction i
            LEFT JOIN customer c ON i.Customer_id = c.Customer_id
            WHERE i.User_id = $userId";

    $result = $conn->query($sql);
    $interactions = [];

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $interactions[] = $row;
        }
    }

    echo json_encode(["status" => "success", "data" => $interactions]);
    $conn->close();
    exit();
}

echo json_encode(["status" => "error", "message" => "Invalid request"]);
$conn->close();
?>
