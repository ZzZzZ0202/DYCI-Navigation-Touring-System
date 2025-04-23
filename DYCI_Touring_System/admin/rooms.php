<?php
session_start();
require_once '../includes/functions.php';

$page_title = "Room Management";
require_once 'includes/admin_header.php';

// Get all buildings for the filter
$buildings = getBuildings();

// Get selected building ID from query string or default to first building
$selected_building_id = isset($_GET['building_id']) ? (int)$_GET['building_id'] : ($buildings[0]['id'] ?? null);

// Get rooms for selected building
$rooms = $selected_building_id ? getRoomsByBuilding($selected_building_id) : [];
?>

<div class="content-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Room Management</h1>
            <div class="mt-2">
                <select id="buildingFilter" class="form-control" onchange="filterRooms(this.value)">
                    <?php foreach ($buildings as $building): ?>
                        <option value="<?php echo $building['id']; ?>" 
                                <?php echo $selected_building_id == $building['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($building['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addRoomModal">
            <i class="fas fa-plus"></i> Add New Room
        </button>
    </div>

    <div class="grid-container">
        <?php foreach ($rooms as $room): ?>
            <div class="card room-card">
                <img src="../assets/images/rooms/<?php echo $room['id']; ?>.jpg" 
                     class="card-img-top"
                     alt="<?php echo htmlspecialchars($room['name']); ?>"
                     onerror="this.src='../assets/images/room-placeholder.jpg'">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($room['name']); ?></h5>
                    <p class="card-text">
                        <i class="fas fa-info-circle"></i> 
                        <?php echo htmlspecialchars($room['description'] ?? 'No description available'); ?>
                    </p>
                    <div class="room-details mb-3">
                        <span class="badge badge-primary">
                            <i class="fas fa-building"></i> 
                            <?php echo htmlspecialchars($room['building_name'] ?? 'Unknown Building'); ?>
                        </span>
                        <span class="badge badge-info">
                            <i class="fas fa-users"></i> 
                            Capacity: <?php echo htmlspecialchars($room['capacity'] ?? 'N/A'); ?>
                        </span>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary flex-grow-1" 
                                onclick="editRoom(<?php echo $room['id']; ?>)">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn btn-danger flex-grow-1" 
                                onclick="confirmDelete('Are you sure you want to delete this room?', 'delete_room.php?id=<?php echo $room['id']; ?>')">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if (empty($rooms)): ?>
            <div class="alert alert-info w-100" role="alert">
                <i class="fas fa-info-circle"></i> No rooms found for this building.
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Add Room Modal -->
<div class="modal fade" id="addRoomModal" tabindex="-1" role="dialog" aria-labelledby="addRoomModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addRoomModalLabel">Add New Room</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addRoomForm" action="add_room.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="roomName">Room Name/Number</label>
                        <input type="text" class="form-control" id="roomName" name="name" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="buildingId">Building</label>
                        <select class="form-control" id="buildingId" name="building_id" required>
                            <?php foreach ($buildings as $building): ?>
                                <option value="<?php echo $building['id']; ?>">
                                    <?php echo htmlspecialchars($building['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="roomDescription">Description</label>
                        <textarea class="form-control" id="roomDescription" name="description" rows="3"></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label for="roomCapacity">Capacity</label>
                        <input type="number" class="form-control" id="roomCapacity" name="capacity" min="1">
                    </div>
                    <div class="form-group">
                        <label for="roomImage">Room Image</label>
                        <input type="file" class="form-control" id="roomImage" name="image" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Room</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Room Modal -->
<div class="modal fade" id="editRoomModal" tabindex="-1" role="dialog" aria-labelledby="editRoomModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRoomModalLabel">Edit Room</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editRoomForm" action="update_room.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" id="editRoomId" name="id">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="editRoomName">Room Name/Number</label>
                        <input type="text" class="form-control" id="editRoomName" name="name" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="editBuildingId">Building</label>
                        <select class="form-control" id="editBuildingId" name="building_id" required>
                            <?php foreach ($buildings as $building): ?>
                                <option value="<?php echo $building['id']; ?>">
                                    <?php echo htmlspecialchars($building['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="editRoomDescription">Description</label>
                        <textarea class="form-control" id="editRoomDescription" name="description" rows="3"></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label for="editRoomCapacity">Capacity</label>
                        <input type="number" class="form-control" id="editRoomCapacity" name="capacity" min="1">
                    </div>
                    <div class="form-group">
                        <label for="editRoomImage">Room Image</label>
                        <input type="file" class="form-control" id="editRoomImage" name="image" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function filterRooms(buildingId) {
    window.location.href = 'rooms.php?building_id=' + buildingId;
}

function editRoom(roomId) {
    // Fetch room details using AJAX
    $.get('get_room.php?id=' + roomId, function(room) {
        $('#editRoomId').val(room.id);
        $('#editRoomName').val(room.name);
        $('#editBuildingId').val(room.building_id);
        $('#editRoomDescription').val(room.description);
        $('#editRoomCapacity').val(room.capacity);
        $('#editRoomModal').modal('show');
    });
}

// Show success message if redirected from add/edit/delete
<?php if (isset($_SESSION['success'])): ?>
    showSuccess('<?php echo $_SESSION['success']; ?>');
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

// Show error message if redirected with error
<?php if (isset($_SESSION['error'])): ?>
    showError('<?php echo $_SESSION['error']; ?>');
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>
</script>

<?php require_once 'includes/admin_footer.php'; ?> 