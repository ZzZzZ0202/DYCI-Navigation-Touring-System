-- Create the database
CREATE DATABASE IF NOT EXISTS dyci_tour;
USE dyci_tour;

-- Create admins table
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create courses table
CREATE TABLE IF NOT EXISTS courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(10) NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    specialization VARCHAR(100),
    units_required INT NOT NULL,
    years_to_complete INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create subjects table
CREATE TABLE IF NOT EXISTS subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(20) NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    units INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create students table first (before student_organizations)
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(20) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    middle_name VARCHAR(50),
    last_name VARCHAR(50) NOT NULL,
    suffix VARCHAR(10),
    email VARCHAR(100) UNIQUE NOT NULL,
    contact_number VARCHAR(15),
    emergency_contact_name VARCHAR(100),
    emergency_contact_number VARCHAR(15),
    emergency_contact_relationship VARCHAR(50),
    birthday DATE NOT NULL,
    age INT GENERATED ALWAYS AS (TIMESTAMPDIFF(YEAR, birthday, CURRENT_DATE)),
    gender ENUM('Male', 'Female') NOT NULL,
    civil_status ENUM('Single', 'Married', 'Widowed', 'Separated') DEFAULT 'Single',
    religion VARCHAR(50),
    street_address VARCHAR(200) NOT NULL,
    barangay VARCHAR(100) NOT NULL,
    city VARCHAR(100) NOT NULL,
    province VARCHAR(100) NOT NULL,
    postal_code VARCHAR(10) NOT NULL,
    course_id INT NOT NULL,
    year_level INT NOT NULL,
    section VARCHAR(10) NOT NULL,
    enrollment_status ENUM('Regular', 'Irregular', 'Transferee') DEFAULT 'Regular',
    scholarship_type VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id)
);

-- Create course_subjects table
CREATE TABLE IF NOT EXISTS course_subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    subject_id INT NOT NULL,
    year_level INT NOT NULL,
    semester INT NOT NULL,
    is_prerequisite BOOLEAN DEFAULT FALSE,
    prerequisite_of INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id),
    FOREIGN KEY (subject_id) REFERENCES subjects(id),
    FOREIGN KEY (prerequisite_of) REFERENCES subjects(id)
);

-- Create organizations table
CREATE TABLE IF NOT EXISTS organizations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    acronym VARCHAR(20) NOT NULL,
    description TEXT,
    type ENUM('Academic', 'Non-Academic', 'Cultural', 'Sports', 'Religious', 'Service') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create student_organizations table
CREATE TABLE IF NOT EXISTS student_organizations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    organization_id INT NOT NULL,
    position VARCHAR(50),
    school_year VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (organization_id) REFERENCES organizations(id)
);

-- Create buildings table
CREATE TABLE IF NOT EXISTS buildings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    floor VARCHAR(50) NOT NULL,
    description TEXT,
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create rooms table
CREATE TABLE IF NOT EXISTS rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    building_id INT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (building_id) REFERENCES buildings(id)
);

-- Create visit_logs table
CREATE TABLE IF NOT EXISTS visit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_type ENUM('student', 'guest') NOT NULL,
    student_id INT,
    room_id INT,
    visit_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (room_id) REFERENCES rooms(id)
);

-- Create remember me tokens table (moved after students table)
CREATE TABLE IF NOT EXISTS remember_me_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(20) NOT NULL,
    token VARCHAR(64) NOT NULL,
    expiry DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(student_id),
    UNIQUE KEY unique_token (token),
    INDEX idx_expiry (expiry)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert organizations
INSERT INTO organizations (name, acronym, description, type) VALUES
('DYCI Student Council', 'DSC', 'The supreme student government organization', 'Non-Academic'),
('Information Technology Society', 'ITS', 'Organization for IT students', 'Academic'),
('Computer Science Society', 'CSS', 'Organization for CS students', 'Academic'),
('Junior Philippine Institute of Accountants', 'JPIA', 'Organization for Accountancy students', 'Academic'),
('Tourism Management Society', 'TMS', 'Organization for Tourism students', 'Academic'),
('Psychology Society', 'PsychSoc', 'Organization for Psychology students', 'Academic'),
('Future Educators Society', 'FES', 'Organization for Education students', 'Academic'),
('DYCI Choir', 'DC', 'Official choir group', 'Cultural'),
('DYCI Dance Troupe', 'DDT', 'Official dance group', 'Cultural'),
('DYCI Sports Club', 'DSC', 'Sports organization', 'Sports'),
('Campus Ministry', 'CM', 'Religious organization', 'Religious'),
('Red Cross Youth Council', 'RCYC', 'Humanitarian service organization', 'Service');

-- Insert courses with complete details
INSERT INTO courses (code, name, description, specialization, units_required, years_to_complete) VALUES
('BSIT', 'BS Information Technology', 'Bachelor of Science in Information Technology', 'Web and Mobile Development', 155, 4),
('BSIT', 'BS Information Technology', 'Bachelor of Science in Information Technology', 'Network and Security', 155, 4),
('BSCS', 'BS Computer Science', 'Bachelor of Science in Computer Science', 'Artificial Intelligence', 160, 4),
('BSCS', 'BS Computer Science', 'Bachelor of Science in Computer Science', 'Data Science', 160, 4),
('BSBA', 'BS Business Administration', 'Bachelor of Science in Business Administration', 'Marketing Management', 150, 4),
('BSBA', 'BS Business Administration', 'Bachelor of Science in Business Administration', 'Financial Management', 150, 4),
('BSA', 'BS Accountancy', 'Bachelor of Science in Accountancy', 'Public Accounting', 165, 4),
('BSA', 'BS Accountancy', 'Bachelor of Science in Accountancy', 'Management Accounting', 165, 4),
('BSTM', 'BS Tourism Management', 'Bachelor of Science in Tourism Management', 'Hotel and Restaurant Management', 155, 4),
('BSTM', 'BS Tourism Management', 'Bachelor of Science in Tourism Management', 'Travel and Tours Management', 155, 4),
('BSPSY', 'BS Psychology', 'Bachelor of Science in Psychology', 'Clinical Psychology', 150, 4),
('BSPSY', 'BS Psychology', 'Bachelor of Science in Psychology', 'Industrial Psychology', 150, 4),
('BSED', 'BS Education', 'Bachelor of Science in Education', 'Mathematics', 155, 4),
('BSED', 'BS Education', 'Bachelor of Science in Education', 'English', 155, 4);

-- Insert default admin user (username: admin, password: admin123)
INSERT INTO admins (username, password) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Insert buildings and rooms
INSERT INTO buildings (name, floor, description) VALUES
('Building A', 'First Floor', 'Main administrative building housing key offices and services'),
('Building B', 'First and Second Floor', 'Academic building with classrooms and service offices');

-- Insert rooms
INSERT INTO rooms (building_id, name, description) VALUES
(1, 'College Registrar', 'Office handling student registration, records, and academic documentation'),
(1, 'Finance Office', 'Handles financial transactions and student payments'),
(1, 'Supreme Student Council Office', 'Student government office for student representation and activities'),
(1, 'Paraya/NSTP', 'National Service Training Program office'),
(1, 'Cashier', 'Processes payments and financial transactions'),
(1, 'Office of Student Affairs', 'Manages student services and activities'),
(1, 'Room 101', 'General purpose classroom'),
(1, 'Human Resource Management and Development Office', 'Handles personnel and staff development'),
(1, 'College of Finance and Accounting Office', 'Academic department office for finance and accounting programs'),
(1, 'Graduate School Office', 'Administrative office for graduate programs');

INSERT INTO rooms (building_id, name, description) VALUES
(2, 'Room 102', 'General purpose classroom'),
(2, 'Room 103', 'General purpose classroom'),
(2, 'Room 104', 'General purpose classroom'),
(2, 'Room 105', 'General purpose classroom'),
(2, 'Room 201', 'General purpose classroom'),
(2, 'Room 202', 'General purpose classroom'),
(2, 'Room 203', 'General purpose classroom'),
(2, 'Room 204', 'General purpose classroom'),
(2, 'Room 205', 'General purpose classroom'),
(2, 'General Service Office', 'Administrative office for general services'),
(2, 'Elida Campus Court', 'Campus courtyard and gathering space');

-- Create stored procedure for student generation
DELIMITER //
CREATE PROCEDURE InsertStudentAccounts()
BEGIN
    DECLARE i INT DEFAULT 1;
    DECLARE student_id VARCHAR(20);
    DECLARE hashed_password VARCHAR(255);
    DECLARE course_id INT;
    DECLARE year INT;
    DECLARE section VARCHAR(10);
    DECLARE gender_val ENUM('Male', 'Female');
    DECLARE birth_date DATE;
    
    -- Common Filipino first names
    DECLARE first_names TEXT DEFAULT 'Juan,Maria,Jose,Ana,Pedro,Rosa,Miguel,Clara,Antonio,Elena,Francisco,Sofia,Manuel,Isabella,Ricardo,Victoria,Eduardo,Carmela,Gabriel,Teresa';
    
    -- Common Filipino middle names (surnames used as middle names)
    DECLARE middle_names TEXT DEFAULT 'Santos,Cruz,Reyes,Garcia,Torres,Ramos,Flores,Gonzales,Martinez,Rodriguez,Aquino,Rivera,Mendoza,Castillo,Villanueva';
    
    -- Common Filipino last names
    DECLARE last_names TEXT DEFAULT 'Dela Cruz,Santos,Garcia,Reyes,Rodriguez,Ramos,Torres,Rivera,Morales,Castillo,Flores,Gonzales,Mendoza,Aquino,Villanueva';
    
    -- Pangasinan street names
    DECLARE streets TEXT DEFAULT 'Rizal,Bonifacio,Mabini,Luna,Aguinaldo,Quezon,Laurel,Quirino,Magsaysay,Roxas,Perez,AB Fernandez,Arellano,Burgos';
    
    -- Dagupan barangays
    DECLARE barangays TEXT DEFAULT 'Bacayao Norte,Bacayao Sur,Bolosan,Bonuan Binloc,Bonuan Boquig,Bonuan Gueset,Calmay,Carael,Caranglaan,Herrero,Lasip Chico,Lasip Grande,Lomboy,Lucao,Malued,Mamalingling,Mangin,Mayombo,Pantal,Poblacion Oeste';
    
    -- Religions common in Pangasinan
    DECLARE religions TEXT DEFAULT 'Roman Catholic,Iglesia Ni Cristo,Methodist,Baptist,Seventh Day Adventist,Born Again Christian';
    
    -- Scholarship types
    DECLARE scholarships TEXT DEFAULT 'Academic Scholar,Athletic Scholar,Cultural Scholar,Government Scholar,Private Scholar';
    
    -- Hash the password once (dyci2023)
    SET hashed_password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
    
    WHILE i <= 10000 DO
        -- Generate student data
        SET student_id = CONCAT('2023-', LPAD(i, 5, '0'));
        SET course_id = 1 + (i % 14);
        SET year = 1 + (i % 4);
        SET gender_val = CASE WHEN i % 2 = 0 THEN 'Male' ELSE 'Female' END;
        SET birth_date = DATE_SUB(CURDATE(), 
            INTERVAL (18 + (i % 6)) YEAR - 
            INTERVAL (i % 12) MONTH - 
            INTERVAL (i % 28) DAY
        );
        
        -- Insert student with complete information
        INSERT INTO students (
            student_id, 
            password,
            first_name,
            middle_name,
            last_name,
            suffix,
            email,
            contact_number,
            emergency_contact_name,
            emergency_contact_number,
            emergency_contact_relationship,
            birthday,
            gender,
            religion,
            street_address,
            barangay,
            city,
            province,
            postal_code,
            course_id,
            year_level,
            section,
            enrollment_status,
            scholarship_type
        ) VALUES (
            student_id,
            hashed_password,
            ELT(1 + (i % 20), SUBSTRING_INDEX(SUBSTRING_INDEX(first_names, ',', 1 + (i % 20)), ',', -1)),
            ELT(1 + (i % 15), SUBSTRING_INDEX(SUBSTRING_INDEX(middle_names, ',', 1 + (i % 15)), ',', -1)),
            ELT(1 + (i % 15), SUBSTRING_INDEX(SUBSTRING_INDEX(last_names, ',', 1 + (i % 15)), ',', -1)),
            CASE WHEN i % 20 = 0 THEN 'Jr.' WHEN i % 50 = 0 THEN 'III' ELSE NULL END,
            CONCAT(LOWER(student_id), '@dyci.edu.ph'),
            CONCAT('09', LPAD(FLOOR(RAND() * 100000000), 9, '0')),
            CONCAT(
                ELT(1 + (i % 20), SUBSTRING_INDEX(SUBSTRING_INDEX(first_names, ',', 1 + (i % 20)), ',', -1)),
                ' ',
                ELT(1 + (i % 15), SUBSTRING_INDEX(SUBSTRING_INDEX(last_names, ',', 1 + (i % 15)), ',', -1))
            ),
            CONCAT('09', LPAD(FLOOR(RAND() * 100000000), 9, '0')),
            ELT(1 + (i % 5), 'Parent', 'Guardian', 'Spouse', 'Sibling', 'Relative'),
            birth_date,
            gender_val,
            ELT(1 + (i % 6), SUBSTRING_INDEX(SUBSTRING_INDEX(religions, ',', 1 + (i % 6)), ',', -1)),
            CONCAT(
                FLOOR(1 + (RAND() * 999)), ' ',
                ELT(1 + (i % 14), SUBSTRING_INDEX(SUBSTRING_INDEX(streets, ',', 1 + (i % 14)), ',', -1)),
                ' Street'
            ),
            ELT(1 + (i % 20), SUBSTRING_INDEX(SUBSTRING_INDEX(barangays, ',', 1 + (i % 20)), ',', -1)),
            'Dagupan City',
            'Pangasinan',
            '2400',
            course_id,
            year,
            CONCAT(
                (SELECT code FROM courses WHERE id = course_id),
                year,
                CHAR(65 + (i % 4))
            ),
            CASE 
                WHEN i % 10 = 0 THEN 'Irregular'
                WHEN i % 15 = 0 THEN 'Transferee'
                ELSE 'Regular'
            END,
            CASE 
                WHEN i % 8 = 0 THEN ELT(1 + (i % 5), SUBSTRING_INDEX(SUBSTRING_INDEX(scholarships, ',', 1 + (i % 5)), ',', -1))
                ELSE NULL
            END
        );
        
        -- Assign students to organizations (some students get multiple organizations)
        IF i % 3 = 0 THEN
            INSERT INTO student_organizations (student_id, organization_id, position, school_year)
            VALUES (
                i,
                1 + (i % 12),
                CASE 
                    WHEN i % 20 = 0 THEN 'President'
                    WHEN i % 20 = 1 THEN 'Vice President'
                    WHEN i % 20 = 2 THEN 'Secretary'
                    WHEN i % 20 = 3 THEN 'Treasurer'
                    ELSE 'Member'
                END,
                '2023-2024'
            );
        END IF;
        
        IF i % 5 = 0 THEN
            INSERT INTO student_organizations (student_id, organization_id, position, school_year)
            VALUES (
                i,
                1 + ((i + 3) % 12),
                'Member',
                '2023-2024'
            );
        END IF;
        
        SET i = i + 1;
    END WHILE;
END //
DELIMITER ;

-- Execute the stored procedure
CALL InsertStudentAccounts();

-- Drop the procedure
DROP PROCEDURE IF EXISTS InsertStudentAccounts; 