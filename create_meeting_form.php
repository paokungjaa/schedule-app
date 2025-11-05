<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header('Location: login.html');
  exit();
}

// Set the session timeout in seconds (e.g., 15 minutes)
$timeout_duration = 900;  // 900 seconds = 15 minutes

// Check if the last activity timestamp exists
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout_duration)) {
    // Last request was more than 15 minutes ago
    session_unset();     // Remove all session variables
    session_destroy();   // Destroy the session
    header("Location: ../frontend/login.html"); // Redirect to login page
    exit();
}

// Update last activity timestamp to current time
$_SESSION['last_activity'] = time();


// Connect to database
$conn = new mysqli("localhost", "root", "", "meeting_app"); // update db name

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get all users except the currently logged-in one
$currentUserId = $_SESSION['user_id'];
$sql = "SELECT id, username FROM users WHERE id != $currentUserId";
$result = $conn->query($sql);

$users = [];
if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $users[] = $row;
  }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Create Meeting</title>
  <link rel="stylesheet" href="./meeting.css" />
</head>
<body>
  <div class="dashboard-container">
    <h2>Create a New Meeting</h2>
    <form action="../backend/create_meeting.php" method="POST">
      <div class="input-group">
        <label for="title">Meeting Title</label>
        <input type="text" id="title" name="title" required />
      </div>

      <div class="input-group">
        <label for="description">Meeting Description</label>
        <textarea id="description" name="description" rows="4" required></textarea>
      </div>

      <div class="input-group">
        <label for="datetime">Date & Time</label>
        <input type="datetime-local" id="datetime" name="datetime" required />
      </div>

      <div class="input-group">
        <label for="invitees[]">Invite Members</label>
        <select name="invitees[]" multiple required>
          <?php foreach ($users as $user): ?>
            <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['username']) ?></option>
          <?php endforeach; ?>
        </select>
        <small>Hold Ctrl (Windows) or Cmd (Mac) to select multiple</small>
      </div>

      <button type="submit" class="btn">Create Meeting</button>
    </form>
  </div>
</body>
</html>
