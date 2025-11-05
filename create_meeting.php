<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../frontend/Login.html");
    exit();
}

$owner_id = $_SESSION['user_id'];
$title = trim($_POST['title']);
$description = trim($_POST['description']);
$datetime = $_POST['datetime'];
$invitees = isset($_POST['invitees']) ? $_POST['invitees'] : [];

if (empty($title) || empty($description) || empty($datetime)) {
    die("All fields are required.");
}

// Insert the meeting into the `meetings` table
$stmt = $conn->prepare("INSERT INTO meetings (owner_id, title, description, datetime) VALUES (?, ?, ?, ?)");
$stmt->bind_param("isss", $owner_id, $title, $description, $datetime);

if ($stmt->execute()) {
    $meeting_id = $stmt->insert_id;

    // Prepare the statement for invitees
    $invite_stmt = $conn->prepare("INSERT INTO meeting_invitees (meeting_id, user_id) VALUES (?, ?)");

    // Loop through invitees and add each to the invitee table
    foreach ($invitees as $user_id) {
        $user_id = intval($user_id);
        if ($user_id !== $owner_id) { // Avoid duplicate if you plan to add owner below
            $invite_stmt->bind_param("ii", $meeting_id, $user_id);
            $invite_stmt->execute();
        }
    }

    $invite_stmt->close();
    $stmt->close();
    $conn->close();

    header("Location: ../frontend/dashboard.php?success=1");
    exit();
} else {
    echo "Error creating meeting: " . $stmt->error;
    $stmt->close();
    $conn->close();
}
?>
