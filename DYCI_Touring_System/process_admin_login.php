<?php
session_start();
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (authenticateAdmin($username, $password)) {
        $_SESSION['admin_id'] = 1;
        $_SESSION['admin_username'] = $username;
        
        // Handle remember me
        if (isset($_POST['remember'])) {
            $token = bin2hex(random_bytes(32));
            setcookie('admin_remember', $token, time() + (30 * 24 * 60 * 60), '/');
            // You might want to store this token in the database for validation
        }
        
        header('Location: admin/dashboard.php');
        exit();
    }
}

// If we get here, authentication failed
header('Location: admin_login.php?error=1');
exit();
?> 