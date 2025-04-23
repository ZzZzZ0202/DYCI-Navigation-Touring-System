<?php
session_start();
require_once 'includes/functions.php';

if (!isset($_SESSION['user_type'])) {
    header("Location: index.php");
    exit();
}

$building_id = isset($_GET['id']) ? (int)$_GET['id'] : 2; // Default to Building B
$building = getBuildingDetails($building_id);
$rooms = getRoomsByBuilding($building_id);

if (!$building) {
    header("Location: tour.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($building['name']); ?> - DYCI Tour</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            min-height: 100vh;
            background: url('images/dyci_bg.jpg') no-repeat center center fixed;
            background-size: cover;
        }

        .header-banner {
            background: linear-gradient(to bottom, rgba(0, 0, 128, 0.9), rgba(0, 0, 128, 0.8));
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .logo {
            width: 50px;
            height: 50px;
        }

        .header-title {
            color: white;
            font-size: 28px;
            font-weight: bold;
        }

        .content-area {
            padding: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: calc(100vh - 90px);
            background: rgba(255, 255, 255, 0.9);
        }

        .rooms-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
            max-width: 1000px;
            width: 100%;
            margin: 20px auto;
        }

        .room-btn {
            background: #000080;
            color: white;
            padding: 15px 20px;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            transition: transform 0.2s, background-color 0.2s;
            min-height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .room-btn:hover {
            background: #000099;
            transform: translateY(-2px);
        }

        .navigation-btns {
            display: flex;
            justify-content: space-between;
            width: 100%;
            max-width: 1000px;
            margin-top: 40px;
        }

        .nav-btn {
            background: #000080;
            color: white;
            padding: 12px 40px;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.2s;
        }

        .nav-btn:hover {
            background: #000099;
        }
    </style>
</head>
<body>
    <div class="header-banner">
        <img src="images/dyci_logo.png" alt="DYCI Logo" class="logo">
        <div class="header-title">Building (B)</div>
    </div>

    <div class="content-area">
        <div class="rooms-grid">
            <a href="view_room.php?id=11" class="room-btn">Room 102</a>
            <a href="view_room.php?id=12" class="room-btn">Room 103</a>
            <a href="view_room.php?id=13" class="room-btn">Room 104</a>
            <a href="view_room.php?id=14" class="room-btn">Room 105</a>
            <a href="view_room.php?id=15" class="room-btn">Room 201</a>
            <a href="view_room.php?id=16" class="room-btn">Room 202</a>
            <a href="view_room.php?id=17" class="room-btn">Room 203</a>
            <a href="view_room.php?id=18" class="room-btn">Room 204</a>
            <a href="view_room.php?id=19" class="room-btn">Room 205</a>
            <a href="view_room.php?id=20" class="room-btn">General Service Office</a>
            <div></div>
            <a href="view_room.php?id=21" class="room-btn">Elida Campus Court</a>
        </div>

        <div class="navigation-btns">
            <a href="tour.php" class="nav-btn">Back</a>
            <a href="#" class="nav-btn">Next</a>
        </div>
    </div>
</body>
</html> 