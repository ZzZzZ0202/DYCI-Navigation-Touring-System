<?php
session_start();
require_once 'includes/functions.php';

// Check if user is logged in as student
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'student') {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];
$student = getStudentDetails($student_id);
$organizations = getStudentOrganizations($student_id);
$visit_history = getStudentVisitHistory($student_id);

if (!$student) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile - DYCI Tour System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .profile-container {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .section {
            margin-bottom: 2rem;
            padding: 1rem;
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }
        .section h3 {
            color: #003366;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }
        .section h3 i {
            margin-right: 0.5rem;
        }
        .org-badge {
            background-color: #003366;
            color: white;
            padding: 0.5rem 1rem;
            margin: 0.25rem;
            border-radius: 20px;
            display: inline-block;
            transition: transform 0.2s;
        }
        .org-badge:hover {
            transform: scale(1.05);
        }
        .map-container {
            height: 400px;
            width: 100%;
            margin-top: 1rem;
            border-radius: 5px;
            overflow: hidden;
        }
        .btn-navy {
            background-color: #003366;
            color: white;
            transition: all 0.3s;
        }
        .btn-navy:hover {
            background-color: #002244;
            color: white;
            transform: translateY(-2px);
        }
        .profile-header {
            position: relative;
            padding-bottom: 2rem;
            margin-bottom: 2rem;
            border-bottom: 2px solid #dee2e6;
        }
        .profile-avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid #003366;
            margin-bottom: 1rem;
        }
        .visit-history {
            max-height: 300px;
            overflow-y: auto;
        }
        .visit-item {
            padding: 0.5rem;
            border-bottom: 1px solid #dee2e6;
            transition: background-color 0.2s;
        }
        .visit-item:hover {
            background-color: #f8f9fa;
        }
        .visit-time {
            color: #6c757d;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container profile-container">
        <div class="profile-header text-center">
            <img src="assets/images/default-avatar.png" alt="Student Avatar" class="profile-avatar">
            <h2 class="mt-3"><?php echo htmlspecialchars($student['name']); ?></h2>
            <p class="text-muted"><?php echo htmlspecialchars($student['student_id']); ?></p>
        </div>

        <div class="section">
            <h3><i class="fas fa-user"></i> Personal Information</h3>
            <div class="row">
                <div class="col-md-6">
                    <p><i class="fas fa-id-card"></i> <strong>Student ID:</strong> <?php echo htmlspecialchars($student['student_id']); ?></p>
                    <p><i class="fas fa-user-graduate"></i> <strong>Course:</strong> <?php echo htmlspecialchars($student['course_name']); ?></p>
                    <p><i class="fas fa-book"></i> <strong>Specialization:</strong> <?php echo htmlspecialchars($student['specialization']); ?></p>
                    <p><i class="fas fa-calendar-alt"></i> <strong>Year Level:</strong> <?php echo htmlspecialchars($student['year_level']); ?></p>
                </div>
                <div class="col-md-6">
                    <p><i class="fas fa-map-marker-alt"></i> <strong>Address:</strong> <?php echo htmlspecialchars($student['address']); ?></p>
                    <p><i class="fas fa-phone"></i> <strong>Contact:</strong> <?php echo htmlspecialchars($student['contact_number']); ?></p>
                    <p><i class="fas fa-envelope"></i> <strong>Email:</strong> <?php echo htmlspecialchars($student['email']); ?></p>
                    <p><i class="fas fa-user-check"></i> <strong>Status:</strong> <span class="badge badge-success">Active</span></p>
                </div>
            </div>
        </div>

        <?php if (!empty($organizations)): ?>
        <div class="section">
            <h3><i class="fas fa-users"></i> Organizations</h3>
            <div class="row">
                <div class="col">
                    <?php foreach ($organizations as $org): ?>
                        <div class="org-badge">
                            <i class="fas fa-star"></i>
                            <?php echo htmlspecialchars($org['name']); ?> - 
                            <?php echo htmlspecialchars($org['position']); ?> 
                            (<?php echo htmlspecialchars($org['school_year']); ?>)
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($visit_history)): ?>
        <div class="section">
            <h3><i class="fas fa-history"></i> Recent Visit History</h3>
            <div class="visit-history">
                <?php foreach ($visit_history as $visit): ?>
                    <div class="visit-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-door-open"></i>
                                <strong><?php echo htmlspecialchars($visit['room_name']); ?></strong>
                                - <?php echo htmlspecialchars($visit['building_name']); ?> (Floor <?php echo htmlspecialchars($visit['floor']); ?>)
                            </div>
                            <span class="visit-time">
                                <i class="far fa-clock"></i>
                                <?php echo date('M d, Y h:i A', strtotime($visit['visit_time'])); ?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="section">
            <h3><i class="fas fa-map-marked-alt"></i> Location</h3>
            <div class="map-container">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3861.802746371565!2d121.01754731483991!3d14.554421989828173!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397c90795555555%3A0x4e23f6808e29c148!2sDr.%20Yanga&#39;s%20Colleges%2C%20Inc.!5e0!3m2!1sen!2sph!4v1647827147811!5w200!5h200"
                    width="100%"
                    height="100%"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy">
                </iframe>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="tour.php" class="btn btn-navy mr-2">
                <i class="fas fa-walking"></i> Start Tour
            </a>
            <a href="dashboard.php" class="btn btn-secondary">
                <i class="fas fa-tachometer-alt"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html> 