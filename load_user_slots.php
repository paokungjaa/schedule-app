<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo "Unauthorized";
    exit;
}

$user_id = $_SESSION['user_id'];
$meeting_id = isset($_GET['meeting_id']) ? intval($_GET['meeting_id']) : 0;

if ($meeting_id <= 0) {
    http_response_code(400);
    echo json_encode([]);
    exit;
}

$sql = "SELECT start_time, end_time FROM availabilities WHERE meeting_id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $meeting_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

$user_slots = [];
while ($row = $result->fetch_assoc()) {
    $user_slots[] = $row;
}

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($user_slots);
?>
