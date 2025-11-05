<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

require_once 'db.php';

$user_id = $_SESSION['user_id'];
$meeting_id = isset($_POST['meeting_id']) ? intval($_POST['meeting_id']) : 0;

if ($meeting_id <= 0) {
    echo "Invalid meeting ID.";
    exit;
}

if (!isset($_POST['selected_slots'])) {
    echo "No slots submitted.";
    exit;
}

// Decode selected slots from JSON (array of "start|end")
$selected_raw = json_decode($_POST['selected_slots'], true);

if (!is_array($selected_raw)) {
    echo "Invalid slot data format.";
    exit;
}

// Clear old availability for this user/meeting
$stmt = $conn->prepare("DELETE FROM availabilities WHERE user_id = ? AND meeting_id = ?");
$stmt->bind_param("ii", $user_id, $meeting_id);
$stmt->execute();
$stmt->close();

// Insert new selected slots
$stmt = $conn->prepare("INSERT INTO availabilities (user_id, meeting_id, start_time, end_time) VALUES (?, ?, ?, ?)");

foreach ($selected_raw as $slot) {
    $parts = explode('|', $slot);
    if (count($parts) === 2) {
        $start_time = $parts[0];
        $end_time = $parts[1];
        $stmt->bind_param("iiss", $user_id, $meeting_id, $start_time, $end_time);
        $stmt->execute();
    }
}

$stmt->close();
$conn->close();

header("Location: ../frontend/availability-calender.php?meeting_id=$meeting_id&saved=1");
exit;
?>
