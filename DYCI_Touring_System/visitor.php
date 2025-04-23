<?php
session_start();
$_SESSION['user_type'] = 'visitor';
header("Location: dashboard.php");
exit();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DYCI Tour - Visitor</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <div class="container">
        <h1>Welcome Visitor</h1>
        <!-- Add your visitor content here -->
        <div class="navigation">
            <a href="tour.php" class="btn">Start Tour</a>
            <a href="map.php" class="btn">View Map</a>
        </div>
    </div>
</body>
</html> 