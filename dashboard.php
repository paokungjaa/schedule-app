<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}
$username = $_SESSION['username'];

$timeout_duration = 900;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout_duration)) {
    session_unset();
    session_destroy();
    header("Location: ../frontend/login.html");
    exit();
}
$_SESSION['last_activity'] = time();

require_once '../backend/db.php';

$user_id = $_SESSION['user_id'];
$pendingInvites = [];
$firstMeetingId = null;

// Find first meeting user is invited to or owns
$sql = "SELECT DISTINCT m.id AS meeting_id
        FROM meetings m
        LEFT JOIN meeting_invitees mi ON m.id = mi.meeting_id
        WHERE mi.user_id = ? OR m.owner_id = ?
        LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $firstMeetingId = $row['meeting_id'];
}
$stmt->close();

// Get pending invites
$sql = "SELECT m.id AS meeting_id, m.title, m.description 
        FROM meetings m
        JOIN meeting_invitees mi ON m.id = mi.meeting_id
        WHERE mi.user_id = ? AND mi.status = 'pending'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $pendingInvites[] = $row;
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="./dashboard.css">
</head>
<body>
<div class="container">
    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="logo">
            <h2>MeetingApp</h2>
        </div>
        <ul>
            <li><a href="dashboard.php"><i class="material-icons">dashboard</i> Dashboard</a></li>

            <li>
                <?php if ($firstMeetingId): ?>
                    <a href="availability-calender.php?meeting_id=<?= $firstMeetingId ?>">
                        <i class="material-icons">event</i> My Meetings
                    </a>
                <?php else: ?>
                    <span style="color: gray; padding: 10px;"><i class="material-icons">event</i> My Meetings</span>
                <?php endif; ?>
            </li>

            <li><a href="profile.php"><i class="material-icons">account_circle</i> Profile</a></li>
            <li><a href="../backend/logout.php"><i class="material-icons">exit_to_app</i> Logout</a></li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <header class="header">
            <div class="welcome-msg">
                <h1>Welcome, <?= htmlspecialchars($username); ?>!</h1>
            </div>
        </header>

        <section class="dashboard-cards">
            <div class="card">
                <h2>Your Upcoming Meetings</h2>
                <p>You don’t have any meetings scheduled yet. Start by creating one.</p>
                <a href="./create_meeting_form.php" class="btn">Create a Meeting</a>
            </div>

        </section>
    </div>
</div>

<!-- Invite popups -->
<?php foreach ($pendingInvites as $invite): ?>
<div class="invite-popup" id="invite-<?= $invite['meeting_id'] ?>">
    <div class="popup-content">
        <h3>You're invited to:</h3>
        <br>
        <h2><?= htmlspecialchars($invite['title']) ?></h2>
        <br>
        <hr>
        <br>
        <p><?= htmlspecialchars($invite['description']) ?></p>
        <br>
        <form method="POST" action="../backend/accept_invited.php">
            <input type="hidden" name="meeting_id" value="<?= $invite['meeting_id']; ?>">
            <button type="submit" class="accept-button">Accept</button>
        </form>
        <form method="POST" action="../backend/decline_invited.php">
            <input type="hidden" name="meeting_id" value="<?= $invite['meeting_id']; ?>">
            <button type="submit" class="decline-button">Decline</button>
        </form>
    </div>
</div>
<?php endforeach; ?>

<script src="../javascript/invitePopup.js"></script>
</body>
</html>
