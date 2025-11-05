<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

require_once '../backend/db.php';

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
if (isset($_GET['meeting_id'])) {
    $meeting_id = intval($_GET['meeting_id']);
} else {
    $stmt = $conn->prepare("SELECT id FROM meetings WHERE owner_id = ? LIMIT 1");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($default_meeting_id);
    if ($stmt->fetch()) {
        header("Location: availability-calender.php?meeting_id=" . $default_meeting_id);
        exit;
    } else {
        echo "No meetings found for this user.";
        exit;
    }
}

$stmt = $conn->prepare("SELECT id, title FROM meetings WHERE owner_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$meeting_result = $stmt->get_result();

if ($meeting_id <= 0) {
    echo "Invalid meeting ID.";
    exit;
}

// Get user’s existing availability
$sql = "SELECT start_time, end_time FROM availabilities WHERE meeting_id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $meeting_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

$user_slots = [];
while ($row = $result->fetch_assoc()) {
    $user_slots[] = [
        'start' => $row['start_time'],
        'end' => $row['end_time'],
        'backgroundColor' => 'green',
        'borderColor' => 'green'
    ];
}
$stmt->close();

// Get overlapping slots (all users)
$sql = "SELECT start_time, end_time, COUNT(*) as count 
        FROM availabilities 
        WHERE meeting_id = ? 
        GROUP BY start_time, end_time 
        HAVING count > 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $meeting_id);
$stmt->execute();
$result = $stmt->get_result();

$overlap_slots = [];
while ($row = $result->fetch_assoc()) {
    $overlap_slots[] = [
        'start' => $row['start_time'],
        'end' => $row['end_time'],
        'backgroundColor' => 'orange',
        'borderColor' => 'orange'
    ];
}
$stmt->close();
$conn->close();

$all_events = array_merge($user_slots, $overlap_slots);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Availability Calendar</title>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
        }

        h2 {
            font-weight: 600;
            margin: 20px 30px;
        }

        #calendar {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            margin: 20px auto;
            padding: 20px;
            width: calc(100% - 60px);
            /* Full width with margin */
            max-width: 1400px;
        }

        form {
            width: 100%;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            margin: 10px 10px 0 30px;
            transition: background-color 0.2s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        #savebutton {
            background-color: #28a745;
        }

        #savebutton:hover {
            background-color: #1e7e34;
        }

        #editButton {
            background-color: #ffc107;
            color: #333;
        }

        #editButton:hover {
            background-color: #e0a800;
        }

        .fc {
            font-size: 14px;
        }
    </style>

    </style>
</head>

<body>

    <button onclick="window.location.href='dashboard.php'">← Back</button>

    <h2>Hello, <?= htmlspecialchars($username) ?> – Select Your Available Time Slots</h2>

    <form id="meetingSwitchForm" method="GET" action="availability-calender.php" style="margin: 0 30px 10px;">
        <label for="meeting_id">Select Meeting:</label>
        <select name="meeting_id" id="meeting_id" onchange="document.getElementById('meetingSwitchForm').submit();">
            <?php while ($row = $meeting_result->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>" <?= ($row['id'] == $meeting_id) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($row['title']) ?>
                </option>
            <?php endwhile; ?>
        </select>
    </form>

    <form method="POST" action="../backend/save_availability.php">
        <input type="hidden" name="meeting_id" value="<?= $meeting_id ?>">
        <input type="hidden" name="selected_slots" id="selectedSlotsInput">
        <div id="calendar"></div>
        <br>
        <button type="submit" id="savebutton">Save Availability</button>
        <button type="button" id="editButton">Edit Availability</button>

    </form>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let selectedEvents = <?= json_encode($user_slots) ?>;
            const overlapEvents = <?= json_encode($overlap_slots) ?>;

            const selectedSlots = new Set(selectedEvents.map(ev => ev.start + '|' + ev.end));
            const calendarEl = document.getElementById('calendar');
            let isEditMode = false;

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                allDaySlot: false,
                selectable: true,
                selectOverlap: false,
                slotDuration: '01:00:00',
                expandRows: true,
                nowIndicator: true,
                validRange: {
                    start: new Date().toISOString().split("T")[0]
                },
                select: function(info) {
                    const key = info.startStr + '|' + info.endStr;
                    if (selectedSlots.has(key)) {
                        selectedSlots.delete(key);
                        calendar.getEvents().forEach(e => {
                            if (e.startStr === info.startStr && e.endStr === info.endStr && e.backgroundColor === 'green') {
                                e.remove();
                            }
                        });
                    } else {
                        selectedSlots.add(key);
                        calendar.addEvent({
                            start: info.startStr,
                            end: info.endStr,
                            backgroundColor: 'green',
                            borderColor: 'green'
                        });
                    }
                    updateInput();
                },
                events: [...selectedEvents, ...overlapEvents],
                eventDidMount: function(info) {
                    const isUserSlot = info.event.backgroundColor === 'green';

                    if (isUserSlot && isEditMode) {
                        const closeBtn = document.createElement('span');
                        closeBtn.innerHTML = '&times;';
                        closeBtn.style.position = 'absolute';
                        closeBtn.style.top = '2px';
                        closeBtn.style.right = '4px';
                        closeBtn.style.cursor = 'pointer';
                        closeBtn.style.color = 'white';
                        closeBtn.style.fontSize = '14px';
                        closeBtn.style.zIndex = '1000';

                        closeBtn.onclick = function(e) {
                            e.stopPropagation();
                            const key = info.event.startStr + '|' + info.event.endStr;
                            selectedSlots.delete(key);
                            info.event.remove();
                            updateInput();
                        };

                        info.el.style.position = 'relative';
                        info.el.appendChild(closeBtn);
                    }
                }
            });

            calendar.render();

            const saveButton = document.getElementById('savebutton');
            const editButton = document.getElementById('editButton');

            editButton.addEventListener('click', function() {
                isEditMode = !isEditMode;

                if (isEditMode) {
                    saveButton.style.display = 'inline-block';
                    editButton.innerText = 'Done Editing';
                } else {
                    saveButton.style.display = 'inline-block';
                    editButton.innerText = 'Edit Availability';
                }

                calendar.refetchEvents(); // Force re-render to trigger eventDidMount again
            });

            function updateInput() {
                document.getElementById('selectedSlotsInput').value = JSON.stringify(Array.from(selectedSlots));
            }

            updateInput();
        });
    </script>

</body>

</html>