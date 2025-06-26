<?php 
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "root";
$database = "coursework";

// Connect DB
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "DB connection failed"]);
    exit();
}

// Handle POST (add customer)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userId = $conn->real_escape_string($_POST['userid']);
    $name = $conn->real_escape_string($_POST['name']);
    $company = $conn->real_escape_string($_POST['company']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $address = $conn->real_escape_string($_POST['address']);

    // Insert the customer data
    $sql = "INSERT INTO customer (Name, Company, Email, Phone_no, Address, User_id)
            VALUES ('$name', '$company', '$email', '$phone', '$address', '$userId')";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Customer added successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to insert customer"]);
    }

    $conn->close();
    exit();
}


// Handle PUT (update customer)
if ($_SERVER["REQUEST_METHOD"] === "PUT") {
    parse_str(file_get_contents("php://input"), $_PUT);
    $id = intval($_PUT['id']);
    $userId = $conn->real_escape_string($_PUT['userid']);
    $name = $conn->real_escape_string($_PUT['name']);
    $company = $conn->real_escape_string($_PUT['company']);
    $email = $conn->real_escape_string($_PUT['email']);
    $phone = $conn->real_escape_string($_PUT['phone']);
    $address = $conn->real_escape_string($_PUT['address']);

    $sql = "UPDATE customer SET User_id='$userId', Name='$name', Company='$company', Email='$email', Phone_no='$phone', Address='$address' WHERE Customer_id = $id";

    if ($conn->query($sql) === TRUE ) {
        echo json_encode(["status" => "success", "message" => "Customer updated"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Update failed"]);
    }
    $conn->close();
    exit();
}

// Handle DELETE
if ($_SERVER["REQUEST_METHOD"] === "DELETE") {
    parse_str(file_get_contents("php://input"), $_DELETE);
    $id = intval($_DELETE['id']);

    $conn->query("DELETE FROM interaction WHERE Customer_id = $id");
    $success = $conn->query("DELETE FROM customer WHERE Customer_id = $id");

    if ($success) {
        echo json_encode(["status" => "success", "message" => "Customer deleted"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Delete failed"]);
    }

    $conn->close();
    exit();
}

// Handle GET (fetch)
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $sql = "SELECT c.User_id, c.Customer_id AS customer_id, c.Name, c.Company, c.Email, c.Phone_no AS Phone_no, c.Address
                FROM customer c";

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

// Invalid
echo json_encode(["status" => "error", "message" => "Invalid request"]);
$conn->close();
?>
