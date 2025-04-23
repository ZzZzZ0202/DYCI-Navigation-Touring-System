<?php
require_once 'config/database.php';

try {
    // Create admin table if it doesn't exist
    $pdo->exec("CREATE TABLE IF NOT EXISTS admins (
        id INT PRIMARY KEY AUTO_INCREMENT,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Check if admin account exists
    $stmt = $pdo->prepare("SELECT id FROM admins WHERE username = 'admin'");
    $stmt->execute();
    
    if (!$stmt->fetch()) {
        // Create default admin account
        $password = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO admins (username, password, name, email) VALUES (?, ?, ?, ?)");
        $stmt->execute(['admin', $password, 'System Administrator', 'admin@dyci.edu.ph']);
        echo "Admin account created successfully!<br>";
        echo "Username: admin<br>";
        echo "Password: admin123";
    } else {
        echo "Admin account already exists!<br>";
        echo "Username: admin<br>";
        echo "Password: admin123";
    }

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 