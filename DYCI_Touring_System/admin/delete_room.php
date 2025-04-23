<?php
session_start();
require_once '../includes/functions.php';

// Check if logged in as admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin_login.php");
    exit();
}

if (isset($_GET['id'])) {
    $room_id = $_GET['id'];
    
    // Get room details to know which building to redirect to
    $room = getRoomDetails($room_id);
    $building_id = $room ? $room['building_id'] : null;
    
    // Delete room image if exists
    $image_path = "../assets/images/rooms/" . $room_id . ".jpg";
    if (file_exists($image_path)) {
        unlink($image_path);
    }
    
    // Delete room
    if (deleteRoom($room_id)) {
        $_SESSION['success'] = "Room deleted successfully";
    } else {
        $_SESSION['error'] = "Error deleting room";
    }
} else {
    $_SESSION['error'] = "Room ID not provided";
}

// Redirect back to rooms page with the current building filter
header("Location: rooms.php" . ($building_id ? "?building_id=" . $building_id : ""));
exit(); 