<?php
session_start();
require_once 'includes/functions.php';

// Check if user is logged in as student
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'student') {
    header("Location: login.php");
    exit();
}

// Get student details
$student = getStudentDetails($_SESSION['student_id']);
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
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background: #f5f5f5;
            font-family: Arial, sans-serif;
        }

        .profile-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            position: relative;
        }

        .profile-header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #000080;
        }

        .profile-header h2 {
            color: #000080;
            font-weight: bold;
        }

        .profile-section {
            margin-bottom: 2rem;
        }

        .profile-section h3 {
            color: #000080;
            font-size: 1.2rem;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e0e0e0;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .info-item {
            margin-bottom: 1rem;
        }

        .info-label {
            font-weight: bold;
            color: #666;
            margin-bottom: 0.25rem;
        }

        .info-value {
            color: #333;
        }

        .back-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            background: #FFD700;
            color: #000080;
            padding: 8px 20px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .back-btn:hover {
            background: #FFC700;
            transform: translateY(-2px);
            text-decoration: none;
            color: #000080;
        }

        .logo {
            width: 120px;
            height: auto;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <a href="dashboard.php" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Back to Dashboard
        </a>

        <div class="profile-header">
            <img src="assets/images/dyci-logo.png" alt="DYCI Logo" class="logo">
            <h2>Student Profile</h2>
        </div>

        <div class="profile-section">
            <h3>Personal Information</h3>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Student ID</div>
                    <div class="info-value"><?php echo htmlspecialchars($student['student_id']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Full Name</div>
                    <div class="info-value">
                        <?php 
                        echo htmlspecialchars($student['first_name']) . ' ' . 
                             ($student['middle_name'] ? htmlspecialchars($student['middle_name']) . ' ' : '') . 
                             htmlspecialchars($student['last_name']); 
                        ?>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Email</div>
                    <div class="info-value"><?php echo htmlspecialchars($student['email']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Gender</div>
                    <div class="info-value"><?php echo htmlspecialchars($student['gender'] ?? 'Not specified'); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Birthday</div>
                    <div class="info-value"><?php echo $student['birthday'] ? date('F j, Y', strtotime($student['birthday'])) : 'Not specified'; ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Religion</div>
                    <div class="info-value"><?php echo htmlspecialchars($student['religion'] ?? 'Not specified'); ?></div>
                </div>
            </div>
        </div>

        <div class="profile-section">
            <h3>Academic Information</h3>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Course</div>
                    <div class="info-value"><?php echo htmlspecialchars(getCourseName($student['course_id'])); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Year Level</div>
                    <div class="info-value"><?php echo htmlspecialchars($student['year_level']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Section</div>
                    <div class="info-value"><?php echo htmlspecialchars($student['section'] ?? 'Not specified'); ?></div>
                </div>
            </div>
        </div>

        <div class="profile-section">
            <h3>Contact Information</h3>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Address</div>
                    <div class="info-value">
                        <?php
                        $address_parts = array_filter([
                            $student['street_address'] ?? '',
                            $student['barangay'] ?? '',
                            $student['city'] ?? '',
                            $student['province'] ?? '',
                            $student['postal_code'] ?? ''
                        ]);
                        echo $address_parts ? htmlspecialchars(implode(', ', $address_parts)) : 'Not specified';
                        ?>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Emergency Contact</div>
                    <div class="info-value">
                        <?php 
                        if ($student['emergency_contact_name'] || $student['emergency_contact_number']) {
                            echo htmlspecialchars($student['emergency_contact_name'] ?? 'Not specified') . ' - ' . 
                                 htmlspecialchars($student['emergency_contact_number'] ?? 'No number');
                        } else {
                            echo 'Not specified';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 