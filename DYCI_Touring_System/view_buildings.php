<?php
session_start();
require_once 'config/database.php';

try {
    // Get the selected building ID from URL parameter
    $selected_building_id = isset($_GET['building_id']) ? $_GET['building_id'] : null;

    // Get all buildings
    $stmt = $pdo->query("SELECT * FROM buildings ORDER BY name");
    $buildings = $stmt->fetchAll();

    // If no building is selected, use the first one
    if (!$selected_building_id && !empty($buildings)) {
        $selected_building_id = $buildings[0]['id'];
    }

    // Get rooms for the selected building
    if ($selected_building_id) {
        $stmt = $pdo->prepare("SELECT * FROM rooms WHERE building_id = ? ORDER BY name");
        $stmt->execute([$selected_building_id]);
        $rooms = $stmt->fetchAll();

        // Get the selected building info
        $stmt = $pdo->prepare("SELECT * FROM buildings WHERE id = ?");
        $stmt->execute([$selected_building_id]);
        $selected_building = $stmt->fetch();
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DYCI Tour - <?php echo isset($selected_building) ? htmlspecialchars($selected_building['name']) : 'Buildings'; ?></title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            min-height: 100vh;
        }

        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            padding: 10px 20px;
            background: #FFD700;
            color: black;
            text-decoration: none;
            border-radius: 20px;
            font-weight: bold;
        }

        .header {
            background: linear-gradient(to bottom, #000080, #1a237e);
            color: white;
            padding: 20px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .header img {
            width: 50px;
            height: 50px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
        }

        .room-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .room-button {
            background: #000080;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 25px;
            text-decoration: none;
            font-size: 16px;
            text-align: center;
            transition: background-color 0.2s;
            width: 100%;
            max-width: 300px;
            margin: 0 auto;
            cursor: pointer;
        }

        .room-button:hover {
            background: #1a237e;
        }

        .building-selector {
            margin-bottom: 20px;
            text-align: center;
        }

        .building-selector a {
            display: inline-block;
            padding: 10px 20px;
            margin: 0 10px;
            text-decoration: none;
            color: #000080;
            border-bottom: 2px solid transparent;
            transition: border-color 0.2s;
        }

        .building-selector a.active {
            border-bottom-color: #000080;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <a href="index.php" class="back-button">← Back</a>
    <div class="header">
        <img src="assets/images/dyci-logo.png" alt="DYCI Logo">
        <h1><?php echo isset($selected_building) ? htmlspecialchars($selected_building['name']) : 'Select Building'; ?></h1>
    </div>
    <div class="container">
        <div class="building-selector">
            <?php foreach ($buildings as $building): ?>
                <a href="?building_id=<?php echo $building['id']; ?>" 
                   class="<?php echo $selected_building_id == $building['id'] ? 'active' : ''; ?>">
                    <?php echo htmlspecialchars($building['name']); ?>
                </a>
            <?php endforeach; ?>
        </div>
        <div class="room-list">
            <?php if (isset($rooms) && !empty($rooms)): ?>
                <?php foreach ($rooms as $room): ?>
                    <a href="view_room.php?id=<?php echo $room['id']; ?>" class="room-button">
                        <?php echo htmlspecialchars($room['name']); ?>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center; color: #666;">No rooms available for this building.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html> 