<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "root";
$database = "coursework";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get inputs
$email = $conn->real_escape_string($_POST['email']);
$password = $conn->real_escape_string($_POST['password']);
$role = $conn->real_escape_string($_POST['role']);



// Check for valid user
$sql = "SELECT * FROM users WHERE Email='$email' AND Password='$password' AND role='$role'";
$result = $conn->query($sql);



if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $_SESSION['User_id'] = $user['User_id']; 

    if ($role === 'admin') {
        header("Location: admin_dashboard.html");
     
    } elseif ($role === 'sales') {
        header("Location: sales_dashboard.html");
      
    }
    
} else {
    $_SESSION['error'] = " Invalid email, password or role.";
    header("Location: login.html?error=1");
    include 'login.html';    
}

$conn->close();
?>
