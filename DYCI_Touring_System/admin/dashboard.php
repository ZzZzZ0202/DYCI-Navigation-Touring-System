<?php
session_start();
require_once '../includes/functions.php';

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../admin_login.php');
    exit();
}

// Get admin info
$admin_username = $_SESSION['admin_username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - DYCI</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .sidebar {
            min-height: 100vh;
            background-color: #000080;
            padding-top: 20px;
        }
        .sidebar .nav-link {
            color: white;
            padding: 15px 20px;
            margin: 5px 0;
        }
        .sidebar .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
        }
        .sidebar .nav-link.active {
            background-color: rgba(255,255,255,0.2);
        }
        .sidebar .nav-link i {
            margin-right: 10px;
        }
        .content {
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            border-bottom: 1px solid #dee2e6;
        }
        .stat-card {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .stat-card h3 {
            color: #000080;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 px-0 sidebar">
                <div class="text-center mb-4">
                    <img src="../assets/images/dyci-logo.png" alt="DYCI Logo" style="width: 80px;">
                    <h5 class="text-white mt-2">Admin Panel</h5>
                </div>
                <nav class="nav flex-column">
                    <a class="nav-link active" href="dashboard.php">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                    <a class="nav-link" href="buildings.php">
                        <i class="fas fa-building"></i> Buildings
                    </a>
                    <a class="nav-link" href="rooms.php">
                        <i class="fas fa-door-open"></i> Rooms
                    </a>
                    <a class="nav-link" href="schedules.php">
                        <i class="fas fa-calendar-alt"></i> Schedules
                    </a>
                    <a class="nav-link" href="students.php">
                        <i class="fas fa-users"></i> Students
                    </a>
                    <a class="nav-link" href="map.php">
                        <i class="fas fa-map"></i> Campus Map
                    </a>
                    <a class="nav-link" href="logout.php">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-10">
                <div class="header d-flex justify-content-between align-items-center">
                    <h2>Dashboard</h2>
                    <div class="user-info">
                        Welcome, <?php echo htmlspecialchars($admin_username); ?>!
                    </div>
                </div>

                <div class="content">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="stat-card">
                                <h3>Total Buildings</h3>
                                <p class="h2"><?php echo getTotalBuildings(); ?></p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <h3>Total Rooms</h3>
                                <p class="h2"><?php echo getTotalRooms(); ?></p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <h3>Total Students</h3>
                                <p class="h2"><?php echo getTotalStudents(); ?></p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <h3>Total Visits</h3>
                                <p class="h2"><?php echo getTotalVisits(); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Recent Visits</h5>
                                </div>
                                <div class="card-body">
                                    <?php
                                    $recent_visits = getRecentVisits(5);
                                    if (!empty($recent_visits)): ?>
                                        <ul class="list-group">
                                        <?php foreach ($recent_visits as $visit): ?>
                                            <li class="list-group-item">
                                                <?php if ($visit['user_type'] === 'student'): ?>
                                                    <strong><?php echo htmlspecialchars($visit['student_name'] ?? 'Unknown Student'); ?></strong>
                                                <?php else: ?>
                                                    <strong><?php echo ucfirst(htmlspecialchars($visit['user_type'])); ?></strong>
                                                <?php endif; ?>
                                                visited 
                                                <strong><?php echo htmlspecialchars($visit['room_name']); ?></strong>
                                                in <?php echo htmlspecialchars($visit['building_name']); ?>
                                                at <?php echo date('M d, Y h:i A', strtotime($visit['visit_time'])); ?>
                                            </li>
                                        <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        <p>No recent visits.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html> 