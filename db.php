<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'parkease_db');

// Create connection
function getDBConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    return $conn;
}

// Initialize database tables
function initDatabase() {
    $conn = getDBConnection();
    
    // Create slots table
    $slotsTable = "CREATE TABLE IF NOT EXISTS slots (
        id INT AUTO_INCREMENT PRIMARY KEY,
        slot_number VARCHAR(10) UNIQUE NOT NULL,
        status ENUM('available', 'booked') DEFAULT 'available',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    // Create bookings table
    $bookingsTable = "CREATE TABLE IF NOT EXISTS bookings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        slot_id INT NOT NULL,
        slot_number VARCHAR(10) NOT NULL,
        name VARCHAR(100) NOT NULL,
        vehicle_number VARCHAR(20) NOT NULL,
        phone_number VARCHAR(15) NOT NULL,
        booking_date DATE NOT NULL,
        booking_time TIME NOT NULL,
        status ENUM('active', 'completed', 'cancelled') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (slot_id) REFERENCES slots(id) ON DELETE CASCADE
    )";
    
    $conn->query($slotsTable);
    $conn->query($bookingsTable);
    
    // Insert default slots if they don't exist
    $checkSlots = $conn->query("SELECT COUNT(*) as count FROM slots");
    $slotCount = $checkSlots->fetch_assoc()['count'];
    
    if ($slotCount == 0) {
        for ($i = 1; $i <= 16; $i++) {
            $slotNum = str_pad($i, 2, '0', STR_PAD_LEFT);
            $conn->query("INSERT INTO slots (slot_number, status) VALUES ('P$slotNum', 'available')");
        }
    }
    
    $conn->close();
}

// Initialize on first load
initDatabase();
?>

