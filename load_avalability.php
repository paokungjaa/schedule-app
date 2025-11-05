<?php
require_once 'db.php';

$meeting_id = isset($_GET['meeting_id']) ? intval($_GET['meeting_id']) : 0;
if ($meeting_id <= 0) {
    http_response_code(400);
    echo json_encode([]);
    exit;
}

$sql = "SELECT start_time, end_time, COUNT(*) as count
        FROM availabilities
        WHERE meeting_id = ?
        GROUP BY start_time, end_time
        HAVING count > 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $meeting_id);
$stmt->execute();
$result = $stmt->get_result();

$overlaps = [];
while ($row = $result->fetch_assoc()) {
    $overlaps[] = $row;
}

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($overlaps);
?>
