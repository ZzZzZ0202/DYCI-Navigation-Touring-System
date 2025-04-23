<?php
session_start();
require_once 'includes/functions.php';

$building_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Building information (you'll need to implement this in your database)
$buildings = [
    1 => [
        'name' => 'Building A',
        'description' => 'Main administrative building housing key offices and facilities.',
        'floors' => '2',
        'facilities' => [
            'Administrative Offices',
            'Lecture Rooms',
            'Student Services',
            'Meeting Rooms'
        ],
        'rooms' => [
            ['id' => 1, 'name' => 'Cashier', 'floor' => 'Ground Floor'],
            ['id' => 2, 'name' => 'College of Finance and Accounting Office', 'floor' => 'Ground Floor'],
            ['id' => 3, 'name' => 'College Registrar', 'floor' => 'Ground Floor'],
            ['id' => 4, 'name' => 'Finance Office', 'floor' => 'Ground Floor'],
            ['id' => 5, 'name' => 'Graduate School Office', 'floor' => 'Second Floor'],
            ['id' => 6, 'name' => 'Human Resource Management and Development Office', 'floor' => 'Second Floor'],
            ['id' => 7, 'name' => 'Office of Student Affairs', 'floor' => 'Ground Floor'],
            ['id' => 8, 'name' => 'Paraya/NSTP', 'floor' => 'Second Floor'],
            ['id' => 9, 'name' => 'Room 101', 'floor' => 'First Floor'],
            ['id' => 10, 'name' => 'Supreme Student Council Office', 'floor' => 'Ground Floor']
        ]
    ],
    2 => [
        'name' => 'Front',
        'description' => 'Main entrance and frontline services area.',
        'floors' => '1',
        'facilities' => [
            'Security Services',
            'Information Desk',
            'Waiting Area',
            'Main Entrance'
        ],
        'rooms' => [
            ['id' => 11, 'name' => 'Entrance', 'floor' => 'Ground Floor'],
            ['id' => 12, 'name' => 'Security Guard post', 'floor' => 'Ground Floor'],
            ['id' => 13, 'name' => 'Frontline', 'floor' => 'Ground Floor'],
            ['id' => 14, 'name' => 'Waiting area', 'floor' => 'Ground Floor']
        ]
    ]
];

if (!isset($buildings[$building_id])) {
    header("Location: tour.php");
    exit();
}

$building = $buildings[$building_id];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($building['name']); ?> - DYCI Tour</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            min-height: 100vh;
            background: #003399;
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
        }

        .card {
            background: white;
            border-radius: 8px;
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .title {
            color: #71369B;
            font-size: 32px;
            margin: 0 0 10px 0;
        }

        .room-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .room-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #eee;
        }

        .room-item:last-child {
            border-bottom: none;
        }

        .room-name {
            font-size: 16px;
            color: #333;
        }

        .view-btn {
            background: #71369B;
            color: white;
            padding: 8px 24px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: background-color 0.2s;
        }

        .view-btn:hover {
            background: #5c2b82;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            padding: 12px 24px;
            background: #71369B;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 500;
            transition: background-color 0.2s;
            margin-left: 20px;
        }

        .back-btn:hover {
            background: #5c2b82;
        }

        .arrow {
            margin-right: 8px;
        }

        @media (max-width: 768px) {
            .title {
                font-size: 24px;
            }
            
            .room-item {
                padding: 12px;
            }
            
            .view-btn {
                padding: 6px 16px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <h1 class="title"><?php echo htmlspecialchars($building['name']); ?></h1>
            </div>

            <ul class="room-list">
                <?php foreach ($building['rooms'] as $room): ?>
                <li class="room-item">
                    <span class="room-name"><?php echo htmlspecialchars($room['name']); ?></span>
                    <a href="view_room.php?id=<?php echo $room['id']; ?>" class="view-btn">View</a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <a href="tour.php" class="back-btn">
            <span class="arrow">←</span>
            Back to Rooms List
        </a>
    </div>
</body>
</html> 