CREATE TABLE IF NOT EXISTS room_schedules (
    id INT PRIMARY KEY AUTO_INCREMENT,
    room_id INT NOT NULL,
    day_of_week ENUM('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday') NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    subject_name VARCHAR(255),
    professor_name VARCHAR(255),
    section VARCHAR(50),
    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample data for Room 101
INSERT INTO room_schedules (room_id, day_of_week, start_time, end_time, subject_name, professor_name, section) VALUES
(1, 'Monday', '08:00:00', '09:30:00', 'Computer Programming 1', 'Dr. Smith', 'BSCS 1A'),
(1, 'Monday', '10:00:00', '11:30:00', 'Web Development', 'Prof. Johnson', 'BSIT 2B'),
(1, 'Tuesday', '13:00:00', '14:30:00', 'Database Management', 'Dr. Garcia', 'BSCS 2A'),
(1, 'Wednesday', '08:00:00', '09:30:00', 'Software Engineering', 'Prof. Wilson', 'BSCS 3A'),
(1, 'Thursday', '15:00:00', '16:30:00', 'Mobile Development', 'Dr. Lee', 'BSIT 3B'); 