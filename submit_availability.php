<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../frontend/Login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$meeting_id = $_POST['meeting_id'];
$start_time = $_POST['start_time'];  // Time format: YYYY-MM-DD HH:MM:SS
$end_time = $_POST['end_time'];      // Time format: YYYY-MM-DD HH:MM:SS

// Check if the meeting exists in the 'meeting' table
$stmt = $conn->prepare("SELECT id FROM meetings WHERE id = ?");
$stmt->bind_param("i", $meeting_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Error: Meeting ID does not exist.";
    exit();
}

// Insert the availability into the database or update if already exists
$stmt = $conn->prepare("INSERT INTO availabilities (meeting_id, user_id, start_time, end_time) 
                        VALUES (?, ?, ?, ?) 
                        ON DUPLICATE KEY UPDATE start_time = ?, end_time = ?");
$stmt->bind_param("iissss", $meeting_id, $user_id, $start_time, $end_time, $start_time, $end_time);

if ($stmt->execute()) {
    echo "Availability updated successfully.";
} else { 
    echo "Error saving availability: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
