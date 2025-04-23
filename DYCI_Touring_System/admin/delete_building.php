<?php
session_start();
require_once '../includes/functions.php';

// Check if logged in as admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin_login.php");
    exit();
}

if (isset($_GET['id'])) {
    $building_id = $_GET['id'];
    
    // Delete building image if exists
    $image_path = "../assets/images/buildings/" . $building_id . ".jpg";
    if (file_exists($image_path)) {
        unlink($image_path);
    }
    
    // Delete building and its rooms
    if (deleteBuilding($building_id)) {
        $_SESSION['success'] = "Building deleted successfully";
    } else {
        $_SESSION['error'] = "Error deleting building";
    }
} else {
    $_SESSION['error'] = "Building ID not provided";
}

header("Location: buildings.php");
exit(); 