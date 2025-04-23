<?php
require_once 'includes/functions.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - DYCI Tour System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background: #f5f5f5;
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }

        .about-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            position: relative;
        }

        .about-header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #000080;
        }

        .about-header h2 {
            color: #000080;
            font-weight: bold;
        }

        .about-section {
            margin-bottom: 2rem;
        }

        .about-section h3 {
            color: #000080;
            font-size: 1.2rem;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e0e0e0;
        }

        .feature-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .feature-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            margin-bottom: 1rem;
            background: #f8f9fa;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .feature-item:hover {
            transform: translateX(10px);
            background: #e9ecef;
        }

        .feature-icon {
            width: 40px;
            height: 40px;
            background: #000080;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            color: white;
        }

        .feature-text {
            flex: 1;
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

        .logo {
            width: 120px;
            height: auto;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="about-container">
        <a href="javascript:history.back()" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>

        <div class="about-header">
            <img src="assets/images/dyci-logo.png" alt="DYCI Logo" class="logo">
            <h2>About DYCI Tour System</h2>
        </div>

        <div class="about-section">
            <h3>Overview</h3>
            <p>Welcome to the DYCI Tour System, your comprehensive guide to exploring the Dr. Yanga's Colleges, Inc. Elida Campus. This system is designed to help students, visitors, and staff navigate our campus facilities efficiently.</p>
        </div>

        <div class="about-section">
            <h3>Key Features</h3>
            <ul class="feature-list">
                <li class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <div class="feature-text">
                        <strong>Interactive Campus Map</strong>
                        <p>Navigate through our campus with an easy-to-use interactive map showing all buildings and facilities.</p>
                    </div>
                </li>
                <li class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="feature-text">
                        <strong>Building Information</strong>
                        <p>Access detailed information about each building, including facilities, rooms, and departments.</p>
                    </div>
                </li>
                <li class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-route"></i>
                    </div>
                    <div class="feature-text">
                        <strong>Guided Tours</strong>
                        <p>Take virtual tours of our campus with step-by-step navigation and information.</p>
                    </div>
                </li>
                <li class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div class="feature-text">
                        <strong>Facility Details</strong>
                        <p>Get comprehensive information about campus facilities, including operating hours and services.</p>
                    </div>
                </li>
            </ul>
        </div>

        <div class="about-section">
            <h3>Our Mission</h3>
            <p>To provide an intuitive and informative platform that helps our community and visitors explore and understand the DYCI Elida Campus layout and facilities, enhancing the overall campus experience.</p>
        </div>
    </div>
</body>
</html> 