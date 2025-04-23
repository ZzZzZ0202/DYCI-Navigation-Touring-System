<?php
session_start();
require_once 'includes/functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_type'])) {
    header("Location: index.php");
    exit();
}

// Get room number from URL parameter
$room_number = isset($_GET['id']) ? htmlspecialchars($_GET['id']) : '101';

// Initialize schedule array
$schedule = array(
    'Monday' => array(),
    'Tuesday' => array(),
    'Wednesday' => array(),
    'Thursday' => array(),
    'Friday' => array(),
    'Saturday' => array(),
    'Sunday' => array()
);

// Sample schedule data for Room 101
if ($room_number === '101') {
    $schedule = array(
        'Monday' => array(
            array(
                'time' => '7:00-10:00 AM',
                'subject' => 'CAA',
                'section' => '2-PACED'
            )
        ),
        'Tuesday' => array(
            array(
                'time' => '7:00-10:00 AM',
                'subject' => 'SAD',
                'section' => '2-PACED'
            ),
            array(
                'time' => '11:00-12:00 NN',
                'subject' => 'PE-2',
                'section' => '2-PACED'
            ),
            array(
                'time' => '1:00-4:00 PM',
                'subject' => 'DMS-2',
                'section' => '2-PACED'
            ),
            array(
                'time' => '4:30-7:00 PM',
                'subject' => 'EFL',
                'section' => '2-PACED'
            )
        ),
        'Wednesday' => array(
            array(
                'time' => '1:00-4:00 PM',
                'subject' => 'IPT',
                'section' => '2-PACED'
            ),
            array(
                'time' => '4:30-7:00 PM',
                'subject' => 'NAC',
                'section' => '2-PACED'
            )
        ),
        'Friday' => array(
            array(
                'time' => '5:00-7:00 PM',
                'subject' => 'FRE',
                'section' => '2-PACED'
            )
        )
    );
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Details - DYCI Tour System</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #4834d4;
            margin: 0;
            padding: 20px;
            min-height: 100vh;
            font-family: Arial, sans-serif;
        }

        .header {
            display: flex;
            align-items: center;
            padding: 0 20px;
            margin-bottom: 30px;
        }

        .header img {
            height: 40px;
            margin-right: 15px;
        }

        .header h1 {
            color: white;
            margin: 0;
            font-size: 24px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 15px;
        }

        .room-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin: 20px auto;
            max-width: 800px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .room-title {
            color: #000;
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 10px;
        }

        .room-location {
            color: #666;
            text-align: center;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .room-description {
            color: #444;
            margin: 0;
            font-size: 14px;
            padding-left: 20px;
        }

        .room-image {
            width: 100%;
            max-width: 600px;
            height: 300px;
            object-fit: cover;
            border-radius: 10px;
            margin: 20px auto;
            display: block;
        }

        .schedule-container {
            background: #003399;
            border-radius: 0;
            margin: 20px auto;
            max-width: 800px;
            padding: 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        .section-header {
            background: white;
            padding: 8px 15px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #ccc;
        }

        .section-text {
            font-weight: bold;
            margin-right: 10px;
        }

        .dropdown-icon {
            width: 0;
            height: 0;
            border-left: 6px solid transparent;
            border-right: 6px solid transparent;
            border-top: 6px solid black;
            display: inline-block;
        }

        .schedule-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        .schedule-table th {
            background-color: #666;
            color: white;
            padding: 8px;
            text-align: center;
            border: 1px solid #444;
            font-weight: normal;
            font-size: 14px;
        }

        .schedule-table td {
            padding: 8px;
            text-align: center;
            border: 1px solid #ccc;
            background: white;
            font-size: 13px;
            height: 45px;
        }

        .schedule-table td:empty {
            background: white;
        }

        .lunch-cell {
            background: white !important;
        }

        .class-time {
            display: block;
            font-size: 12px;
            color: #666;
            margin-top: 2px;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            padding: 10px 20px;
            background-color: #6c5ce7;
            color: white;
            text-decoration: none;
            border-radius: 50px;
            margin: 20px;
            font-weight: 500;
        }

        .back-btn:hover {
            background-color: #5849c2;
            color: white;
            text-decoration: none;
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 10px;
            }
            
            .room-card, .schedule-container {
                margin: 20px 10px;
            }
            
            .room-image {
                height: 200px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="assets/images/dyci_logo.png" alt="DYCI Logo">
        <h1>Room Details</h1>
    </div>

    <div class="container">
        <div class="room-card">
            <h2 class="room-title">Room <?php echo $room_number; ?></h2>
            <p class="room-location">Building A - Ground Floor</p>
            <img src="assets/images/rooms/room<?php echo $room_number; ?>.jpg" alt="Room <?php echo $room_number; ?>" class="room-image">
            <p class="room-description">General purpose classroom</p>
        </div>

        <div class="schedule-container">
            <div class="section-header">
                <span class="section-text">2-PACED</span>
                <span class="dropdown-icon"></span>
            </div>
            <table class="schedule-table">
                <thead>
                    <tr>
                        <th>Monday</th>
                        <th>Tuesday</th>
                        <th>Wednesday</th>
                        <th>Thursday</th>
                        <th>Friday</th>
                        <th>Saturday</th>
                        <th>Sunday</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            CAA
                            <span class="class-time">7:00-10:00 AM</span>
                        </td>
                        <td>
                            SAD
                            <span class="class-time">7:00-10:00 AM</span>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            PE-2
                            <span class="class-time">11:00-12:00 NN</span>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="lunch-cell">Lunch</td>
                        <td class="lunch-cell">Lunch</td>
                        <td class="lunch-cell">Lunch</td>
                        <td class="lunch-cell">Lunch</td>
                        <td class="lunch-cell">Lunch</td>
                        <td class="lunch-cell">Lunch</td>
                        <td class="lunch-cell">Lunch</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            DMS-2
                            <span class="class-time">1:00-4:00 PM</span>
                        </td>
                        <td>
                            IPT
                            <span class="class-time">1:00-4:00 PM</span>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            EFL
                            <span class="class-time">4:30-7:00 PM</span>
                        </td>
                        <td>
                            NAC
                            <span class="class-time">4:30-7:00 PM</span>
                        </td>
                        <td></td>
                        <td>
                            FRE
                            <span class="class-time">5:00-7:00 PM</span>
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <a href="campus_map.php" class="back-btn">
        ← BACK
    </a>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html> 