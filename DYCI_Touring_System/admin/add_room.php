<?php
session_start();
require_once '../includes/functions.php';

// Check if logged in as admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $building_id = $_POST['building_id'] ?? '';
    $description = $_POST['description'] ?? '';
    $capacity = $_POST['capacity'] ?? null;

    if (empty($name) || empty($building_id)) {
        $_SESSION['error'] = "Room name and building are required";
        header("Location: rooms.php");
        exit();
    }

    // Handle image upload
    $image_url = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = "../assets/images/rooms/";
        $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($file_extension, $allowed_extensions)) {
            $_SESSION['error'] = "Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.";
            header("Location: rooms.php");
            exit();
        }

        // Create directory if it doesn't exist
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Add room and get the new room ID
        $room_id = addRoom($name, $building_id, $description, $capacity);
        
        if ($room_id) {
            $filename = $room_id . "." . $file_extension;
            $target_file = $upload_dir . $filename;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $image_url = "assets/images/rooms/" . $filename;
                // Update room with image URL
                updateRoomImage($room_id, $image_url);
                $_SESSION['success'] = "Room added successfully";
            } else {
                $_SESSION['error'] = "Error uploading image";
            }
        } else {
            $_SESSION['error'] = "Error adding room";
        }
    } else {
        // Add room without image
        if (addRoom($name, $building_id, $description, $capacity)) {
            $_SESSION['success'] = "Room added successfully";
        } else {
            $_SESSION['error'] = "Error adding room";
        }
    }

    // Redirect back to rooms page with the current building filter
    header("Location: rooms.php?building_id=" . $building_id);
    exit();
} 