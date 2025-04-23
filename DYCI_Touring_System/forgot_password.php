<?php
session_start();
require_once 'includes/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - DYCI</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #3f51b5;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            padding: 2rem;
        }

        .back-button {
            background-color: #ffd700;
            color: #000;
            padding: 0.5rem 1.5rem;
            border: none;
            border-radius: 4px;
            font-weight: 500;
            text-decoration: none;
            align-self: flex-start;
            margin-bottom: 2rem;
        }

        .back-button:hover {
            background-color: #ffc700;
            text-decoration: none;
            color: #000;
        }

        .forgot-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-grow: 1;
        }

        .forgot-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            padding: 2rem;
            text-align: center;
            position: relative;
        }

        .logo {
            width: 120px;
            height: 120px;
            margin-bottom: 1.5rem;
        }

        .portal-title {
            color: #000080;
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .portal-subtitle {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }

        .form-control {
            padding: 0.75rem 2.25rem;
            font-size: 1rem;
            border: 1px solid #ced4da;
            border-radius: 4px;
            margin-bottom: 1rem;
        }

        .form-control-icon {
            position: relative;
        }

        .form-control-icon i {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #000080;
            z-index: 2;
        }

        .btn-reset {
            background-color: #000080;
            color: white;
            width: 100%;
            padding: 0.75rem;
            border: none;
            border-radius: 4px;
            font-weight: 500;
            margin: 1rem 0;
        }

        .links {
            text-align: center;
            font-size: 0.9rem;
            color: #666;
        }

        .links a {
            color: #666;
            text-decoration: none;
        }

        .links a:hover {
            text-decoration: underline;
        }

        .links p {
            margin: 0.5rem 0;
        }
    </style>
</head>
<body>
    <a href="javascript:history.back()" class="back-button">Back</a>
    <div class="forgot-container">
        <div class="forgot-card">
            <img src="assets/images/dyci-logo.png" alt="DYCI Logo" class="logo">
            <h1 class="portal-title">FORGOT PASSWORD</h1>
            <p class="portal-subtitle">Enter your student ID and email address to reset your password</p>
            
            <form action="process_forgot_password.php" method="POST">
                <div class="form-control-icon">
                    <i class="fas fa-id-card"></i>
                    <input type="text" class="form-control" name="student_id" placeholder="Student ID" required>
                </div>
                
                <div class="form-control-icon">
                    <i class="fas fa-envelope"></i>
                    <input type="email" class="form-control" name="email" placeholder="Email Address" required>
                </div>
                
                <button type="submit" class="btn btn-reset">Reset Password</button>
                
                <div class="links">
                    <p>Remember your password? <a href="student_login.php">Back to Login</a></p>
                </div>
            </form>
        </div>
    </div>
</body>
</html> 