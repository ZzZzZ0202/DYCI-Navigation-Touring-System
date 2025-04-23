<?php
session_start();
require_once 'includes/functions.php';

// Generate CSRF token if not exists
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if (isset($_SESSION['user_type'])) {
    header("Location: dashboard.php");
    exit();
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $password = $_POST['password'];
    
    $student = authenticateStudent($student_id, $password);
    
    if ($student) {
        $_SESSION['user_type'] = 'student';
        $_SESSION['student_id'] = $student['student_id'];
        $_SESSION['student_name'] = $student['name'];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = 'Invalid student ID or password';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Log-in Portal - DYCI</title>
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

        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-grow: 1;
        }

        .login-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            padding: 2rem;
            text-align: center;
            position: relative;
        }

        .info-icon {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: #ffd700;
            color: #000;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
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

        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 1rem 0;
            color: #666;
        }

        .remember-me input[type="checkbox"] {
            width: 16px;
            height: 16px;
        }

        .btn-login {
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
    <a href="index.php" class="back-button">Back</a>
    <div class="login-container">
        <div class="login-card">
            <a href="#" class="info-icon" onclick="confirmAdminAccess()">!</a>
            <img src="assets/images/dyci-logo.png" alt="DYCI Logo" class="logo">
            <h1 class="portal-title">STUDENT LOG-IN PORTAL</h1>
            
            <form action="login.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <div class="form-control-icon">
                    <i class="fas fa-id-card"></i>
                    <input type="text" class="form-control" name="student_id" placeholder="Student ID" required>
                </div>
                
                <div class="form-control-icon">
                    <i class="fas fa-lock"></i>
                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                </div>
                
                <div class="remember-me">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Remember me for 30 days</label>
                </div>
                
                <button type="submit" class="btn btn-login">Login</button>
                
                <div class="links">
                    <p>Don't have an account? <a href="register.php">Register here</a></p>
                    <p><a href="forgot_password.php">Forgot your password?</a></p>
                </div>
            </form>
        </div>
    </div>
    <script>
        function confirmAdminAccess() {
            alert("This is for Authorized Access only. Only the Admin can log in to this section.");
            window.location.href = 'admin_login.php';
        }
    </script>
</body>
</html> 