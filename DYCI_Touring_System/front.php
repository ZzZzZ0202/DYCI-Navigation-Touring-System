<?php
session_start();
if (!isset($_SESSION['user_type'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Front - DYCI Tour</title>
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
            margin-bottom: 40px;
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

        .nav-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
            width: 100%;
            max-width: 600px;
        }

        .nav-button {
            background: #000080;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 25px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .nav-button:hover {
            background: #0000a0;
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
            <div class="title">Front</div>
        </div>

        <div class="nav-container">
            <a href="entrance.php" class="nav-button">Entrance</a>
            <a href="security.php" class="nav-button">Security Guard post</a>
            <a href="frontline.php" class="nav-button">Frontline</a>
            <a href="waiting.php" class="nav-button">Waiting area</a>
        </div>

        <a href="dashboard.php" class="back-btn">Back</a>
    </div>
</body>
</html> 