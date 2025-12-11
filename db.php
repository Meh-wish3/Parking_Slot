<?php
// Database configuration
define('DB_FILE', __DIR__ . '/parkease.db');

// Create connection
function getDBConnection() {
    try {
        $db = new PDO('sqlite:' . DB_FILE);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    } catch (PDOException $e) {
        error_log("Database connection failed: " . $e->getMessage());
        throw new Exception("Database connection failed");
    }
}

// Initialize database tables
function initDatabase() {
    $db = getDBConnection();
    
    // Create slots table
    $db->exec("CREATE TABLE IF NOT EXISTS slots (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        slot_number TEXT UNIQUE NOT NULL,
        status TEXT DEFAULT 'available' CHECK(status IN ('available', 'booked')),
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");
    
    // Create bookings table
    $db->exec("CREATE TABLE IF NOT EXISTS bookings (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        slot_id INTEGER NOT NULL,
        slot_number TEXT NOT NULL,
        user_id INTEGER,
        name TEXT NOT NULL,
        vehicle_number TEXT NOT NULL,
        phone_number TEXT NOT NULL,
        booking_date DATE NOT NULL,
        check_in_time TIME NOT NULL,
        check_out_time TIME NOT NULL,
        status TEXT DEFAULT 'active' CHECK(status IN ('active', 'completed', 'cancelled')),
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (slot_id) REFERENCES slots(id) ON DELETE CASCADE,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
    )");
    
    // Create users table
    $db->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT UNIQUE NOT NULL,
        password TEXT NOT NULL,
        phone TEXT,
        is_admin INTEGER DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");
    
    // Insert default slots if they don't exist
    $stmt = $db->query("SELECT COUNT(*) as count FROM slots");
    $slotCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    if ($slotCount == 0) {
        $stmt = $db->prepare("INSERT INTO slots (slot_number, status) VALUES (?, 'available')");
        for ($i = 1; $i <= 16; $i++) {
            $slotNum = 'P' . str_pad($i, 2, '0', STR_PAD_LEFT);
            $stmt->execute([$slotNum]);
        }
    }
    
    // Create default admin user if no admin exists
    $stmt = $db->query("SELECT COUNT(*) as count FROM users WHERE is_admin = 1");
    $adminCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    if ($adminCount == 0) {
        $adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $db->prepare("INSERT INTO users (name, email, password, is_admin) VALUES (?, ?, ?, 1)");
        $stmt->execute(['Admin', 'admin@parkease.com', $adminPassword]);
    }
}

// Initialize database on first load
try {
    initDatabase();
} catch (Exception $e) {
    error_log("Database initialization error: " . $e->getMessage());
}
?>
