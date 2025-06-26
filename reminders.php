<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['User_id'])) {
    echo json_encode(["error" => "Not logged in"]);
    exit;
}

$conn = new mysqli('localhost', 'root', 'root', 'coursework');
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$userId = $_SESSION['User_id'];

// FETCH LEADS
if (isset($_GET['action']) && $_GET['action'] === 'getLeads') {
    $leads = [];
    $stmt = $conn->prepare("SELECT Lead_id, follow_up_date FROM lead WHERE User_id=?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) $leads[] = $row;
    echo json_encode($leads);
    exit;
}

// FETCH REMINDERS
if (isset($_GET['action']) && $_GET['action'] === 'getReminders') {
    $reminders = [];
    $stmt = $conn->prepare("SELECT * FROM reminder WHERE User_id=?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) $reminders[] = $row;
    echo json_encode($reminders);
    exit;
}

// ADD REMINDER
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addReminder'])) {
    $leadId = $_POST['lead_id'];
    $message = $_POST['message'];
    $dateSent = $_POST['date_sent'];
    $status = 'Pending';

    $stmt = $conn->prepare("INSERT INTO reminder (User_id, Lead_id, Date_sent, Message, Reminder_status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iisss", $userId, $leadId, $dateSent, $message, $status);
    $stmt->execute();
    $stmt->close();
    exit;
}

// EDIT REMINDER
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editReminder'])) {
    $id = $_POST['editReminder'];
    $leadId = $_POST['lead_id'];
    $message = $_POST['message'];
    $dateSent = $_POST['date_sent'];

    $stmt = $conn->prepare("UPDATE reminder SET Lead_id=?, Date_sent=?, Message=? WHERE Reminder_id=? AND User_id=?");
    $stmt->bind_param("sssii", $leadId, $dateSent, $message, $id, $userId);
    $stmt->execute();
    $stmt->close();
    exit;
}

// DELETE REMINDER
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteReminder'])) {
    $id = $_POST['deleteReminder'];
    $stmt = $conn->prepare("DELETE FROM reminder WHERE Reminder_id=? AND User_id=?");
    $stmt->bind_param("ii", $id, $userId);
    $stmt->execute();
    $stmt->close();
    exit;
}
?>
