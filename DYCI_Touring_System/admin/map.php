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
    $buildingId = $_POST['building_id'] ?? '';
    $name = $_POST['name'] ?? '';
    $x = $_POST['x'] ?? '';
    $y = $_POST['y'] ?? '';
    $color = $_POST['color'] ?? '';
    
    // Update building location in database
    // You'll need to implement this in your database
}

// Get all buildings (you'll need to implement this in your database)
$buildings = [
    1 => [
        'name' => 'Front',
        'x' => 100,
        'y' => 100,
        'color' => '#000080',
        'rooms' => [
            11 => ['name' => 'Entrance', 'x' => 110, 'y' => 110],
            12 => ['name' => 'Security Guard post', 'x' => 120, 'y' => 120],
            13 => ['name' => 'Frontline', 'x' => 130, 'y' => 130],
            14 => ['name' => 'Waiting area', 'x' => 140, 'y' => 140]
        ]
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Map Management - DYCI Tour</title>
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
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 20px;
        }

        .map-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: relative;
            height: 600px;
        }

        .map {
            width: 100%;
            height: 100%;
            background: #f8f9fa;
            position: relative;
        }

        .building {
            position: absolute;
            width: 100px;
            height: 100px;
            border: 2px solid #000;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: move;
        }

        .building-label {
            background: rgba(255, 255, 255, 0.9);
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            text-align: center;
        }

        .controls {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .building-form {
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

        .coordinates {
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

        .add-building-btn {
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

        .add-building-btn:hover {
            background: #218838;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Map Management</h1>
        <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
    </div>

    <div class="container">
        <div class="map-container">
            <div class="map">
                <?php foreach ($buildings as $buildingId => $building): ?>
                <div class="building" 
                     style="left: <?php echo $building['x']; ?>px; 
                            top: <?php echo $building['y']; ?>px;
                            background: <?php echo $building['color']; ?>;">
                    <div class="building-label"><?php echo htmlspecialchars($building['name']); ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="controls">
            <button class="add-building-btn" onclick="location.href='add_building.php'">Add New Building</button>

            <?php foreach ($buildings as $buildingId => $building): ?>
            <form method="POST" action="" class="building-form">
                <input type="hidden" name="building_id" value="<?php echo $buildingId; ?>">
                
                <div class="form-group">
                    <label for="name_<?php echo $buildingId; ?>">Building Name</label>
                    <input type="text" id="name_<?php echo $buildingId; ?>" name="name" 
                           value="<?php echo htmlspecialchars($building['name']); ?>" required>
                </div>

                <div class="coordinates">
                    <div class="form-group">
                        <label for="x_<?php echo $buildingId; ?>">X Position</label>
                        <input type="number" id="x_<?php echo $buildingId; ?>" name="x" 
                               value="<?php echo htmlspecialchars($building['x']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="y_<?php echo $buildingId; ?>">Y Position</label>
                        <input type="number" id="y_<?php echo $buildingId; ?>" name="y" 
                               value="<?php echo htmlspecialchars($building['y']); ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="color_<?php echo $buildingId; ?>">Color</label>
                    <input type="color" id="color_<?php echo $buildingId; ?>" name="color" 
                           value="<?php echo htmlspecialchars($building['color']); ?>" required>
                </div>

                <button type="submit" class="save-btn">Save Changes</button>
            </form>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        // Add drag and drop functionality
        document.querySelectorAll('.building').forEach(building => {
            building.addEventListener('mousedown', startDragging);
        });

        function startDragging(e) {
            const building = e.target.closest('.building');
            const startX = e.clientX - building.offsetLeft;
            const startY = e.clientY - building.offsetTop;

            function moveBuilding(e) {
                building.style.left = (e.clientX - startX) + 'px';
                building.style.top = (e.clientY - startY) + 'px';
            }

            function stopDragging() {
                document.removeEventListener('mousemove', moveBuilding);
                document.removeEventListener('mouseup', stopDragging);
            }

            document.addEventListener('mousemove', moveBuilding);
            document.addEventListener('mouseup', stopDragging);
        }
    </script>
</body>
</html> 