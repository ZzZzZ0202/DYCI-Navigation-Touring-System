<?php
session_start();
require_once 'includes/functions.php';

$building_id = isset($_GET['building_id']) ? (int)$_GET['building_id'] : 0;
$rooms = $building_id ? getRoomsByBuildingWithImages($building_id) : getAllRoomsWithImages();
$buildings = getBuildings();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rooms - DYCI Tour System</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: #003399;
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
        }

        .rooms-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .room-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .room-card:hover {
            transform: translateY(-5px);
        }

        .room-image {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .room-info {
            padding: 15px;
        }

        .room-name {
            margin: 0;
            color: #000080;
            font-size: 18px;
            font-weight: bold;
        }

        .room-location {
            color: #666;
            font-size: 14px;
            margin-top: 5px;
        }

        .details-btn {
            display: inline-block;
            padding: 8px 16px;
            background-color: #003399;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 10px;
            font-size: 14px;
        }

        .details-btn:hover {
            background-color: #002266;
        }

        .section-title {
            color: white;
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="section-title">DYCI Rooms</h1>

        <div class="filter-section">
            <div class="form-group">
                <label for="building_filter">Filter by Building:</label>
                <select class="form-control" id="building_filter" onchange="filterRooms(this.value)">
                    <option value="">All Buildings</option>
                    <?php foreach ($buildings as $building): ?>
                        <option value="<?php echo $building['id']; ?>" 
                                <?php echo ($building_id == $building['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($building['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="rooms-grid">
            <?php foreach ($rooms as $room): ?>
                <div class="room-card">
                    <img src="assets/images/rooms/<?php echo htmlspecialchars($room['image_url']); ?>" 
                         alt="<?php echo htmlspecialchars($room['name']); ?>"
                         class="room-image">
                    <div class="room-info">
                        <h2 class="room-name"><?php echo htmlspecialchars($room['name']); ?></h2>
                        <p class="room-location">
                            <?php echo htmlspecialchars($room['building_name'] . ' - Floor ' . $room['floor']); ?>
                        </p>
                        <a href="view_room_student.php?id=<?php echo $room['id']; ?>" class="details-btn">View Details</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        function filterRooms(buildingId) {
            window.location.href = buildingId ? 
                'rooms.php?building_id=' + buildingId : 
                'rooms.php';
        }

        function viewRoom(roomId) {
            <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'student'): ?>
                window.location.href = 'view_room_student.php?id=' + roomId;
            <?php else: ?>
                window.location.href = 'view_room_visitor.php?id=' + roomId;
            <?php endif; ?>
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html> 