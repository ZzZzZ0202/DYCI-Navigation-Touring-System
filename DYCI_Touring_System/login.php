<?php
session_start();
require_once 'includes/functions.php';
require_once 'config/database.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Add CSRF protection
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('CSRF token validation failed');
    }
}

// Check if already logged in
if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'student') {
    header("Location: profile.php");
    exit();
}

// Check for remember me cookie
if (!isset($_SESSION['user_type']) && isset($_COOKIE['remember_me'])) {
    $token = $_COOKIE['remember_me'];
    $student = validateRememberMeToken($token);
    if ($student) {
        $_SESSION['user_type'] = 'student';
        $_SESSION['student_id'] = $student['student_id'];
        $_SESSION['last_activity'] = time();
        header("Location: profile.php");
        exit();
    }
}

// Add session timeout check
$timeout = 1800; // 30 minutes
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
    session_unset();
    session_destroy();
    header("Location: login.php?timeout=1");
    exit();
}

// Process login form
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || !validateCSRFToken($_POST['csrf_token'])) {
        $_SESSION['error'] = 'Invalid request. Please try again.';
        header('Location: student_login.php');
        exit();
    }

    $student_id = $_POST['student_id'] ?? '';
    $password = $_POST['password'] ?? '';
    $remember_me = isset($_POST['remember_me']);
    
    if (empty($student_id) || empty($password)) {
        $error = 'Please fill in all fields';
    } else {
        // Try to authenticate the student
        $student = authenticateStudent($student_id, $password);
        
        if ($student) {
            // Set session variables
            $_SESSION['user_type'] = 'student';
            $_SESSION['student_id'] = $student['student_id'];
            $_SESSION['student_name'] = $student['first_name'] . ' ' . $student['last_name'];
            $_SESSION['last_activity'] = time();
            
            // Handle remember me
            if ($remember_me) {
                $token = bin2hex(random_bytes(32));
                setRememberToken($student['student_id'], $token);
                setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/', '', true, true);
            }
            
            header("Location: dashboard.php");
            exit();
        } else {
            $error = 'Invalid student ID or password';
        }
    }
}

// Add timeout message
if (isset($_GET['timeout']) && $_GET['timeout'] == 1) {
    $error = 'Your session has expired. Please log in again.';
}

// Debug information
if (isset($_SESSION)) {
    error_log("Current session data: " . print_r($_SESSION, true));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login - DYCI Tour System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            max-width: 400px;
            width: 90%;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            position: relative;
        }

        .login-logo {
            width: 120px;
            height: 120px;
            margin: 0 auto 1.5rem;
            display: block;
        }

        .title {
            color: #000080;
            text-align: center;
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .input-group {
            position: relative;
        }

        .input-group-prepend .input-group-text {
            background-color: #000080;
            color: white;
            border: none;
            width: 40px;
        }

        .form-control {
            border: 1px solid #ced4da;
            border-radius: 5px;
            padding: 0.75rem;
            font-size: 1rem;
        }

        .form-control:focus {
            border-color: #000080;
            box-shadow: 0 0 0 0.2rem rgba(0,0,128,0.25);
        }

        .btn-login {
            background: #000080;
            color: white;
            width: 100%;
            padding: 0.75rem;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            margin-top: 1rem;
        }

        .btn-login:hover {
            background: #000060;
        }

        .links {
            text-align: center;
            margin-top: 1rem;
        }

        .links a {
            color: #666;
            font-size: 0.9rem;
            text-decoration: none;
            margin: 0 10px;
        }

        .links a:hover {
            color: #000080;
        }

        .admin-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #FFD700;
            color: #000080;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-weight: bold;
            font-size: 18px;
        }

        .admin-btn:hover {
            background: #FFC700;
            text-decoration: none;
            color: #000080;
        }

        .back-btn {
            position: fixed;
            bottom: 20px;
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
    <div class="login-container">
        <a href="admin_login.php" class="admin-btn">!</a>
        <img src="assets/images/dyci-logo.png" alt="DYCI Logo" class="login-logo">
        <h2 class="title">STUDENT LOG-IN PORTAL</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success" role="alert">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fas fa-id-card"></i>
                        </span>
                    </div>
                    <input type="text" 
                           name="student_id" 
                           class="form-control" 
                           placeholder="Student ID"
                           value="<?php echo isset($_POST['student_id']) ? htmlspecialchars($_POST['student_id']) : ''; ?>"
                           required>
                </div>
            </div>

            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </span>
                    </div>
                    <input type="password" 
                           name="password" 
                           class="form-control" 
                           placeholder="Password"
                           required>
                </div>
            </div>

            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" 
                           class="custom-control-input" 
                           id="remember" 
                           name="remember">
                    <label class="custom-control-label" for="remember">Remember me for 30 days</label>
                </div>
            </div>

            <button type="submit" class="btn btn-login">
                <i class="fas fa-sign-in-alt mr-2"></i>Login
            </button>
        </form>

        <div class="links">
            <a href="register.php">Don't have an account? Register here</a>
            <br>
            <a href="forgot_password.php">Forgot your password?</a>
        </div>
    </div>

    <a href="index.php" class="back-btn">
        <i class="fas fa-arrow-left"></i>
        Back to Homepage
    </a>
</body>
</html> 