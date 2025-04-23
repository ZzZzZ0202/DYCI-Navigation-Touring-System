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
    echo json_encode(['error' => 'Building ID not provided']);
    exit();
}

$building = getBuildingDetails($_GET['id']);

if (!$building) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Building not found']);
    exit();
}

header('Content-Type: application/json');
echo json_encode($building); 