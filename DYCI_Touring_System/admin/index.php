<?php
session_start();
require_once '../includes/functions.php';

// If already logged in as admin, redirect to admin dashboard
if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
    header("Location: dashboard.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (authenticateAdmin($username, $password)) {
        $_SESSION['admin'] = true;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - DYCI Tour</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            min-height: 100vh;
            background: url('../images/dyci_bg.jpg') no-repeat center center fixed;
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
            justify-content: center;
            padding: 20px;
            box-sizing: border-box;
        }

        .login-container {
            background: white;
            border-radius: 10px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo {
            width: 80px;
            height: 80px;
            margin-bottom: 20px;
        }

        .title {
            color: #000080;
            font-size: 24px;
            font-weight: bold;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            color: #333;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }

        .form-input:focus {
            border-color: #000080;
            outline: none;
        }

        .error-message {
            color: #ff0000;
            margin-bottom: 20px;
            text-align: center;
        }

        .submit-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #000080, #0000ff);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: opacity 0.3s;
        }

        .submit-btn:hover {
            opacity: 0.9;
        }

        .back-link {
            color: white;
            text-decoration: none;
            margin-top: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: bold;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .back-arrow {
            width: 20px;
            height: 20px;
            fill: white;
        }
    </style>
</head>
<body>
    <div class="overlay">
        <div class="login-container">
            <div class="header">
                <img src="../images/dyci_logo.png" alt="DYCI Logo" class="logo">
                <div class="title">Admin Login</div>
            </div>

            <?php if ($error): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label class="form-label" for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-input" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-input" required>
                </div>

                <button type="submit" class="submit-btn">Login</button>
            </form>
        </div>

        <a href="../index.php" class="back-link">
            <svg class="back-arrow" viewBox="0 0 24 24">
                <path fill="currentColor" d="M20,11V13H8L13.5,18.5L12.08,19.92L4.16,12L12.08,4.08L13.5,5.5L8,11H20Z" />
            </svg>
            Back to Main Site
        </a>
    </div>
</body>
</html> 