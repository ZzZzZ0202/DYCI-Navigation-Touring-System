<?php
require_once 'config/database.php';

try {
    // Create a test student with known credentials
    $student_id = '2023-00001';
    $password = 'dyci2023';
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // First, check if student exists
    $stmt = $pdo->prepare("SELECT * FROM students WHERE student_id = ?");
    $stmt->execute([$student_id]);
    $student = $stmt->fetch();
    
    if ($student) {
        echo "Student exists, updating password...\n";
        // Update the password
        $stmt = $pdo->prepare("UPDATE students SET password = ? WHERE student_id = ?");
        $stmt->execute([$hashed_password, $student_id]);
    } else {
        echo "Student doesn't exist, creating new student...\n";
        // Insert the student
        $stmt = $pdo->prepare("
            INSERT INTO students (
                student_id, 
                password,
                first_name,
                middle_name,
                last_name,
                email,
                course_id,
                year_level,
                section
            ) VALUES (?, ?, 'Juan', 'Santos', 'Dela Cruz', ?, 1, 1, 'BSIT1A')
        ");
        $stmt->execute([$student_id, $hashed_password, $student_id . '@dyci.edu.ph']);
    }
    
    echo "Student account ready!\n";
    echo "Student ID: $student_id\n";
    echo "Password: $password\n";
    
    // Verify the credentials
    $stmt = $pdo->prepare("SELECT * FROM students WHERE student_id = ?");
    $stmt->execute([$student_id]);
    $student = $stmt->fetch();
    
    if ($student && password_verify($password, $student['password'])) {
        echo "Password verification successful!\n";
    } else {
        echo "Password verification failed!\n";
    }
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?> 