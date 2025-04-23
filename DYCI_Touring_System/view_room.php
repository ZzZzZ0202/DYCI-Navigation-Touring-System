<?php
session_start();
require_once 'includes/functions.php';

$room_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$room = getRoomDetails($room_id);

if (!$room) {
    header("Location: start_tour.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($room['name']); ?> - DYCI Tour</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .room-container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
        }

        .room-header {
            background: #000080;
            color: white;
            padding: 2rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            text-align: center;
        }

        .room-image {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 2rem;
        }

        .info-card {
            background: white;
            border-radius: 10px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .info-card h3 {
            color: #000080;
            margin-bottom: 1rem;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .schedule-table {
            width: 100%;
            margin-top: 1rem;
        }

        .schedule-table th {
            background: #000080;
            color: white;
            padding: 0.75rem;
        }

        .schedule-table td {
            padding: 0.75rem;
            border: 1px solid #dee2e6;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #FFD700;
            color: #000080;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            margin-bottom: 2rem;
            transition: all 0.3s;
        }

        .back-btn:hover {
            background: #FFC700;
            transform: translateY(-2px);
            text-decoration: none;
            color: #000080;
        }

        .facility-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .facility-item {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 5px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .schedule-list {
            list-style: none;
            padding: 0;
        }

        .schedule-item {
            padding: 0.5rem;
            border-bottom: 1px solid #dee2e6;
        }

        .schedule-item:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>
    <div class="room-container">
        <a href="javascript:history.back()" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>

        <div class="room-header">
            <h1><?php echo htmlspecialchars($room['name']); ?></h1>
            <p class="mb-0">
                <i class="fas fa-building mr-2"></i>
                <?php echo htmlspecialchars($room['building_name']); ?>
            </p>
        </div>

        <?php if ($room['image_url']): ?>
        <img src="<?php echo htmlspecialchars($room['image_url']); ?>" alt="<?php echo htmlspecialchars($room['name']); ?>" class="room-image">
        <?php endif; ?>

        <div class="info-card">
            <h3>
                <i class="fas fa-info-circle"></i>
                About this Room
            </h3>
            <p><?php echo nl2br(htmlspecialchars($room['description'])); ?></p>
        </div>

        <?php if ($room['name'] !== 'General Service Office'): ?>
        <div class="info-card">
            <h3>
                <i class="fas fa-calendar-alt"></i>
                Class Schedule
            </h3>
            <div class="table-responsive">
                <table class="schedule-table">
                    <thead>
                        <tr>
                            <th>Day</th>
                            <th>Time</th>
                            <th>Subject</th>
                            <th>Section</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $schedules = getRoomSchedule($room_id);
                        foreach ($schedules as $schedule):
                            // Skip lunch break
                            if (strpos($schedule['start_time'], '11:00:00') === false):
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($schedule['day_of_week']); ?></td>
                            <td>
                                <?php 
                                    $start = date('h:i A', strtotime($schedule['start_time']));
                                    $end = date('h:i A', strtotime($schedule['end_time']));
                                    echo htmlspecialchars($start . ' - ' . $end); 
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($schedule['subject']); ?></td>
                            <td><?php echo htmlspecialchars($schedule['section']); ?></td>
                        </tr>
                        <?php 
                            endif;
                        endforeach; 
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php else: ?>
        <div class="info-card">
            <h3>
                <i class="fas fa-clock"></i>
                Operating Hours
            </h3>
            <ul class="schedule-list">
                <li class="schedule-item">
                    <strong>Monday to Friday:</strong> 8:00 AM - 5:00 PM
                </li>
                <li class="schedule-item">
                    <strong>Saturday:</strong> 8:00 AM - 12:00 PM
                </li>
                <li class="schedule-item">
                    <strong>Sunday:</strong> Closed
                </li>
            </ul>
        </div>
        <?php endif; ?>

        <div class="info-card">
            <h3>
                <i class="fas fa-clipboard-list"></i>
                Facilities & Features
            </h3>
            <ul class="facility-list">
                <li class="facility-item">
                    <i class="fas fa-air-conditioner"></i>
                    Air Conditioning
                </li>
                <?php if (strpos($room['name'], 'Office') !== false): ?>
                <li class="facility-item">
                    <i class="fas fa-desk"></i>
                    Staff Work Area
                </li>
                <li class="facility-item">
                    <i class="fas fa-chair"></i>
                    Waiting Area
                </li>
                <li class="facility-item">
                    <i class="fas fa-file-alt"></i>
                    Document Processing
                </li>
                <?php endif; ?>
                <?php if ($room['name'] === 'Room 101'): ?>
                <li class="facility-item">
                    <i class="fas fa-chalkboard"></i>
                    Whiteboard
                </li>
                <li class="facility-item">
                    <i class="fas fa-projector"></i>
                    LCD Projector
                </li>
                <li class="facility-item">
                    <i class="fas fa-wifi"></i>
                    Wi-Fi Access
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html> 