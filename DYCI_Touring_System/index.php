<?php
session_start();
require_once 'includes/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DYCI ELIDA CAMPUS TOUR NAVIGATION</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background-color: #3949AB;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .logo {
            width: 150px;
            height: auto;
            margin-bottom: 2rem;
        }

        .title {
            color: white;
            font-size: 2.5rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 3rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        .buttons-container {
            display: flex;
            gap: 2rem;
            justify-content: center;
            align-items: center;
        }

        .nav-button {
            background: white;
            border: none;
            border-radius: 10px;
            padding: 2rem;
            width: 200px;
            text-align: center;
            text-decoration: none;
            color: #3949AB;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }

        .nav-button:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.2);
            text-decoration: none;
            color: #3949AB;
        }

        .nav-button i {
            font-size: 3rem;
        }

        .nav-button span {
            font-size: 1.2rem;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .buttons-container {
                flex-direction: column;
            }

            .nav-button {
                width: 80%;
                max-width: 300px;
            }

            .title {
                font-size: 2rem;
                padding: 0 1rem;
            }
        }
    </style>
</head>
<body>
    <img src="assets/images/dyci-logo.png" alt="DYCI Logo" class="logo">
    <h1 class="title">DYCI ELIDA CAMPUS TOUR NAVIGATION</h1>
    
    <div class="buttons-container">
        <a href="student_login.php" class="nav-button">
            <i class="fas fa-user-graduate"></i>
            <span>Student</span>
        </a>
        <a href="tour.php" class="nav-button">
            <i class="fas fa-user"></i>
            <span>Visitor</span>
        </a>
    </div>
</body>
</html> 