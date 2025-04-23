<?php
session_start();
require_once '../includes/functions.php';

// Check if logged in as admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $floor = $_POST['floor'] ?? 1;

    if (empty($id) || empty($name) || empty($description)) {
        $_SESSION['error'] = "All fields are required";
        header("Location: buildings.php");
        exit();
    }

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = "../assets/images/buildings/";
        $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($file_extension, $allowed_extensions)) {
            $_SESSION['error'] = "Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.";
            header("Location: buildings.php");
            exit();
        }

        // Create directory if it doesn't exist
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $filename = $id . "." . $file_extension;
        $target_file = $upload_dir . $filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image_url = "assets/images/buildings/" . $filename;
            // Update building with new image
            updateBuildingImage($id, $image_url);
        } else {
            $_SESSION['error'] = "Error uploading image";
            header("Location: buildings.php");
            exit();
        }
    }

    // Update building details
    if (updateBuilding($id, $name, $floor, $description)) {
        $_SESSION['success'] = "Building updated successfully";
    } else {
        $_SESSION['error'] = "Error updating building";
    }

    header("Location: buildings.php");
    exit();
} 