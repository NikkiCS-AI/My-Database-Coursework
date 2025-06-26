<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "root";
$database = "coursework";

// Check session
if (!isset($_SESSION['User_id'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit();
}

$userId = intval($_SESSION['User_id']);

// Connect DB
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "DB connection failed"]);
    exit();
}
//deleting a customer
if (isset($_POST['deleteCustomer']) && isset($_POST['Customer_id'])) {
    $customerId = intval($_POST['Customer_id']);
    $deleteQuery = $conn->prepare("DELETE FROM customer WHERE Customer_id = ?");
    $deleteQuery->bind_param("i", $customerId);

    if ($deleteQuery->execute()) {
        echo json_encode(["status" => "success", "message" => "Customer deleted successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to delete customer."]);
    }

    $conn->close();
    exit();
}
function generateNewCustomerId($conn) {
    $customerResult = $conn->query("SELECT MAX(Customer_id) AS max_id FROM customer");
    $leadResult = $conn->query("SELECT MAX(Customer_id) AS maxlead_id FROM lead");

    $customerRow = $customerResult->fetch_assoc();
    $leadRow = $leadResult->fetch_assoc();

    $maxCustomerId = $customerRow['max_id'] ?? 0;
    $maxLeadCustomerId = $leadRow['maxlead_id'] ?? 0;

    $newId = max($maxCustomerId, $maxLeadCustomerId) + 1;
    return $newId;
}

// Handle POST (add customer)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $company = $conn->real_escape_string($_POST['company']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $address = $conn->real_escape_string($_POST['address']);
    $leadId = isset($_POST['lead_id']) && $_POST['lead_id'] !== '' ? intval($_POST['lead_id']) : null;

    $customerId = generateNewCustomerId($conn);
    if ($leadId) {
        // Check if the Lead ID exists and fetch the customer_id linked to it
        $checkLead = $conn->query("SELECT Customer_id FROM lead WHERE Lead_id = $leadId AND User_id = $userId");
        if ($checkLead->num_rows === 0) {
            echo json_encode(["status" => "error", "message" => "Invalid Lead ID"]);
            $conn->close();
            exit();
        } else {
            $leadData = $checkLead->fetch_assoc();
            $customerId = intval($leadData['Customer_id']);
        }
    }

        
if ($customerId) {
    $leadIdValue = $leadId !== null ? $leadId : 'NULL';

$sql = "INSERT INTO customer (Customer_id, Name, Company, Email, Phone_no, Address, User_id, Lead_id)
        VALUES ($customerId, '$name', '$company', '$email', '$phone', '$address', $userId, $leadIdValue)";
}

if ($conn->query($sql) === TRUE) {
    echo json_encode(["status" => "success", "message" => "Customer added successfully"]);
    } else {
    echo json_encode(["status" => "error", "message" => "Failed to insert customer: " . $conn->error]);
    }

$conn->close();
exit();
}

// Handle PUT (update customer)
if ($_SERVER["REQUEST_METHOD"] === "PUT") {
    parse_str(file_get_contents("php://input"), $_PUT);
    $id = intval($_PUT['id']);
    $name = $conn->real_escape_string($_PUT['name']);
    $company = $conn->real_escape_string($_PUT['company']);
    $email = $conn->real_escape_string($_PUT['email']);
    $phone = $conn->real_escape_string($_PUT['phone']);
    $address = $conn->real_escape_string($_PUT['address']);

    $sql = "UPDATE customer SET Name='$name', Company='$company', Email='$email', Phone_no='$phone', Address='$address' WHERE Customer_id = $id AND User_id = $userId";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Customer updated"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Update failed"]);
    }
    $conn->close();
    exit();
}


if ($_SERVER["REQUEST_METHOD"] === "GET") {
    if (isset($_GET['fetchLeads'])) {
        $leadsResult = $conn->query("SELECT Lead_id FROM lead WHERE User_id=$userId");
        $leads = [];

        while ($row = $leadsResult->fetch_assoc()) {
            $leads[] = $row;
        }

        echo json_encode(["status" => "success", "leads" => $leads]);
        $conn->close();
        exit();
    }

    $sql = "SELECT c.Customer_id AS customer_id, c.Name, c.Company, c.Email, c.Phone_no, c.Address, c.Lead_id,
                GROUP_CONCAT(CONCAT(i.type, ' - ', i.date) ORDER BY i.date ASC SEPARATOR '<br>') AS interaction
            FROM customer c
            LEFT JOIN interaction i ON c.Customer_id = i.Customer_id
            WHERE c.User_id = $userId
            GROUP BY c.Customer_id, c.Name, c.Email, c.Phone_no";

    $result = $conn->query($sql);
    $customers = [];

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $customers[] = $row;
        }
    }

    echo json_encode(["status" => "success", "data" => $customers]);
    $conn->close();
    exit();
}

// Invalid request
echo json_encode(["status" => "error", "message" => "Invalid request"]);
$conn->close();
?>
