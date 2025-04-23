<?php
session_start();
require_once 'includes/functions.php';

$room_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$room = getRoomDetails($room_id);

if (!$room) {
    header("Location: index.php");
    exit();
}

// Log the visit
logVisit('visitor', null, $room_id);
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

        .room-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin: 40px auto;
            max-width: 800px;
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

        .room-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .room-description {
            color: #444;
            font-size: 16px;
            line-height: 1.5;
            text-align: center;
            margin: 20px 0;
            padding: 0 20px;
        }

        .room-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
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
    </style>
</head>
<body>
    <div class="room-card">
        <div class="room-header">
            <h1 class="room-title">Room <?php echo htmlspecialchars($room['name']); ?></h1>
            <p class="room-location"><?php echo htmlspecialchars($room['building_name'] . ' - ' . $room['floor']); ?></p>
        </div>

        <img src="assets/images/rooms/<?php echo htmlspecialchars(getRoomImage($room_id)); ?>" 
             alt="Room <?php echo htmlspecialchars($room['name']); ?>" 
             class="room-image">

        <p class="room-description">
            <?php echo htmlspecialchars($room['description'] ?? 'General purpose classroom equipped with modern facilities for an optimal learning environment.'); ?>
        </p>

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

    <a href="index.php" class="back-btn">
        <span class="back-arrow">←</span>
        Back to Map
    </a>
</body>
</html> 