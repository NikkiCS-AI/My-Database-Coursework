<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "root";
$database = "coursework";

if (!isset($_SESSION['User_id'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit();
}

$userId = intval($_SESSION['User_id']);

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "DB connection failed"]);
    exit();
}

function generateNewCustomerId($conn) {
    $customerResult = $conn->query("SELECT MAX(Customer_id) AS max_id FROM customer");
    $leadResult = $conn->query("SELECT MAX(Customer_id) AS maxlead_id FROM lead");

    $customerRow = $customerResult->fetch_assoc();
    $leadRow = $leadResult->fetch_assoc();

    $maxCustomerId = isset($customerRow['max_id']) ? (int)$customerRow['max_id'] : 0;
    $maxLeadCustomerId = isset($leadRow['maxlead_id']) ? (int)$leadRow['maxlead_id'] : 0;

    // Pick the larger one, then add 1
    $newId = max($maxCustomerId, $maxLeadCustomerId) + 1;

    return $newId;
}

// Handle POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $status = $conn->real_escape_string($_POST['status']);
    $followUpDate = $conn->real_escape_string($_POST['followup']);
    $leadNotes = $conn->real_escape_string($_POST['notes']);

    $customerId = null;
    if (strtolower(trim($status)) === "closed") { 
        $customerId = generateNewCustomerId($conn);
    }

    $sql = "INSERT INTO lead (Customer_id, User_id, status, follow_up_date, lead_notes)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisss", $customerId, $userId, $status, $followUpDate, $leadNotes);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Lead added successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to insert lead"]);
    }

    $stmt->close();
    $conn->close();
    exit();
}

// Handle PUT
if ($_SERVER["REQUEST_METHOD"] === "PUT") {
    parse_str(file_get_contents("php://input"), $_PUT);
    $leadId = intval($_PUT['leadId']);
    $status = $conn->real_escape_string($_PUT['status']);
    $followUpDate = $conn->real_escape_string($_PUT['followup']);
    $leadNotes = $conn->real_escape_string($_PUT['notes']);

    $updateCustomerId = ""; 
    if (strtolower(trim($status)) === "closed") { 
        $newCustomerId = generateNewCustomerId($conn);
        $updateCustomerId = ", Customer_id = $newCustomerId"; 
    }

    $sql = "UPDATE lead SET status='$status', follow_up_date='$followUpDate', lead_notes='$leadNotes' $updateCustomerId
            WHERE Lead_id = $leadId AND User_id = $userId";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Lead updated"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Update failed"]);
    }

    $conn->close();
    exit();
}

// Handle DELETE
if ($_SERVER["REQUEST_METHOD"] === "DELETE") {
    parse_str(file_get_contents("php://input"), $_DELETE);
    $leadId = intval($_DELETE['Lead_id']);

    $success = $conn->query("DELETE FROM lead WHERE Lead_id = $leadId AND User_id = $userId");

    if ($success) {
        echo json_encode(["status" => "success", "message" => "Lead deleted"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Delete failed"]);
    }
    $conn->close();
    exit();
}

// Handle GET
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $sql = "SELECT Lead_id, Customer_id, User_id, status, follow_up_date, lead_notes
            FROM lead
            WHERE User_id = $userId";

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
