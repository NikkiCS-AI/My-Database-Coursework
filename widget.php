<?php
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// // Only allow admin to fetch widget data
// //if (!isset($_SESSION['User_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
//     echo json_encode(["error" => "Access denied"]);
//     exit;
// }

$conn = new mysqli('localhost', 'root', 'root', 'coursework');
if ($conn->connect_error) die("Database connection failed: " . $conn->connect_error);

$response = [
    'totalLeads' => 0,
    'newLeads' => 0,
    'completedLeads' => 0,
    'upcomingFollowups' => 0,
    'overdueFollowups' => 0
];

// Total Leads
$result = $conn->query("SELECT COUNT(*) AS total FROM lead");
if ($row = $result->fetch_assoc()) $response['totalLeads'] = $row['total'];

// New Leads
$result = $conn->query("SELECT COUNT(*) AS newLeads FROM lead WHERE Status = 'New'");
if ($row = $result->fetch_assoc()) $response['newLeads'] = $row['newLeads'];

// Completed Leads
$result = $conn->query("SELECT COUNT(*) AS completedLeads FROM lead WHERE Status = 'Completed'");
if ($row = $result->fetch_assoc()) $response['completedLeads'] = $row['completedLeads'];

// Upcoming Follow-ups (follow_up_date = tomorrow)
$tomorrow = date('Y-m-d', strtotime('+1 day'));
$stmt = $conn->prepare("SELECT COUNT(*) AS upcoming FROM lead WHERE follow_up_date = ?");
$stmt->bind_param("s", $tomorrow);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) $response['upcomingFollowups'] = $row['upcoming'];

// Overdue Follow-ups (follow_up_date < today)
$today = date('Y-m-d');
$stmt = $conn->prepare("SELECT COUNT(*) AS overdue FROM lead WHERE follow_up_date < ?");
$stmt->bind_param("s", $today);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) $response['overdueFollowups'] = $row['overdue'];

echo json_encode($response);
?>
