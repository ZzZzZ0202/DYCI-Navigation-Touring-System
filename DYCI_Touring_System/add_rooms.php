<?php
require_once 'config/database.php';

try {
    // Add new rooms for Building B
    $rooms = [
        ['Room 102', 'General purpose classroom'],
        ['Room 103', 'General purpose classroom'],
        ['Room 104', 'General purpose classroom'],
        ['Room 105', 'General purpose classroom'],
        ['Room 201', 'General purpose classroom'],
        ['Room 202', 'General purpose classroom'],
        ['Room 203', 'General purpose classroom'],
        ['Room 204', 'General purpose classroom'],
        ['Room 205', 'General purpose classroom'],
        ['General Service Office', 'Administrative office for general services']
    ];

    $stmt = $pdo->prepare("INSERT INTO rooms (building_id, name, description) VALUES (2, ?, ?)");
    
    foreach ($rooms as $room) {
        $stmt->execute($room);
    }

    echo "Rooms added successfully!";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 