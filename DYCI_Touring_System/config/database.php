<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'dyci_tour');
define('DB_USER', 'root');     // Default XAMPP MySQL username
define('DB_PASS', '');         // Default XAMPP MySQL password has no password

// Create connection function
function getDatabaseConnection() {
    static $conn = null;
    
    if ($conn === null) {
        try {
            $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            
            if ($conn->connect_error) {
                throw new Exception("Connection failed: " . $conn->connect_error);
            }
            
            // Set charset to utf8
            $conn->set_charset("utf8");
            
        } catch (Exception $e) {
            error_log("Database connection error: " . $e->getMessage());
            die("Database connection failed. Please try again later.");
        }
    }
    
    return $conn;
}

// Create database and tables if they don't exist
function initializeDatabase() {
    try {
        // First create a connection without selecting a database
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS);
        
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }
        
        // Create database if it doesn't exist
        $sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
        if (!$conn->query($sql)) {
            throw new Exception("Error creating database: " . $conn->error);
        }
        
        // Select the database
        $conn->select_db(DB_NAME);
        
        // Create students table
        $sql = "CREATE TABLE IF NOT EXISTS students (
            id INT AUTO_INCREMENT PRIMARY KEY,
            student_id VARCHAR(20) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            first_name VARCHAR(50) NOT NULL,
            middle_name VARCHAR(50),
            last_name VARCHAR(50) NOT NULL,
            email VARCHAR(100) UNIQUE,
            course_id INT,
            year_level INT,
            section VARCHAR(20),
            street_address VARCHAR(100),
            barangay VARCHAR(50),
            city VARCHAR(50),
            province VARCHAR(50),
            postal_code VARCHAR(10),
            birthday DATE,
            gender VARCHAR(10),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        if (!$conn->query($sql)) {
            throw new Exception("Error creating students table: " . $conn->error);
        }

        // Create login_attempts table
        $sql = "CREATE TABLE IF NOT EXISTS login_attempts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            student_id VARCHAR(20),
            success BOOLEAN,
            attempt_time DATETIME,
            ip_address VARCHAR(45),
            FOREIGN KEY (student_id) REFERENCES students(student_id)
        )";
        if (!$conn->query($sql)) {
            throw new Exception("Error creating login_attempts table: " . $conn->error);
        }

        // Create remember_tokens table
        $sql = "CREATE TABLE IF NOT EXISTS remember_tokens (
            id INT AUTO_INCREMENT PRIMARY KEY,
            student_id INT,
            token VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (student_id) REFERENCES students(id)
        )";
        if (!$conn->query($sql)) {
            throw new Exception("Error creating remember_tokens table: " . $conn->error);
        }

        // Create test student account if it doesn't exist
        $test_student_id = '2023-00001';
        $test_password = password_hash('dyci2023', PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare("SELECT id FROM students WHERE student_id = ?");
        $stmt->bind_param("s", $test_student_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            $sql = "INSERT INTO students (student_id, password, first_name, last_name, email) 
                    VALUES (?, ?, 'Test', 'Student', 'test@dyci.edu.ph')";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $test_student_id, $test_password);
            $stmt->execute();
        }
        
        $conn->close();
        return true;
        
    } catch (Exception $e) {
        error_log("Database initialization error: " . $e->getMessage());
        return false;
    }
}

// Initialize the database and tables
initializeDatabase();

// First, try connecting without database to create it if needed
try {
    $pdo = new PDO("mysql:host=" . DB_HOST, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    
    // Select the database
    $pdo->exec("USE `" . DB_NAME . "`");
    
    // Set additional options
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    // Create necessary tables if they don't exist
    
    // Courses table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `courses` (
        `id` INT PRIMARY KEY AUTO_INCREMENT,
        `code` VARCHAR(10) NOT NULL UNIQUE,
        `name` VARCHAR(100) NOT NULL,
        `specialization` VARCHAR(100),
        `units_required` INT NOT NULL DEFAULT 0,
        `years_to_complete` INT NOT NULL DEFAULT 4,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Students table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `students` (
        `id` INT PRIMARY KEY AUTO_INCREMENT,
        `student_id` VARCHAR(20) NOT NULL UNIQUE,
        `password` VARCHAR(255) NOT NULL,
        `first_name` VARCHAR(50) NOT NULL,
        `middle_name` VARCHAR(50),
        `last_name` VARCHAR(50) NOT NULL,
        `email` VARCHAR(100) NOT NULL UNIQUE,
        `course_id` INT,
        `year_level` INT NOT NULL DEFAULT 1,
        `section` VARCHAR(10),
        `gender` ENUM('Male', 'Female', 'Other'),
        `birthday` DATE,
        `religion` VARCHAR(50),
        `street_address` VARCHAR(100),
        `barangay` VARCHAR(100),
        `city` VARCHAR(100),
        `province` VARCHAR(100),
        `postal_code` VARCHAR(10),
        `emergency_contact_name` VARCHAR(100),
        `emergency_contact_number` VARCHAR(20),
        `last_login` TIMESTAMP NULL,
        `remember_token` VARCHAR(100),
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (`course_id`) REFERENCES `courses`(`id`)
    )");

    // Admins table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `admins` (
        `id` INT PRIMARY KEY AUTO_INCREMENT,
        `username` VARCHAR(50) NOT NULL UNIQUE,
        `password` VARCHAR(255) NOT NULL,
        `name` VARCHAR(100) NOT NULL,
        `email` VARCHAR(100) NOT NULL UNIQUE,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Buildings table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `buildings` (
        `id` INT PRIMARY KEY AUTO_INCREMENT,
        `name` VARCHAR(100) NOT NULL,
        `floor` INT NOT NULL DEFAULT 1,
        `description` TEXT,
        `image_url` VARCHAR(255),
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Rooms table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `rooms` (
        `id` INT PRIMARY KEY AUTO_INCREMENT,
        `building_id` INT NOT NULL,
        `name` VARCHAR(100) NOT NULL,
        `description` TEXT,
        `capacity` INT,
        `image_url` VARCHAR(255),
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (`building_id`) REFERENCES `buildings`(`id`)
    )");

    // Visit logs table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `visit_logs` (
        `id` INT PRIMARY KEY AUTO_INCREMENT,
        `user_type` ENUM('student', 'visitor') NOT NULL,
        `student_id` VARCHAR(20),
        `room_id` INT NOT NULL,
        `visited_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (`room_id`) REFERENCES `rooms`(`id`)
    )");

    // Insert default admin if not exists
    $stmt = $pdo->prepare("SELECT id FROM admins WHERE username = 'admin' LIMIT 1");
    $stmt->execute();
    if (!$stmt->fetch()) {
        $default_password = password_hash('admin123', PASSWORD_DEFAULT);
        $pdo->exec("INSERT INTO admins (username, password, name, email) 
                   VALUES ('admin', '$default_password', 'System Administrator', 'admin@dyci.edu.ph')");
    }

    // Insert default courses if not exists
    $default_courses = [
        ['BSIT', 'Bachelor of Science in Information Technology', 'General', 155, 4],
        ['BSCS', 'Bachelor of Science in Computer Science', 'General', 155, 4],
        ['BSCpE', 'Bachelor of Science in Computer Engineering', 'General', 155, 4]
    ];

    $stmt = $pdo->prepare("INSERT IGNORE INTO courses (code, name, specialization, units_required, years_to_complete) 
                          VALUES (?, ?, ?, ?, ?)");
    
    foreach ($default_courses as $course) {
        $stmt->execute($course);
    }
    
} catch(PDOException $e) {
    error_log("Connection failed: " . $e->getMessage());
    echo "Error: " . $e->getMessage();  // Display error during development
    die();
}
?> 