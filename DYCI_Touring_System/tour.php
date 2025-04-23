<?php
require_once 'includes/functions.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DYCI Tour System - Visitor</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background-color: #3949AB;
            font-family: Arial, sans-serif;
        }

        .dashboard-container {
            padding: 2rem;
            position: relative;
        }

        .welcome-text {
            color: white;
            font-size: 2rem;
            margin: 2rem 0;
            text-align: left;
            padding-left: 1rem;
        }

        .cards-container {
            display: flex;
            justify-content: center;
            gap: 2rem;
            flex-wrap: wrap;
            padding: 2rem;
            margin-top: 3rem;
        }

        .card {
            background: white;
            border-radius: 10px;
            padding: 2rem;
            width: 300px;
            text-align: center;
            transition: transform 0.3s;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .card:hover {
            transform: translateY(-10px);
        }

        .card i {
            font-size: 3rem;
            color: #000080;
            margin-bottom: 1rem;
        }

        .card h3 {
            color: #000080;
            margin-bottom: 1rem;
            font-weight: bold;
        }

        .card p {
            color: #666;
            margin-bottom: 0;
        }

        .logo {
            width: 120px;
            height: auto;
            position: absolute;
            top: 20px;
            right: 50%;
            transform: translateX(50%);
        }

        .back-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            background: #FFD700;
            color: #000080;
            padding: 8px 20px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .back-btn:hover {
            background: #FFC700;
            transform: translateY(-2px);
            text-decoration: none;
            color: #000080;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <a href="index.php" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Back to Homepage
        </a>
        
        <img src="assets/images/dyci-logo.png" alt="DYCI Logo" class="logo">
        
        <h1 class="welcome-text">Welcome to DYCI Tour System</h1>

        <div class="cards-container">
            <a href="start_tour.php" class="card text-decoration-none">
                <i class="fas fa-building"></i>
                <h3>Start Tour</h3>
                <p>Explore DYCI's buildings and facilities through our interactive tour guide.</p>
            </a>

            <a href="about.php" class="card text-decoration-none">
                <i class="fas fa-info-circle"></i>
                <h3>About</h3>
                <p>Learn more about the DYCI Tour system and its features.</p>
            </a>

            <a href="map.php" class="card text-decoration-none">
                <i class="fas fa-map"></i>
                <h3>Campus Map</h3>
                <p>View an interactive map of DYCI Elida Campus and explore its layout.</p>
            </a>
        </div>
    </div>
</body>
</html> 