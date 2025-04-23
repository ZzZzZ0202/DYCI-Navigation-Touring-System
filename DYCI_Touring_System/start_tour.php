<?php
require_once 'includes/functions.php';

// Get all buildings and rooms
$buildings = getBuildings();
$selected_building_id = isset($_GET['building_id']) ? (int)$_GET['building_id'] : null;
$rooms = $selected_building_id ? getRoomsByBuilding($selected_building_id) : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Tour - DYCI Tour System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background: #f5f5f5;
            font-family: Arial, sans-serif;
        }

        .tour-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 2rem;
            position: relative;
        }

        .header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo {
            width: 120px;
            height: auto;
            margin-bottom: 1rem;
        }

        .title {
            color: #000080;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            background: #000080;
            color: white;
            padding: 1rem;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        .building-list, .room-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .list-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid #eee;
            transition: background-color 0.3s ease;
        }

        .list-item:last-child {
            border-bottom: none;
        }

        .list-item:hover {
            background-color: #f8f9fa;
        }

        .item-name {
            font-weight: 500;
            color: #333;
        }

        .view-btn {
            background: #FFD700;
            color: #000080;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .view-btn:hover {
            background: #FFC700;
            transform: translateY(-2px);
            text-decoration: none;
            color: #000080;
        }

        .back-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            background: #FFD700;
            color: #000080;
            padding: 8px 20px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .back-btn:hover {
            background: #FFC700;
            transform: translateY(-2px);
            text-decoration: none;
            color: #000080;
        }

        .empty-message {
            text-align: center;
            color: #666;
            padding: 2rem;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="tour-container">
        <a href="javascript:history.back()" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>

        <div class="header">
            <img src="assets/images/dyci-logo.png" alt="DYCI Logo" class="logo">
            <h2 class="title">Campus Buildings and Rooms</h2>
        </div>

        <div class="cards-container">
            <!-- Buildings Card -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-building mr-2"></i>
                    Buildings
                </div>
                <div class="card-body">
                    <?php if (!empty($buildings)): ?>
                        <ul class="building-list">
                            <?php foreach ($buildings as $building): ?>
                                <li class="list-item">
                                    <span class="item-name"><?php echo htmlspecialchars($building['name']); ?></span>
                                    <a href="?building_id=<?php echo $building['id']; ?>" class="view-btn">
                                        <i class="fas fa-eye mr-1"></i>
                                        View Rooms
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <div class="empty-message">No buildings available.</div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Rooms Card -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-door-open mr-2"></i>
                    Rooms
                </div>
                <div class="card-body">
                    <?php if ($selected_building_id && !empty($rooms)): ?>
                        <ul class="room-list">
                            <?php foreach ($rooms as $room): ?>
                                <li class="list-item">
                                    <span class="item-name"><?php echo htmlspecialchars($room['name']); ?></span>
                                    <a href="view_room.php?id=<?php echo $room['id']; ?>" class="view-btn">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Details
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php elseif ($selected_building_id): ?>
                        <div class="empty-message">No rooms available for this building.</div>
                    <?php else: ?>
                        <div class="empty-message">Select a building to view its rooms.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 