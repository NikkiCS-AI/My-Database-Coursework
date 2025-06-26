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

// Connect DB
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "DB connection failed"]);
    exit();
}

// Handle POST (add user)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);


    $sql = "INSERT INTO users (Name, Email, Phone_no)
            VALUES ('$name', '$email', '$phone')" ;

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["status" => "success", "message" => "User added successfully"]);
        } else {
        echo json_encode(["status" => "error", "message" => "Failed to insert user"]);
    }

    $conn->close();
    exit();
}

// Handle PUT (update user)
if ($_SERVER["REQUEST_METHOD"] === "PUT") {
    parse_str(file_get_contents("php://input"), $_PUT);
    $userid = intval($_PUT['userid']);
    $name = $conn->real_escape_string($_PUT['name']);
    $email = $conn->real_escape_string($_PUT['email']);
    $phone = $conn->real_escape_string($_PUT['phone']);

    $sql = "UPDATE users SET Name='$name', Email='$email', Phone_no='$phone' WHERE User_id=$userid";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "User updated"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Update failed"]);
    }
    $conn->close();
    exit();
}

// Handle DELETE
if ($_SERVER["REQUEST_METHOD"] === "DELETE") {
    parse_str(file_get_contents("php://input"), $_DELETE);
    $userId = intval($_DELETE['userid']);

    $conn->query("DELETE FROM lead WHERE User_id = $userId");
    $success = $conn->query("DELETE FROM users WHERE User_id = $userId");

    if ($success) {
        echo json_encode(["status" => "success", "message" => "User deleted"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Delete failed"]);
    }

    $conn->close();
    exit();
}

// Handle GET (fetch)
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $sql = "SELECT 
                users.User_id , users.Name, users.Email, users.Phone_no AS Phone_no
            FROM users";

    $result = $conn->query($sql);
    $users = [];

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }

    echo json_encode(["status" => "success", "data" => $users]);
    $conn->close();
    exit();
}

// Invalid
echo json_encode(["status" => "error", "message" => "Invalid request"]);
$conn->close();
?>
