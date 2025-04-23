<?php
session_start();
require_once 'includes/functions.php';

// Check if user is logged in as student
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'student') {
    header("Location: login.php");
    exit();
}

$room_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$room = getRoomDetails($room_id);
$schedule = getRoomSchedule($room_id); // You'll need to implement this function

if (!$room) {
    header("Location: index.php");
    exit();
}

// Log the visit
logVisit('student', $_SESSION['user_id'], $room_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($room['name']); ?> - DYCI Tour</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            min-height: 100vh;
            background: #003399;
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .room-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .room-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .room-title {
            color: #000080;
            font-size: 28px;
            font-weight: bold;
            margin: 0;
        }

        .room-location {
            color: #666;
            font-size: 16px;
            margin-top: 5px;
        }

        .room-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .room-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 4px;
        }

        .room-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
        }

        .info-title {
            color: #000080;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .info-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            color: #444;
        }

        .info-label {
            font-weight: bold;
            width: 120px;
        }

        .schedule-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .schedule-title {
            color: #000080;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }

        .schedule-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .schedule-table th {
            background: #f8f9fa;
            padding: 12px;
            text-align: left;
            border-bottom: 2px solid #e9ecef;
            color: #000080;
        }

        .schedule-table td {
            padding: 12px;
            border-bottom: 1px solid #e9ecef;
            color: #444;
        }

        .schedule-table tr:hover {
            background: #f8f9fa;
        }

        .time-slot {
            font-weight: bold;
            color: #666;
        }

        .section-badge {
            display: inline-block;
            padding: 2px 8px;
            background: #e9ecef;
            border-radius: 12px;
            font-size: 12px;
            color: #666;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            padding: 10px 20px;
            background-color: #003399;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px;
            font-weight: 500;
            transition: background-color 0.2s;
        }

        .back-btn:hover {
            background-color: #002266;
        }

        .back-arrow {
            margin-right: 8px;
        }

        @media (max-width: 768px) {
            .room-content {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="room-card">
            <div class="room-header">
                <h1 class="room-title">Room <?php echo htmlspecialchars($room['name']); ?></h1>
                <p class="room-location"><?php echo htmlspecialchars($room['building_name'] . ' - ' . $room['floor']); ?></p>
            </div>

            <div class="room-content">
                <div>
                    <img src="assets/images/rooms/<?php echo htmlspecialchars(getRoomImage($room_id)); ?>" 
                         alt="Room <?php echo htmlspecialchars($room['name']); ?>" 
                         class="room-image">
                    
                    <div class="room-info">
                        <h2 class="info-title">Room Information</h2>
                        <div class="info-item">
                            <span class="info-label">Room Type:</span>
                            <span><?php echo htmlspecialchars($room['type'] ?? 'Lecture Room'); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Capacity:</span>
                            <span><?php echo htmlspecialchars($room['capacity'] ?? '40'); ?> students</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Building:</span>
                            <span><?php echo htmlspecialchars($room['building_name']); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Floor:</span>
                            <span><?php echo htmlspecialchars($room['floor']); ?></span>
                        </div>
                    </div>
                </div>

                <div class="schedule-card">
                    <h2 class="schedule-title">Room Schedule</h2>
                    <table class="schedule-table">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Subject</th>
                                <th>Section</th>
                                <th>Professor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($schedule && count($schedule) > 0): ?>
                                <?php foreach ($schedule as $slot): ?>
                                <tr>
                                    <td class="time-slot"><?php echo htmlspecialchars($slot['time']); ?></td>
                                    <td><?php echo htmlspecialchars($slot['subject']); ?></td>
                                    <td>
                                        <span class="section-badge">
                                            <?php echo htmlspecialchars($slot['section']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($slot['professor']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" style="text-align: center;">No schedule available for this room.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <a href="index.php" class="back-btn">
            <span class="back-arrow">←</span>
            Back to Map
        </a>
    </div>
</body>
</html> 