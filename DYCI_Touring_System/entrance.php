<?php
session_start();
require_once 'includes/functions.php';

if (!isset($_SESSION['user_type'])) {
    header("Location: index.php");
    exit();
}

// Get location info from database
$location = getLocationInfo('Entrance');

// Log the visit
logVisit($_SESSION['user_type'], 
    isset($_SESSION['student_id']) ? $_SESSION['student_id'] : null, 
    $location['id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Entrance - DYCI Tour</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            min-height: 100vh;
            background: url('images/dyci_bg.jpg') no-repeat center center fixed;
            background-size: cover;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(rgba(0, 0, 150, 0.8), rgba(0, 0, 150, 0.8));
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        .header {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 20px;
            width: 100%;
            max-width: 800px;
        }

        .logo {
            width: 60px;
            height: 60px;
        }

        .title {
            color: white;
            font-size: 32px;
            font-weight: bold;
        }

        .content {
            background: white;
            border-radius: 15px;
            padding: 20px;
            width: 100%;
            max-width: 800px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .location-title {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .info-icon {
            width: 24px;
            height: 24px;
            background: #000080;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        .location-name {
            font-size: 18px;
            font-weight: bold;
            color: #000;
            background: white;
            padding: 8px 20px;
            border-radius: 20px;
            border: 2px solid #000080;
        }

        .location-image {
            width: 100%;
            height: auto;
            border-radius: 10px;
            margin: 20px 0;
        }

        .back-btn {
            position: fixed;
            bottom: 40px;
            left: 40px;
            background: #FFD700;
            color: #000;
            padding: 10px 30px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
        }

        .back-btn:hover {
            background: #FFC700;
        }
    </style>
</head>
<body>
    <div class="overlay">
        <div class="header">
            <img src="images/dyci_logo.png" alt="DYCI Logo" class="logo">
            <div class="title">Entrance (Elida Campus)</div>
        </div>

        <div class="content">
            <div class="location-title">
                <div class="info-icon">i</div>
                <div class="location-name">The entrance to the Elida Campus</div>
            </div>
            <img src="images/entrance.jpg" alt="DYCI Entrance" class="location-image">
        </div>

        <a href="front.php" class="back-btn">Back</a>
    </div>
</body>
</html> 