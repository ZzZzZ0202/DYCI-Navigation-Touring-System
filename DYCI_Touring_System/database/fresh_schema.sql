DROP DATABASE IF EXISTS dyci_tour;
CREATE DATABASE dyci_tour;
USE dyci_tour;

-- Create courses table
CREATE TABLE IF NOT EXISTS courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(10) NOT NULL,
    name VARCHAR(100) NOT NULL,
    specialization VARCHAR(100),
    units_required INT NOT NULL,
    years_to_complete INT NOT NULL
);

-- Create students table with all required fields
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(20) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    middle_name VARCHAR(50),
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    course_id INT NOT NULL,
    year_level INT NOT NULL,
    section VARCHAR(10) NOT NULL,
    gender VARCHAR(20),
    birthday DATE,
    religion VARCHAR(50),
    street_address VARCHAR(100),
    barangay VARCHAR(100),
    city VARCHAR(100),
    province VARCHAR(100),
    postal_code VARCHAR(20),
    emergency_contact_name VARCHAR(100),
    emergency_contact_number VARCHAR(20),
    FOREIGN KEY (course_id) REFERENCES courses(id)
);

-- Create buildings table
CREATE TABLE IF NOT EXISTS buildings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    floor VARCHAR(50) NOT NULL,
    description TEXT,
    image_url VARCHAR(255)
);

-- Create rooms table
CREATE TABLE IF NOT EXISTS rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    building_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    image_url VARCHAR(255),
    FOREIGN KEY (building_id) REFERENCES buildings(id)
);

-- Create organizations table
CREATE TABLE IF NOT EXISTS organizations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    acronym VARCHAR(20),
    type VARCHAR(50) NOT NULL,
    description TEXT
);

-- Create student_organizations table
CREATE TABLE IF NOT EXISTS student_organizations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    organization_id INT NOT NULL,
    position VARCHAR(100),
    school_year VARCHAR(20) NOT NULL,
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (organization_id) REFERENCES organizations(id)
);

-- Create visit_logs table
CREATE TABLE IF NOT EXISTS visit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_type ENUM('student', 'admin') NOT NULL,
    student_id INT,
    room_id INT NOT NULL,
    visit_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (room_id) REFERENCES rooms(id)
);

-- Create admins table
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL
);

-- Create remember_me_tokens table
CREATE TABLE IF NOT EXISTS remember_me_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    token VARCHAR(255) NOT NULL,
    expiry TIMESTAMP NOT NULL,
    FOREIGN KEY (student_id) REFERENCES students(id)
);

-- Insert courses
INSERT INTO courses (code, name, specialization, units_required, years_to_complete) VALUES
('BSIT', 'BS Information Technology', 'Web and Mobile Development', 155, 4);

-- Insert a test student with complete information
INSERT INTO students (
    student_id, 
    password,
    first_name,
    middle_name,
    last_name,
    email,
    course_id,
    year_level,
    section,
    gender,
    birthday,
    religion,
    street_address,
    barangay,
    city,
    province,
    postal_code,
    emergency_contact_name,
    emergency_contact_number
) VALUES (
    '2023-00001',
    '$2y$10$dC30WXmxhYf8YHIvGiSYPOdYzHh9Q7hHmUK0VDHVVCq1DOb.UQKB.',
    'Juan',
    'Santos',
    'Dela Cruz',
    '2023-00001@dyci.edu.ph',
    1,
    1,
    'BSIT1A',
    'Male',
    '2003-01-01',
    'Catholic',
    '123 Main Street',
    'San Vicente',
    'Dagupan City',
    'Pangasinan',
    '2400',
    'Maria Dela Cruz',
    '09123456789'
);

-- Insert Building A
INSERT INTO buildings (name, floor, description) VALUES
('Building A', 'Ground Floor', 'Main administrative building');

-- Insert rooms for Building A
INSERT INTO rooms (building_id, name, description) VALUES
(1, 'College Registrar', 'Handles student records and registration'),
(1, 'Finance Office', 'Manages financial transactions and student accounts'),
(1, 'Supreme Student Council Office', 'Student government office'),
(1, 'Paraya/NSTP', 'National Service Training Program office'),
(1, 'Cashier', 'Handles payments and financial transactions'),
(1, 'Office of Student Affairs', 'Manages student services and activities'),
(1, 'Room 101', 'General purpose classroom'),
(1, 'Human Resource Management and Development Office', 'HR department'),
(1, 'College of Finance and Accounting Office', 'CFA department office'),
(1, 'Graduate School Office', 'Graduate studies administration');

-- Insert organizations
INSERT INTO organizations (name, acronym, type, description) VALUES
('Supreme Student Council', 'SSC', 'Student Government', 'The highest governing student body'),
('Information Technology Society', 'ITS', 'Academic', 'IT student organization'),
('Junior Philippine Institute of Accountants', 'JPIA', 'Academic', 'Accounting student organization'),
('Young Entrepreneurs Society', 'YES', 'Academic', 'Business student organization'),
('Rotaract Club', 'RC', 'Service', 'Community service organization');

-- Insert a default admin (Username: admin, Password: admin123)
INSERT INTO admins (username, password, name, email) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin@dyci.edu.ph'); 