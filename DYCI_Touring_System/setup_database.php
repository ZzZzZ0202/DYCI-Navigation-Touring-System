<?php
require_once 'config/database.php';

try {
    echo "Starting database setup...<br>";

    // Test database connection
    echo "Testing database connection...<br>";
    $pdo->getAttribute(PDO::ATTR_CONNECTION_STATUS);
    echo "Database connection successful!<br>";

    // First, disable foreign key checks
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    
    // Drop tables in reverse order of dependencies
    $pdo->exec("DROP TABLE IF EXISTS room_schedules");
    $pdo->exec("DROP TABLE IF EXISTS visit_logs");
    $pdo->exec("DROP TABLE IF EXISTS rooms");
    $pdo->exec("DROP TABLE IF EXISTS buildings");
    
    // Re-enable foreign key checks
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    
    echo "Tables dropped successfully<br>";

    // Create buildings table
    $pdo->exec("CREATE TABLE IF NOT EXISTS buildings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        floor VARCHAR(50) NOT NULL,
        description TEXT,
        image_url VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Create rooms table
    $pdo->exec("CREATE TABLE IF NOT EXISTS rooms (
        id INT AUTO_INCREMENT PRIMARY KEY,
        building_id INT,
        name VARCHAR(100) NOT NULL,
        description TEXT,
        image_url VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (building_id) REFERENCES buildings(id)
    )");

    // Create room_schedules table
    $pdo->exec("CREATE TABLE IF NOT EXISTS room_schedules (
        id INT AUTO_INCREMENT PRIMARY KEY,
        room_id INT,
        day_of_week ENUM('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday') NOT NULL,
        start_time TIME NOT NULL,
        end_time TIME NOT NULL,
        subject VARCHAR(100) NOT NULL,
        FOREIGN KEY (room_id) REFERENCES rooms(id)
    )");

    // Insert buildings
    $pdo->exec("INSERT INTO buildings (name, floor, description) VALUES
        ('Building (A)', 'First Floor', 'Main administrative building housing key offices and services'),
        ('Building (B)', 'First and Second Floor', 'Academic building with classrooms and service offices'),
        ('Front', 'Ground Floor', 'Main entrance and frontline services area')
    ");

    // Get building IDs
    $stmt = $pdo->query("SELECT id, name FROM buildings");
    $buildings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $building_a_id = null;
    $building_b_id = null;
    $front_id = null;
    
    foreach ($buildings as $building) {
        if ($building['name'] === 'Building (A)') {
            $building_a_id = $building['id'];
        } else if ($building['name'] === 'Building (B)') {
            $building_b_id = $building['id'];
        } else if ($building['name'] === 'Front') {
            $front_id = $building['id'];
        }
    }

    // Insert Front building rooms
    $front_rooms = [
        ['Entrance', 'Main entrance area'],
        ['Security Guard post', 'Security monitoring station'],
        ['Frontline', 'Information and assistance desk'],
        ['Waiting area', 'Visitor waiting area']
    ];

    // Insert Building A rooms
    $building_a_rooms = [
        ['Collge Registar', 'Office handling student registration and records'],
        ['Finnace Office', 'Handles financial transactions'],
        ['Supreme Student Court Council Office', 'Student government office'],
        ['Paraya/NSTP', 'National Service Training Program office'],
        ['Cashier', 'Processes payments and transactions'],
        ['Office of Student Affairs', 'Manages student services and activities'],
        ['Room 101', 'General purpose room'],
        ['Human Resource Management and Development Office', 'HR management office'],
        ['College of Finance and Accounting Office', 'Finance and accounting department'],
        ['Graduate School Office', 'Graduate studies administration']
    ];

    // Insert Building B rooms exactly as shown in the image
    $building_b_rooms = [
        ['Room 102', 'General purpose classroom'],
        ['Room 103', 'General purpose classroom'],
        ['Room 104', 'General purpose classroom'],
        ['Room 105', 'General purpose classroom'],
        ['Room 201', 'General purpose classroom'],
        ['Room 202', 'General purpose classroom'],
        ['Room 203', 'General purpose classroom'],
        ['Room 204', 'General purpose classroom'],
        ['Room 205', 'General purpose classroom'],
        ['General Service Office', 'Administrative service office'],
        ['Elida Campus Court', 'Campus court facility']
    ];

    $stmt = $pdo->prepare("INSERT INTO rooms (building_id, name, description) VALUES (?, ?, ?)");
    
    // Insert Front building rooms
    foreach ($front_rooms as $room) {
        $stmt->execute([$front_id, $room[0], $room[1]]);
    }

    // Insert Building A rooms
    foreach ($building_a_rooms as $room) {
        $stmt->execute([$building_a_id, $room[0], $room[1]]);
    }

    // Insert Building B rooms
    foreach ($building_b_rooms as $room) {
        $stmt->execute([$building_b_id, $room[0], $room[1]]);
    }

    // Get room IDs for Building B rooms
    $stmt = $pdo->query("SELECT id, name FROM rooms WHERE building_id = " . $building_b_id);
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $room_ids = [];
    foreach ($rooms as $room) {
        $room_ids[$room['name']] = $room['id'];
    }

    // Insert schedules for Room 102 exactly as shown in the image
    $schedules = [
        // Room 102 Schedule
        ['Room 102', [
            // Monday
            ['Monday', '07:00:00', '10:00:00', 'CAA'],
            
            // Tuesday
            ['Tuesday', '07:00:00', '10:00:00', 'SAD'],
            ['Tuesday', '11:00:00', '12:00:00', 'PE-2'],
            ['Tuesday', '13:00:00', '16:00:00', 'DMS-2'],
            ['Tuesday', '16:30:00', '19:00:00', 'EFL'],
            
            // Wednesday
            ['Wednesday', '13:00:00', '16:00:00', 'IPT'],
            ['Wednesday', '16:30:00', '19:00:00', 'NAC'],
            
            // Saturday
            ['Saturday', '17:00:00', '19:00:00', 'FRE']
        ]]
    ];

    // Insert all schedules
    $stmt = $pdo->prepare("INSERT INTO room_schedules (room_id, day_of_week, start_time, end_time, subject) VALUES (?, ?, ?, ?, ?)");
    foreach ($schedules as $room_schedule) {
        $room_name = $room_schedule[0];
        $room_id = $room_ids[$room_name];
        foreach ($room_schedule[1] as $schedule) {
            $stmt->execute([
                $room_id,
                $schedule[0],
                $schedule[1],
                $schedule[2],
                $schedule[3]
            ]);
        }
    }

    echo "Database setup completed successfully with all rooms and schedules!";

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 