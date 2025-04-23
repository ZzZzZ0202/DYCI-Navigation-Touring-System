<?php
session_start();
require_once 'includes/functions.php';

// Get student details if logged in
$is_student = isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'student';
$student = $is_student ? getStudentDetails($_SESSION['student_id']) : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DYCI Tour System</title>
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

        .student-login {
            position: absolute;
            top: 20px;
            right: 20px;
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

        .student-login:hover {
            background: #FFC700;
            transform: translateY(-2px);
            text-decoration: none;
            color: #000080;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding: 1rem;
        }

        .profile-button {
            background: #FFD700;
            color: #000080;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }

        .profile-button:hover {
            background: #FFC700;
            transform: translateY(-2px);
            text-decoration: none;
            color: #000080;
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

        .auth-buttons {
            display: flex;
            gap: 1rem;
        }

        .logout-button {
            background: #DC3545;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s;
        }

        .logout-button:hover {
            background: #C82333;
            text-decoration: none;
            color: white;
        }

        .logo {
            width: 120px;
            height: auto;
            position: absolute;
            top: 20px;
            right: 50%;
            transform: translateX(50%);
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <?php if (!$is_student): ?>
            <a href="login.php" class="student-login">
                <i class="fas fa-sign-in-alt"></i>
                Student Login
            </a>
        <?php endif; ?>
        
        <img src="assets/images/dyci-logo.png" alt="DYCI Logo" class="logo">
        
        <h1 class="welcome-text">
            <?php if ($is_student): ?>
                Welcome, <?php echo htmlspecialchars($student['first_name']); ?>!
                <div class="auth-buttons">
                    <a href="profile.php" class="profile-button">
                        <i class="fas fa-user"></i>
                        My Profile
                    </a>
                    <a href="logout.php" class="logout-button">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </a>
                </div>
            <?php else: ?>
                Welcome to DYCI Tour System
            <?php endif; ?>
        </h1>

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