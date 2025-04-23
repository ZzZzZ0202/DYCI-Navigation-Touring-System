<?php
session_start();
require_once '../includes/functions.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentId = $_POST['student_id'] ?? '';
    $name = $_POST['name'] ?? '';
    $idNumber = $_POST['id_number'] ?? '';
    $course = $_POST['course'] ?? '';
    $yearLevel = $_POST['year_level'] ?? '';
    $status = $_POST['status'] ?? '';
    
    // Update student details in database
    // You'll need to implement this in your database
}

// Get all students (you'll need to implement this in your database)
$students = [
    1 => [
        'name' => 'John Doe',
        'id_number' => '2023-0001',
        'course' => 'BSIT',
        'year_level' => '3',
        'status' => 'active'
    ],
    // Add other students here
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Management - DYCI Tour</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }

        .header {
            background: #000080;
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .back-btn {
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            border: 1px solid white;
            border-radius: 4px;
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
        }

        .student-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .student-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .student-form {
            display: grid;
            gap: 15px;
        }

        .form-group {
            display: grid;
            gap: 5px;
        }

        .form-group label {
            color: #333;
            font-weight: bold;
        }

        .form-group input,
        .form-group select {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-active {
            background: #28a745;
            color: white;
        }

        .status-inactive {
            background: #dc3545;
            color: white;
        }

        .save-btn {
            background: #000080;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.2s;
        }

        .save-btn:hover {
            background: #000066;
        }

        .add-student-btn {
            background: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-bottom: 20px;
            transition: background-color 0.2s;
        }

        .add-student-btn:hover {
            background: #218838;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Student Management</h1>
        <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
    </div>

    <div class="container">
        <button class="add-student-btn" onclick="location.href='add_student.php'">Add New Student</button>

        <div class="student-grid">
            <?php foreach ($students as $id => $student): ?>
            <div class="student-card">
                <form method="POST" action="" class="student-form">
                    <input type="hidden" name="student_id" value="<?php echo $id; ?>">
                    
                    <div class="form-group">
                        <label for="name_<?php echo $id; ?>">Full Name</label>
                        <input type="text" id="name_<?php echo $id; ?>" name="name" 
                               value="<?php echo htmlspecialchars($student['name']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="id_number_<?php echo $id; ?>">ID Number</label>
                        <input type="text" id="id_number_<?php echo $id; ?>" name="id_number" 
                               value="<?php echo htmlspecialchars($student['id_number']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="course_<?php echo $id; ?>">Course</label>
                        <input type="text" id="course_<?php echo $id; ?>" name="course" 
                               value="<?php echo htmlspecialchars($student['course']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="year_level_<?php echo $id; ?>">Year Level</label>
                        <select id="year_level_<?php echo $id; ?>" name="year_level" required>
                            <option value="1" <?php echo $student['year_level'] === '1' ? 'selected' : ''; ?>>1st Year</option>
                            <option value="2" <?php echo $student['year_level'] === '2' ? 'selected' : ''; ?>>2nd Year</option>
                            <option value="3" <?php echo $student['year_level'] === '3' ? 'selected' : ''; ?>>3rd Year</option>
                            <option value="4" <?php echo $student['year_level'] === '4' ? 'selected' : ''; ?>>4th Year</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="status_<?php echo $id; ?>">Status</label>
                        <select id="status_<?php echo $id; ?>" name="status" required>
                            <option value="active" <?php echo $student['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                            <option value="inactive" <?php echo $student['status'] === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                    </div>

                    <button type="submit" class="save-btn">Save Changes</button>
                </form>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html> 