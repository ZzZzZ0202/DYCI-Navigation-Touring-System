<?php
session_start();
require_once 'includes/functions.php';
require_once 'config/database.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'] ?? '';
    $password = $_POST['password'] ?? '';
    $remember_me = isset($_POST['remember_me']);

    // Validate input
    if (empty($student_id) || empty($password)) {
        $_SESSION['error'] = 'Please fill in all fields.';
        header('Location: student_login.php');
        exit();
    }

    // Authenticate student
    $student = authenticateStudent($student_id, $password);

    if ($student) {
        // Set session variables
        $_SESSION['user_type'] = 'student';
        $_SESSION['student_id'] = $student['id'];
        $_SESSION['first_name'] = $student['first_name'];

        // Set remember me cookie if checked
        if ($remember_me) {
            $token = bin2hex(random_bytes(32));
            setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/'); // 30 days
            // Store token in database (you'll need to implement this)
            storeRememberToken($student['id'], $token);
        }

        // Log successful login
        logLoginAttempt($student_id, true);

        // Redirect to dashboard
        header('Location: dashboard.php');
        exit();
    } else {
        // Log failed login attempt
        logLoginAttempt($student_id, false);

        $_SESSION['error'] = 'Invalid student ID or password.';
        header('Location: student_login.php');
        exit();
    }
} else {
    // If someone tries to access this file directly without POST data
    header('Location: student_login.php');
    exit();
}

// Helper function to log login attempts
function logLoginAttempt($student_id, $success) {
    // Get database connection
    $conn = getDatabaseConnection();
    
    $sql = "INSERT INTO login_attempts (student_id, success, attempt_time, ip_address) 
            VALUES (?, ?, NOW(), ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sis", $student_id, $success, $_SERVER['REMOTE_ADDR']);
    $stmt->execute();
    $stmt->close();
}

// Helper function to store remember me token
function storeRememberToken($student_id, $token) {
    // Get database connection
    $conn = getDatabaseConnection();
    
    // First, remove any existing tokens for this student
    $sql = "DELETE FROM remember_tokens WHERE student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $stmt->close();
    
    // Store new token
    $sql = "INSERT INTO remember_tokens (student_id, token, created_at) VALUES (?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $student_id, $token);
    $stmt->execute();
    $stmt->close();
}
?> 