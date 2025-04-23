<?php
session_start();
require_once 'includes/functions.php';

$area = isset($_GET['area']) ? $_GET['area'] : '';

// Area information
$areas = [
    'entrance' => [
        'title' => 'Main Entrance',
        'description' => 'The main gateway to DYCI campus, featuring a modern design that welcomes students, faculty, and visitors. The entrance is equipped with turnstiles and ID scanning system for enhanced security.',
        'features' => [
            'Modern gate design',
            'Electronic turnstiles',
            'ID scanning system',
            'Information boards',
            'Covered walkway'
        ],
        'image' => 'entrance.jpg'
    ],
    'security' => [
        'title' => 'Security Guard Post',
        'description' => 'Our 24/7 security station is the first point of contact for campus safety. Trained security personnel monitor all entry and exit points, ensuring a safe environment for everyone.',
        'features' => [
            '24/7 security coverage',
            'Visitor registration system',
            'CCTV monitoring',
            'Emergency response center',
            'Lost and found service'
        ],
        'image' => 'security.jpg'
    ],
    'frontline' => [
        'title' => 'Frontline Services',
        'description' => 'The Frontline Services office serves as a one-stop center for administrative needs. Our friendly staff are ready to assist with inquiries, admissions, payments, and other student services.',
        'features' => [
            'Admissions assistance',
            'Payment transactions',
            'Document requests',
            'Student ID processing',
            'General information desk'
        ],
        'image' => 'frontline.jpg'
    ],
    'waiting' => [
        'title' => 'Waiting Area',
        'description' => 'A comfortable space designed for visitors and students waiting for services or appointments. The air-conditioned area provides a relaxing environment with essential amenities.',
        'features' => [
            'Air-conditioned space',
            'Comfortable seating',
            'Information displays',
            'Water dispenser',
            'Reading materials'
        ],
        'image' => 'waiting.jpg'
    ]
];

if (!isset($areas[$area])) {
    header("Location: view_front.php");
    exit();
}

$current_area = $areas[$area];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($current_area['title']); ?> - DYCI Tour</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            min-height: 100vh;
            background: #003399;
        }

        .header {
            background: rgba(0, 0, 0, 0.5);
            padding: 20px;
            text-align: center;
            color: white;
        }

        .header-title {
            font-size: 32px;
            margin: 0;
        }

        .container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .area-card {
            background: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .area-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .area-description {
            color: #444;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .features-title {
            color: #000080;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .features-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            background: #f8f9fa;
            border-radius: 6px;
            color: #444;
        }

        .feature-icon {
            margin-right: 10px;
            color: #003399;
        }

        .navigation {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .nav-btn {
            display: inline-flex;
            align-items: center;
            padding: 12px 24px;
            background-color: #003399;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 500;
            transition: background-color 0.2s;
        }

        .nav-btn:hover {
            background-color: #002266;
        }

        .arrow {
            margin: 0 8px;
        }

        @media (max-width: 768px) {
            .header-title {
                font-size: 24px;
            }
            
            .area-image {
                height: 300px;
            }
            
            .features-list {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <h1 class="header-title"><?php echo htmlspecialchars($current_area['title']); ?></h1>
    </header>

    <div class="container">
        <div class="area-card">
            <img src="assets/images/front/<?php echo htmlspecialchars($current_area['image']); ?>" 
                 alt="<?php echo htmlspecialchars($current_area['title']); ?>" 
                 class="area-image">

            <p class="area-description"><?php echo htmlspecialchars($current_area['description']); ?></p>

            <h2 class="features-title">Features & Amenities</h2>
            <ul class="features-list">
                <?php foreach ($current_area['features'] as $feature): ?>
                <li class="feature-item">
                    <span class="feature-icon">•</span>
                    <?php echo htmlspecialchars($feature); ?>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="navigation">
            <a href="view_front.php" class="nav-btn">
                <span class="arrow">←</span>
                Back to Front
            </a>
            <?php if ($area === 'waiting'): ?>
            <a href="view_buildings.php" class="nav-btn">
                Next Section
                <span class="arrow">→</span>
            </a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html> 