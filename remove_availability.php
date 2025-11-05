<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../frontend/Login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$meeting_id = $_POST['meeting_id'];
$start_time = $_POST['start_time'];  // Time format: YYYY-MM-DDTHH:MM:SS

// Log the data for debugging
error_log("meeting_id: " . $meeting_id . " | start_time: " . $start_time);

// Remove the 'Z' from the end of the start_time if it exists (for UTC time)
$start_time = rtrim($start_time, 'Z');

// Delete the availability from the database
$stmt = $conn->prepare("DELETE FROM availabilities WHERE meeting_id = ? AND user_id = ? AND start_time = ?");
$stmt->bind_param("iis", $meeting_id, $user_id, $start_time);

if ($stmt->execute()) {
    echo "Availability removed successfully.";
} else {
    echo "Error removing availability: " . $stmt->error;
}

$stmt->close();
$conn->close();


