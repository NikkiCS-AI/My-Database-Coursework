<?php
session_start();
header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION["User_id"])) {
    echo json_encode(["error" => "Not logged in"]);
    exit();
}
$userId = intval($_SESSION['User_id']);

$servername = "localhost";
$username = "root";
$password = "root";
$database = "coursework";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "DB connection failed"]);
    exit();
}


if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $stmt = $conn->prepare("SELECT Name, Email, Phone_no, Password, User_id FROM users WHERE User_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        echo json_encode($user);
    } else {
        echo json_encode(["error" => "User not found"]);
    }
    $stmt->close();
    $conn->close();
    exit();
}

// Handle POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $phone = $_POST["phone"] ;
    $password = $_POST["password"];

    $stmt = $conn->prepare("UPDATE users SET Email = ?, Phone_no = ?, Password = ? WHERE User_id = ?");
    $stmt->bind_param("sssi",$email, $phone, $password, $userId);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(["message" => "Details updated!"]);
    } else {
        echo json_encode(["message" => "No changes made."]);
    }

    $stmt->close();
    $conn->close();
    exit();
}
