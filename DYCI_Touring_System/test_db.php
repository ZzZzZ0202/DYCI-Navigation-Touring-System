<?php
require_once 'config/database.php';

try {
    // Test database connection
    echo "Testing database connection...\n";
    $pdo->query("SELECT 1");
    echo "Database connection successful!\n\n";

    // Check if tables exist
    $tables = ['students', 'courses', 'buildings', 'rooms', 'organizations', 'student_organizations', 'visit_logs', 'admins', 'remember_me_tokens'];
    
    foreach ($tables as $table) {
        $result = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($result->rowCount() > 0) {
            echo "Table '$table' exists\n";
            
            // Show table structure
            echo "Structure of '$table':\n";
            $structure = $pdo->query("DESCRIBE $table");
            while ($row = $structure->fetch()) {
                echo "  - {$row['Field']} ({$row['Type']})\n";
            }
            echo "\n";
        } else {
            echo "Table '$table' does NOT exist\n\n";
        }
    }

    // Check if test student exists
    $stmt = $pdo->prepare("SELECT * FROM students WHERE student_id = ?");
    $stmt->execute(['2023-00001']);
    $student = $stmt->fetch();
    
    if ($student) {
        echo "Test student found:\n";
        print_r($student);
    } else {
        echo "Test student NOT found\n";
    }

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?> 