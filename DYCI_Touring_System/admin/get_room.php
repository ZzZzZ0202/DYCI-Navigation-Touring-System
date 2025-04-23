<?php
session_start();
require_once '../includes/functions.php';

// Check if logged in as admin
if (!isset($_SESSION['admin_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

if (!isset($_GET['id'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Room ID not provided']);
    exit();
}

$room = getRoomDetails($_GET['id']);

if (!$room) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Room not found']);
    exit();
}

header('Content-Type: application/json');
echo json_encode($room); 