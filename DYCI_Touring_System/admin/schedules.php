<?php
session_start();
require_once '../includes/functions.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $scheduleId = $_POST['schedule_id'] ?? '';
    $roomId = $_POST['room_id'] ?? '';
    $day = $_POST['day'] ?? '';
    $startTime = $_POST['start_time'] ?? '';
    $endTime = $_POST['end_time'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $section = $_POST['section'] ?? '';
    $professor = $_POST['professor'] ?? '';
    
    // Update schedule in database
    // You'll need to implement this in your database
}

// Get all rooms (you'll need to implement this in your database)
$rooms = [
    11 => [
        'name' => 'Entrance',
        'schedules' => [
            1 => [
                'day' => 'Monday',
                'start_time' => '08:00',
                'end_time' => '09:00',
                'subject' => 'Introduction to Computing',
                'section' => 'BSIT-1A',
                'professor' => 'Prof. Smith'
            ]
        ]
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Schedule Management - DYCI Tour</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }

        .header {
            background: #000080;
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .back-btn {
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            border: 1px solid white;
            border-radius: 4px;
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
        }

        .room-schedule {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .room-header {
            color: #000080;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #000080;
        }

        .schedule-form {
            display: grid;
            gap: 15px;
        }

        .form-group {
            display: grid;
            gap: 5px;
        }

        .form-group label {
            color: #333;
            font-weight: bold;
        }

        .form-group input,
        .form-group select {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .time-inputs {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .save-btn {
            background: #000080;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.2s;
        }

        .save-btn:hover {
            background: #000066;
        }

        .add-schedule-btn {
            background: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-bottom: 20px;
            transition: background-color 0.2s;
        }

        .add-schedule-btn:hover {
            background: #218838;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Schedule Management</h1>
        <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
    </div>

    <div class="container">
        <button class="add-schedule-btn" onclick="location.href='add_schedule.php'">Add New Schedule</button>

        <?php foreach ($rooms as $roomId => $room): ?>
        <div class="room-schedule">
            <h2 class="room-header"><?php echo htmlspecialchars($room['name']); ?></h2>
            
            <?php foreach ($room['schedules'] as $scheduleId => $schedule): ?>
            <form method="POST" action="" class="schedule-form">
                <input type="hidden" name="schedule_id" value="<?php echo $scheduleId; ?>">
                <input type="hidden" name="room_id" value="<?php echo $roomId; ?>">
                
                <div class="form-group">
                    <label for="day_<?php echo $scheduleId; ?>">Day</label>
                    <select id="day_<?php echo $scheduleId; ?>" name="day" required>
                        <option value="Monday" <?php echo $schedule['day'] === 'Monday' ? 'selected' : ''; ?>>Monday</option>
                        <option value="Tuesday" <?php echo $schedule['day'] === 'Tuesday' ? 'selected' : ''; ?>>Tuesday</option>
                        <option value="Wednesday" <?php echo $schedule['day'] === 'Wednesday' ? 'selected' : ''; ?>>Wednesday</option>
                        <option value="Thursday" <?php echo $schedule['day'] === 'Thursday' ? 'selected' : ''; ?>>Thursday</option>
                        <option value="Friday" <?php echo $schedule['day'] === 'Friday' ? 'selected' : ''; ?>>Friday</option>
                    </select>
                </div>

                <div class="time-inputs">
                    <div class="form-group">
                        <label for="start_time_<?php echo $scheduleId; ?>">Start Time</label>
                        <input type="time" id="start_time_<?php echo $scheduleId; ?>" name="start_time" 
                               value="<?php echo htmlspecialchars($schedule['start_time']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="end_time_<?php echo $scheduleId; ?>">End Time</label>
                        <input type="time" id="end_time_<?php echo $scheduleId; ?>" name="end_time" 
                               value="<?php echo htmlspecialchars($schedule['end_time']); ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="subject_<?php echo $scheduleId; ?>">Subject</label>
                    <input type="text" id="subject_<?php echo $scheduleId; ?>" name="subject" 
                           value="<?php echo htmlspecialchars($schedule['subject']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="section_<?php echo $scheduleId; ?>">Section</label>
                    <input type="text" id="section_<?php echo $scheduleId; ?>" name="section" 
                           value="<?php echo htmlspecialchars($schedule['section']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="professor_<?php echo $scheduleId; ?>">Professor</label>
                    <input type="text" id="professor_<?php echo $scheduleId; ?>" name="professor" 
                           value="<?php echo htmlspecialchars($schedule['professor']); ?>" required>
                </div>

                <button type="submit" class="save-btn">Save Changes</button>
            </form>
            <?php endforeach; ?>
        </div>
        <?php endforeach; ?>
    </div>
</body>
</html> 