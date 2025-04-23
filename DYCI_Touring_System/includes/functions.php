<?php
require_once __DIR__ . '/../config/database.php';

function authenticateStudent($student_id, $password) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM students WHERE student_id = ? LIMIT 1");
        $stmt->execute([$student_id]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($student) {
            // For debugging
            error_log("Student found: " . $student_id);
            error_log("Stored password hash: " . $student['password']);
            
            if (password_verify($password, $student['password'])) {
                error_log("Password verified successfully");
                return $student; // Return the full student data
            } else {
                error_log("Password verification failed");
            }
        } else {
            error_log("No student found with ID: " . $student_id);
        }
        return false;
    } catch (PDOException $e) {
        error_log("Authentication error: " . $e->getMessage());
        return false;
    }
}

function authenticateAdmin($username, $password) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            return true;
        }
        return false;
    } catch(PDOException $e) {
        error_log("Admin authentication error: " . $e->getMessage());
        return false;
    }
}

function getBuildingDetails($building_id) {
    global $pdo;
    
    try {
        $sql = "SELECT b.*, COUNT(r.id) as room_count 
                FROM buildings b 
                LEFT JOIN rooms r ON b.id = r.building_id 
                WHERE b.id = ? 
                GROUP BY b.id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$building_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Get building details error: " . $e->getMessage());
        return false;
    }
}

function getBuildings() {
    global $pdo;
    
    try {
        $sql = "SELECT b.*, COUNT(r.id) as room_count 
                FROM buildings b 
                LEFT JOIN rooms r ON b.id = r.building_id 
                GROUP BY b.id 
                ORDER BY b.name, b.floor";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Get buildings error: " . $e->getMessage());
        return [];
    }
}

function getRoomsByBuilding($building_id) {
    global $pdo;
    
    try {
        error_log("Getting rooms for building ID: " . $building_id);
        $stmt = $pdo->prepare("SELECT * FROM rooms WHERE building_id = ? ORDER BY name");
        $stmt->execute([$building_id]);
        $rooms = $stmt->fetchAll();
        error_log("Found " . count($rooms) . " rooms");
        return $rooms;
    } catch(PDOException $e) {
        error_log("Get rooms error: " . $e->getMessage());
        return [];
    }
}

function getRoomDetails($room_id) {
    global $pdo;
    
    try {
        $sql = "SELECT r.*, b.name as building_name, b.floor,
                       r.image_url as room_image 
                FROM rooms r 
                JOIN buildings b ON r.building_id = b.id 
                WHERE r.id = ?";
                
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$room_id]);
        $room = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($room) {
            // Ensure image_url is set
            if (empty($room['room_image'])) {
                $room['room_image'] = 'default-room.jpg';
            }
        }
        
        return $room;
    } catch(PDOException $e) {
        error_log("Get room details error: " . $e->getMessage());
        return null;
    }
}

function getRoomSchedule($room_id) {
    global $pdo;
    
    try {
        $sql = "SELECT * FROM room_schedules WHERE room_id = ? ORDER BY day_of_week, start_time";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$room_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Get room schedule error: " . $e->getMessage());
        return [];
    }
}

function logVisit($user_type, $student_id = null, $room_id) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("INSERT INTO visit_logs (user_type, student_id, room_id) VALUES (?, ?, ?)");
        $stmt->execute([$user_type, $student_id, $room_id]);
        return true;
    } catch(PDOException $e) {
        error_log("Visit logging error: " . $e->getMessage());
        return false;
    }
}

function registerStudent($student_id, $password, $name, $course) {
    global $pdo;
    
    try {
        // First get the course_id
        $stmt = $pdo->prepare("SELECT id FROM courses WHERE code = ? LIMIT 1");
        $stmt->execute([$course]);
        $course_data = $stmt->fetch();
        
        if (!$course_data) {
            error_log("Course not found: " . $course);
            return false;
        }

        // Split the name into parts (assuming format: First Middle Last)
        $name_parts = explode(' ', $name);
        $first_name = $name_parts[0];
        $last_name = end($name_parts);
        $middle_name = count($name_parts) > 2 ? implode(' ', array_slice($name_parts, 1, -1)) : null;

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert the student with required fields
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
                section,
                street_address,
                barangay,
                city,
                province,
                postal_code,
                birthday,
                gender
            ) VALUES (
                ?, ?, ?, ?, ?, 
                ?, ?, ?, ?, ?,
                ?, ?, ?, ?, ?,
                ?
            )
        ");
        
        $stmt->execute([
            $student_id,
            $hashed_password,
            $first_name,
            $middle_name,
            $last_name,
            $student_id . '@dyci.edu.ph', // Default email
            $course_data['id'],
            1, // Default year level
            $course . '1A', // Default section
            'Not Provided', // Default address
            'Not Provided',
            'Dagupan City',
            'Pangasinan',
            '2400',
            date('Y-m-d'), // Current date as birthday temporarily
            'Male' // Default gender
        ]);
        
        return true;
    } catch(PDOException $e) {
        error_log("Registration error: " . $e->getMessage());
        return false;
    }
}

/**
 * Get detailed student information including course details
 * @param string $student_id The student ID to look up
 * @return array|false Student details or false if not found
 */
function getStudentDetails($student_id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("
            SELECT 
                s.*,
                c.name as course_name,
                c.code as course_code,
                c.specialization,
                c.units_required,
                c.years_to_complete,
                CONCAT(s.first_name, ' ', COALESCE(s.middle_name, ''), ' ', s.last_name) as full_name
            FROM students s
            LEFT JOIN courses c ON s.course_id = c.id
            WHERE s.student_id = ?
        ");
        $stmt->execute([$student_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Set default values for nullable fields
        if ($result) {
            $nullable_fields = [
                'gender', 'birthday', 'religion', 
                'street_address', 'barangay', 'city', 
                'province', 'postal_code',
                'emergency_contact_name', 'emergency_contact_number'
            ];
            
            foreach ($nullable_fields as $field) {
                if (!isset($result[$field])) {
                    $result[$field] = null;
                }
            }
        }
        
        return $result;
    } catch (PDOException $e) {
        error_log("Error in getStudentDetails: " . $e->getMessage());
        return false;
    }
}

function getStudentVisitHistory($student_id) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("
            SELECT vl.*, r.name as room_name, b.name as building_name, b.floor
            FROM visit_logs vl
            JOIN rooms r ON vl.room_id = r.id
            JOIN buildings b ON r.building_id = b.id
            WHERE vl.student_id = ? AND vl.user_type = 'student'
            ORDER BY vl.visit_time DESC
        ");
        $stmt->execute([$student_id]);
        return $stmt->fetchAll();
    } catch(PDOException $e) {
        error_log("Get visit history error: " . $e->getMessage());
        return [];
    }
}

function getTotalRooms() {
    global $pdo;
    
    try {
        $stmt = $pdo->query("SELECT COUNT(*) FROM rooms");
        return $stmt->fetchColumn();
    } catch(PDOException $e) {
        error_log("Get total rooms error: " . $e->getMessage());
        return 0;
    }
}

function getTotalVisits() {
    global $pdo;
    
    try {
        $stmt = $pdo->query("SELECT COUNT(*) FROM visit_logs");
        return $stmt->fetchColumn();
    } catch(PDOException $e) {
        error_log("Get total visits error: " . $e->getMessage());
        return 0;
    }
}

function getTotalBuildings() {
    global $pdo;
    
    try {
        $stmt = $pdo->query("SELECT COUNT(*) FROM buildings");
        return $stmt->fetchColumn();
    } catch(PDOException $e) {
        error_log("Get total buildings error: " . $e->getMessage());
        return 0;
    }
}

function getTotalStudents() {
    global $pdo;
    
    try {
        $stmt = $pdo->query("SELECT COUNT(*) FROM students");
        return $stmt->fetchColumn();
    } catch(PDOException $e) {
        error_log("Get total students error: " . $e->getMessage());
        return 0;
    }
}

function getRecentVisits($limit = 5) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("
            SELECT 
                vl.*,
                r.name as room_name,
                b.name as building_name,
                b.floor,
                CONCAT(s.first_name, ' ', COALESCE(s.middle_name, ''), ' ', s.last_name) as student_name
            FROM visit_logs vl
            JOIN rooms r ON vl.room_id = r.id
            JOIN buildings b ON r.building_id = b.id
            LEFT JOIN students s ON vl.student_id = s.student_id
            ORDER BY vl.visit_time DESC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Get recent visits error: " . $e->getMessage());
        return [];
    }
}

function addBuilding($name, $floor, $description, $image_url = null) {
    global $pdo;
    
    try {
        // Validate inputs
        if (empty($name) || !is_numeric($floor)) {
            error_log("Invalid building data: name or floor invalid");
            return false;
        }
        
        $stmt = $pdo->prepare("
            INSERT INTO buildings (name, floor, description, image_url)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$name, $floor, $description, $image_url]);
        return $pdo->lastInsertId();
    } catch(PDOException $e) {
        error_log("Add building error: " . $e->getMessage());
        return false;
    }
}

function updateBuilding($id, $name, $floor, $description, $image_url = null) {
    global $pdo;
    
    try {
        // Validate inputs
        if (empty($id) || empty($name) || !is_numeric($floor)) {
            error_log("Invalid building data: id, name or floor invalid");
            return false;
        }
        
        // Check if building exists
        $check = $pdo->prepare("SELECT id FROM buildings WHERE id = ?");
        $check->execute([$id]);
        if (!$check->fetch()) {
            error_log("Building not found with ID: " . $id);
            return false;
        }
        
        $stmt = $pdo->prepare("
            UPDATE buildings
            SET name = ?, floor = ?, description = ?, image_url = ?
            WHERE id = ?
        ");
        $stmt->execute([$name, $floor, $description, $image_url, $id]);
        return true;
    } catch(PDOException $e) {
        error_log("Update building error: " . $e->getMessage());
        return false;
    }
}

function deleteBuilding($id) {
    global $pdo;
    
    try {
        // Check if building exists and get room count
        $check = $pdo->prepare("
            SELECT b.id, COUNT(r.id) as room_count 
            FROM buildings b 
            LEFT JOIN rooms r ON b.id = r.building_id 
            WHERE b.id = ? 
            GROUP BY b.id
        ");
        $check->execute([$id]);
        $building = $check->fetch(PDO::FETCH_ASSOC);
        
        if (!$building) {
            error_log("Building not found with ID: " . $id);
            return false;
        }
        
        $pdo->beginTransaction();
        
        // Delete associated rooms first if any exist
        if ($building['room_count'] > 0) {
            $stmt = $pdo->prepare("DELETE FROM rooms WHERE building_id = ?");
            $stmt->execute([$id]);
        }
        
        // Then delete the building
        $stmt = $pdo->prepare("DELETE FROM buildings WHERE id = ?");
        $stmt->execute([$id]);
        
        $pdo->commit();
        return true;
    } catch(PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log("Delete building error: " . $e->getMessage());
        return false;
    }
}

/**
 * Get all organizations a student is part of
 * @param int $student_id The student's ID
 * @return array List of organizations
 */
function getStudentOrganizations($student_id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("
            SELECT o.name as organization_name,
                   o.acronym,
                   o.type as organization_type,
                   so.position,
                   so.school_year
            FROM student_organizations so
            JOIN organizations o ON so.organization_id = o.id
            WHERE so.student_id = ?
            ORDER BY so.school_year DESC, o.name ASC
        ");
        $stmt->execute([$student_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error in getStudentOrganizations: " . $e->getMessage());
        return [];
    }
}

function setRememberToken($student_id, $token) {
    require_once 'db.php';
    
    try {
        $stmt = $pdo->prepare("UPDATE students SET remember_token = ? WHERE student_id = ?");
        $stmt->execute([$token, $student_id]);
        return true;
    } catch (PDOException $e) {
        error_log("Set remember token error: " . $e->getMessage());
        return false;
    }
}

function validateRememberToken($token) {
    require_once 'db.php';
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM students WHERE remember_token = ?");
        $stmt->execute([$token]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Validate remember token error: " . $e->getMessage());
        return false;
    }
}

// Add CSRF token validation function
function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function clearRememberMeToken($student_id) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("DELETE FROM remember_me_tokens WHERE student_id = ?");
        $stmt->execute([$student_id]);
        return true;
    } catch(PDOException $e) {
        error_log("Clear remember me token error: " . $e->getMessage());
        return false;
    }
}

function updateRoomImage($room_id, $image_file) {
    global $pdo;
    
    try {
        // Check if room exists
        $stmt = $pdo->prepare("SELECT id, name FROM rooms WHERE id = ?");
        $stmt->execute([$room_id]);
        $room = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$room) {
            error_log("Room not found with ID: " . $room_id);
            return false;
        }

        // Create rooms directory if it doesn't exist
        $upload_dir = __DIR__ . '/../assets/images/rooms/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Get file extension
        $file_extension = strtolower(pathinfo($image_file['name'], PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($file_extension, $allowed_extensions)) {
            error_log("Invalid file type. Only JPG, JPEG, PNG & GIF files are allowed.");
            return false;
        }

        // Generate new filename using room ID (to ensure uniqueness)
        $new_filename = 'room_' . $room_id . '.' . $file_extension;
        $target_file = $upload_dir . $new_filename;

        // Remove old image if exists (with any extension)
        foreach ($allowed_extensions as $ext) {
            $old_file = $upload_dir . 'room_' . $room_id . '.' . $ext;
            if (file_exists($old_file)) {
                unlink($old_file);
            }
        }

        // Upload new image
        if (move_uploaded_file($image_file['tmp_name'], $target_file)) {
            // Update room record with new image path
            $stmt = $pdo->prepare("UPDATE rooms SET image_url = ? WHERE id = ?");
            $stmt->execute([$new_filename, $room_id]);
            return true;
        } else {
            error_log("Failed to upload image.");
            return false;
        }
    } catch(PDOException $e) {
        error_log("Update room image error: " . $e->getMessage());
        return false;
    }
}

function getRoomImage($room_id) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("SELECT image_url FROM rooms WHERE id = ?");
        $stmt->execute([$room_id]);
        $room = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$room || empty($room['image_url'])) {
            return 'default-room.jpg';
        }

        $image_path = __DIR__ . '/../assets/images/rooms/' . $room['image_url'];
        if (file_exists($image_path)) {
            return $room['image_url'];
        }

        return 'default-room.jpg';
    } catch(PDOException $e) {
        error_log("Get room image error: " . $e->getMessage());
        return 'default-room.jpg';
    }
}

function getRoomsByBuildingWithImages($building_id) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("
            SELECT r.*, b.name as building_name, b.floor 
            FROM rooms r 
            JOIN buildings b ON r.building_id = b.id 
            WHERE r.building_id = ? 
            ORDER BY r.name
        ");
        $stmt->execute([$building_id]);
        $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Add image URLs to each room
        foreach ($rooms as &$room) {
            $room['image_url'] = getRoomImage($room['id']);
        }
        
        return $rooms;
    } catch(PDOException $e) {
        error_log("Get rooms error: " . $e->getMessage());
        return [];
    }
}

function getAllRoomsWithImages() {
    global $pdo;
    
    try {
        $stmt = $pdo->query("
            SELECT r.*, b.name as building_name, b.floor 
            FROM rooms r 
            JOIN buildings b ON r.building_id = b.id 
            ORDER BY b.name, r.name
        ");
        $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Add image URLs to each room
        foreach ($rooms as &$room) {
            $room['image_url'] = getRoomImage($room['id']);
        }
        
        return $rooms;
    } catch(PDOException $e) {
        error_log("Get all rooms error: " . $e->getMessage());
        return [];
    }
} 