<?php
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin_login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Admin Panel'; ?> - DYCI</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo-container">
                <img src="../assets/images/dyci-logo.png" alt="DYCI Logo" class="logo">
                <h5>Admin Panel</h5>
            </div>
            <nav class="nav-menu">
                <a href="dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="buildings.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'buildings.php' ? 'active' : ''; ?>">
                    <i class="fas fa-building"></i> Buildings
                </a>
                <a href="rooms.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'rooms.php' ? 'active' : ''; ?>">
                    <i class="fas fa-door-open"></i> Rooms
                </a>
                <a href="schedules.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'schedules.php' ? 'active' : ''; ?>">
                    <i class="fas fa-calendar-alt"></i> Schedules
                </a>
                <a href="students.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'students.php' ? 'active' : ''; ?>">
                    <i class="fas fa-users"></i> Students
                </a>
                <a href="map.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'map.php' ? 'active' : ''; ?>">
                    <i class="fas fa-map"></i> Campus Map
                </a>
                <a href="logout.php">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="top-bar">
                <h2><?php echo $page_title ?? 'Admin Panel'; ?></h2>
                <div class="user-info">
                    Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!
                </div>
            </div>
            <div class="content-wrapper"> 