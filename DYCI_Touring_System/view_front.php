<?php
session_start();
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
            background: #003399;
        }

        .header {
            background: rgba(0, 0, 0, 0.5);
            padding: 20px;
            text-align: center;
            color: white;
            position: relative;
        }

        .logo {
            width: 80px;
            height: 80px;
            margin-right: 15px;
            vertical-align: middle;
        }

        .header-title {
            font-size: 32px;
            margin: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .front-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .areas-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .area-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
            cursor: pointer;
        }

        .area-card:hover {
            transform: translateY(-5px);
        }

        .area-title {
            color: #000080;
            font-size: 20px;
            font-weight: bold;
            margin: 0 0 10px 0;
        }

        .area-description {
            color: #444;
            font-size: 14px;
            line-height: 1.5;
            margin: 0;
        }

        .navigation {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .nav-btn {
            display: inline-flex;
            align-items: center;
            padding: 12px 24px;
            background-color: #003399;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 500;
            transition: background-color 0.2s;
        }

        .nav-btn:hover {
            background-color: #002266;
        }

        .arrow {
            margin: 0 8px;
        }

        @media (max-width: 768px) {
            .areas-grid {
                grid-template-columns: 1fr;
            }
            
            .header-title {
                font-size: 24px;
            }
            
            .logo {
                width: 60px;
                height: 60px;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <h1 class="header-title">
            <img src="assets/images/logo.png" alt="DYCI Logo" class="logo">
            Front
        </h1>
    </header>

    <div class="container">
        <img src="assets/images/front/main-entrance.jpg" alt="DYCI Front View" class="front-image">

        <div class="areas-grid">
            <div class="area-card" onclick="window.location.href='view_area.php?area=entrance'">
                <h2 class="area-title">Entrance</h2>
                <p class="area-description">
                    The main entrance to DYCI, welcoming students, faculty, and visitors. Features a modern gate design with the institution's name prominently displayed.
                </p>
            </div>

            <div class="area-card" onclick="window.location.href='view_area.php?area=security'">
                <h2 class="area-title">Security Guard post</h2>
                <p class="area-description">
                    24/7 security station ensuring campus safety. All visitors must register here before entering the premises.
                </p>
            </div>

            <div class="area-card" onclick="window.location.href='view_area.php?area=frontline'">
                <h2 class="area-title">Frontline</h2>
                <p class="area-description">
                    One-stop service center for administrative needs, inquiries, and student services. Handles admissions, payments, and general information.
                </p>
            </div>

            <div class="area-card" onclick="window.location.href='view_area.php?area=waiting'">
                <h2 class="area-title">Waiting area</h2>
                <p class="area-description">
                    Comfortable seating area for visitors and students. Air-conditioned space equipped with information displays and amenities.
                </p>
            </div>
        </div>

        <div class="navigation">
            <a href="index.php" class="nav-btn">
                <span class="arrow">←</span>
                Back to Map
            </a>
            <a href="view_buildings.php" class="nav-btn">
                Next Section
                <span class="arrow">→</span>
            </a>
        </div>
    </div>
</body>
</html> 