<?php
session_start();
require_once '../includes/functions.php';

$page_title = "Building Management";
require_once 'includes/admin_header.php';

// Check if logged in as admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin_login.php");
    exit();
}

// Set the correct path for the logo in header
$logo_path = "../assets/images/dyci-logo.png";

// Get all buildings
$buildings = getBuildings();
?>

<div class="content-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Building Management</h1>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addBuildingModal">
            <i class="fas fa-plus"></i> Add New Building
        </button>
    </div>

    <div class="grid-container">
        <?php foreach ($buildings as $building): ?>
            <div class="card building-card">
                <img src="../assets/images/buildings/<?php echo $building['id']; ?>.jpg" 
                     class="card-img-top"
                     alt="<?php echo htmlspecialchars($building['name']); ?>"
                     onerror="this.src='../assets/images/building-placeholder.jpg'">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($building['name']); ?></h5>
                    <p class="card-text">
                        <i class="fas fa-info-circle"></i> 
                        <?php echo htmlspecialchars($building['description']); ?>
                    </p>
                    <div class="badge badge-light mb-3">
                        <i class="fas fa-door-open"></i>
                        <?php 
                        $rooms = getRoomsByBuilding($building['id']);
                        echo count($rooms) . ' ' . (count($rooms) === 1 ? 'Room' : 'Rooms');
                        ?>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary flex-grow-1" 
                                onclick="editBuilding(<?php echo $building['id']; ?>)">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn btn-danger flex-grow-1" 
                                onclick="confirmDelete('Are you sure you want to delete this building? This will also delete all rooms in this building.', 'delete_building.php?id=<?php echo $building['id']; ?>')">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Add Building Modal -->
<div class="modal fade" id="addBuildingModal" tabindex="-1" role="dialog" aria-labelledby="addBuildingModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addBuildingModalLabel">Add New Building</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addBuildingForm" action="add_building.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="buildingName">Building Name</label>
                        <input type="text" class="form-control" id="buildingName" name="name" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="buildingDescription">Description</label>
                        <textarea class="form-control" id="buildingDescription" name="description" rows="3" required></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label for="buildingFloor">Floor Number</label>
                        <input type="number" class="form-control" id="buildingFloor" name="floor" required min="1">
                    </div>
                    <div class="form-group">
                        <label for="buildingImage">Building Image</label>
                        <input type="file" class="form-control" id="buildingImage" name="image" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Building</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Building Modal -->
<div class="modal fade" id="editBuildingModal" tabindex="-1" role="dialog" aria-labelledby="editBuildingModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editBuildingModalLabel">Edit Building</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editBuildingForm" action="update_building.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" id="editBuildingId" name="id">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="editBuildingName">Building Name</label>
                        <input type="text" class="form-control" id="editBuildingName" name="name" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="editBuildingDescription">Description</label>
                        <textarea class="form-control" id="editBuildingDescription" name="description" rows="3" required></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label for="editBuildingFloor">Floor Number</label>
                        <input type="number" class="form-control" id="editBuildingFloor" name="floor" required min="1">
                    </div>
                    <div class="form-group">
                        <label for="editBuildingImage">Building Image</label>
                        <input type="file" class="form-control" id="editBuildingImage" name="image" accept="image/*">
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
function editBuilding(buildingId) {
    // Fetch building details using AJAX
    $.get('get_building.php?id=' + buildingId, function(building) {
        $('#editBuildingId').val(building.id);
        $('#editBuildingName').val(building.name);
        $('#editBuildingDescription').val(building.description);
        $('#editBuildingFloor').val(building.floor);
        $('#editBuildingModal').modal('show');
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