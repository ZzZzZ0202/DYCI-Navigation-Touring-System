CREATE TABLE IF NOT EXISTS room_facilities (
    id INT PRIMARY KEY AUTO_INCREMENT,
    room_id INT NOT NULL,
    facility_name VARCHAR(255) NOT NULL,
    quantity INT DEFAULT 1,
    description TEXT,
    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample data for Room 101
INSERT INTO room_facilities (room_id, facility_name, quantity, description) VALUES
(1, 'Computer', 30, 'Desktop computers with latest specifications'),
(1, 'Projector', 1, 'HD Projector with HDMI and VGA inputs'),
(1, 'Whiteboard', 2, 'Large whiteboards with markers'),
(1, 'Air Conditioner', 2, 'Split-type air conditioning units'),
(1, 'Student Chairs', 40, 'Ergonomic chairs with writing tablets'); 