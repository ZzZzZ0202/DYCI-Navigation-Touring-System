<?php
session_start();
require_once '../includes/functions.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: login.php");
    exit();
}

$message = '';
$room_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$room = getRoomDetails($room_id);

if (!$room) {
    header("Location: rooms.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $building_id = $_POST['building_id'] ?? '';
    $type = $_POST['type'] ?? '';
    $capacity = $_POST['capacity'] ?? '';
    $description = $_POST['description'] ?? '';

    try {
        $stmt = $pdo->prepare("
            UPDATE rooms 
            SET name = ?, building_id = ?, type = ?, capacity = ?, description = ?
            WHERE id = ?
        ");
        
        if ($stmt->execute([$name, $building_id, $type, $capacity, $description, $room_id])) {
            // Handle image upload if a file was selected
            if (isset($_FILES['room_image']) && $_FILES['room_image']['error'] === UPLOAD_ERR_OK) {
                if (updateRoomImage($room_id, $_FILES['room_image'])) {
                    $message = "Room updated successfully with new image.";
                } else {
                    $message = "Room details updated but image upload failed.";
                }
            } else {
                $message = "Room updated successfully.";
            }
        } else {
            $message = "Error updating room.";
        }
    } catch(PDOException $e) {
        error_log("Update room error: " . $e->getMessage());
        $message = "Error updating room.";
    }
}

// Get buildings for dropdown
$buildings = getBuildings();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Room - DYCI Tour System</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .current-image {
            max-width: 300px;
            margin: 10px 0;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mb-4">Update Room</h2>
        
        <?php if ($message): ?>
        <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Room Name/Number:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($room['name']); ?>" required>
            </div>

            <div class="form-group">
                <label for="building_id">Building:</label>
                <select class="form-control" id="building_id" name="building_id" required>
                    <?php foreach ($buildings as $building): ?>
                        <option value="<?php echo $building['id']; ?>" <?php echo ($building['id'] == $room['building_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($building['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="type">Room Type:</label>
                <input type="text" class="form-control" id="type" name="type" value="<?php echo htmlspecialchars($room['type'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="capacity">Capacity:</label>
                <input type="number" class="form-control" id="capacity" name="capacity" value="<?php echo htmlspecialchars($room['capacity'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($room['description'] ?? ''); ?></textarea>
            </div>

            <div class="form-group">
                <label>Current Image:</label><br>
                <img src="../assets/images/rooms/<?php echo htmlspecialchars(getRoomImage($room_id)); ?>" 
                     alt="Room Image" 
                     class="current-image">
            </div>

            <div class="form-group">
                <label for="room_image">Update Image:</label>
                <input type="file" class="form-control-file" id="room_image" name="room_image" accept="image/*">
                <small class="form-text text-muted">Supported formats: JPG, JPEG, PNG, GIF</small>
            </div>

            <button type="submit" class="btn btn-primary">Update Room</button>
            <a href="rooms.php" class="btn btn-secondary">Back to Rooms</a>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html> 