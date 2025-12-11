<?php
session_start();
header('Content-Type: application/json');
require_once 'db.php';

try {
    $db = getDBConnection();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database connection error. Please check your database configuration.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'checkAvailability') {
        $date = $_POST['date'] ?? '';
        $checkInTime = $_POST['check_in_time'] ?? '';
        $checkOutTime = $_POST['check_out_time'] ?? '';
        
        // Get all slots
        $slots = $db->query("SELECT * FROM slots ORDER BY slot_number");
        $availableSlots = [];
        
        while ($slot = $slots->fetch(PDO::FETCH_ASSOC)) {
            // Check if slot has any overlapping bookings for this date
            // A slot is unavailable if there's a booking where:
            // (existing check_in < requested check_out) AND (existing check_out > requested check_in)
            $stmt = $db->prepare("SELECT id FROM bookings WHERE slot_id = ? AND booking_date = ? AND status = 'active' AND check_in_time < ? AND check_out_time > ?");
            $stmt->execute([$slot['id'], $date, $checkOutTime, $checkInTime]);
            $result = $stmt->fetch();
            
            $slot['is_available'] = (!$result && $slot['status'] === 'available');
            $availableSlots[] = $slot;
        }
        
        echo json_encode(['success' => true, 'slots' => $availableSlots]);
    }
    
    elseif ($action === 'bookSlot') {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Please login to book a slot', 'requireLogin' => true]);
            exit;
        }
        
        $userId = $_SESSION['user_id'];
        $slotId = intval($_POST['slot_id'] ?? 0);
        $slotNumber = $_POST['slot_number'] ?? '';
        $name = trim($_POST['name'] ?? '');
        $vehicleNumber = trim($_POST['vehicle_number'] ?? '');
        $phoneNumber = trim($_POST['phone_number'] ?? '');
        $date = $_POST['date'] ?? '';
        $checkInTime = $_POST['check_in_time'] ?? '';
        $checkOutTime = $_POST['check_out_time'] ?? '';
        
        // Validation
        if (empty($name) || empty($vehicleNumber) || empty($phoneNumber) || empty($date) || empty($checkInTime) || empty($checkOutTime)) {
            echo json_encode(['success' => false, 'message' => 'All fields are required']);
            exit;
        }
        
        // Validate check-out is after check-in
        if ($checkOutTime <= $checkInTime) {
            echo json_encode(['success' => false, 'message' => 'Check-out time must be after check-in time']);
            exit;
        }
        
        // Check if slot is still available
        $stmt = $db->prepare("SELECT status FROM slots WHERE id = ?");
        $stmt->execute([$slotId]);
        $slotData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$slotData) {
            echo json_encode(['success' => false, 'message' => 'Slot not found']);
            exit;
        }
        
        // Check for overlapping bookings
        $stmt = $db->prepare("SELECT id FROM bookings WHERE slot_id = ? AND booking_date = ? AND status = 'active' AND check_in_time < ? AND check_out_time > ?");
        $stmt->execute([$slotId, $date, $checkOutTime, $checkInTime]);
        
        if ($stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Slot is already booked for this time range']);
            exit;
        }
        
        // Insert booking with check-in and check-out times
        $stmt = $db->prepare("INSERT INTO bookings (slot_id, slot_number, user_id, name, vehicle_number, phone_number, booking_date, check_in_time, check_out_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        if ($stmt->execute([$slotId, $slotNumber, $userId, $name, $vehicleNumber, $phoneNumber, $date, $checkInTime, $checkOutTime])) {
            echo json_encode(['success' => true, 'message' => 'Booking confirmed successfully!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to create booking']);
        }
    }
    
    elseif ($action === 'getBookings') {
        $stmt = $db->query("SELECT b.*, s.slot_number FROM bookings b JOIN slots s ON b.slot_id = s.id ORDER BY b.created_at DESC");
        $bookingList = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode(['success' => true, 'bookings' => $bookingList]);
    }
    
    elseif ($action === 'getStats') {
        $totalSlots = $db->query("SELECT COUNT(*) as count FROM slots")->fetch(PDO::FETCH_ASSOC)['count'];
        $bookedSlots = $db->query("SELECT COUNT(*) as count FROM bookings WHERE status = 'active' AND booking_date >= date('now')")->fetch(PDO::FETCH_ASSOC)['count'];
        $availableSlots = $totalSlots - $bookedSlots;
        
        echo json_encode([
            'success' => true,
            'stats' => [
                'total' => intval($totalSlots),
                'booked' => intval($bookedSlots),
                'available' => intval($availableSlots)
            ]
        ]);
    }
    
    elseif ($action === 'addSlot') {
        $slotNumber = trim($_POST['slot_number'] ?? '');
        
        if (empty($slotNumber)) {
            echo json_encode(['success' => false, 'message' => 'Slot number is required']);
            exit;
        }
        
        // Check if slot already exists
        $stmt = $db->prepare("SELECT id FROM slots WHERE slot_number = ?");
        $stmt->execute([$slotNumber]);
        
        if ($stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Slot already exists']);
            exit;
        }
        
        $stmt = $db->prepare("INSERT INTO slots (slot_number, status) VALUES (?, 'available')");
        
        if ($stmt->execute([$slotNumber])) {
            echo json_encode(['success' => true, 'message' => 'Slot added successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add slot']);
        }
    }
    
    elseif ($action === 'removeSlot') {
        $slotNumber = trim($_POST['slot_number'] ?? '');
        
        if (empty($slotNumber)) {
            echo json_encode(['success' => false, 'message' => 'Slot number is required']);
            exit;
        }
        
        // Check if slot has active bookings
        $stmt = $db->prepare("SELECT id FROM bookings WHERE slot_number = ? AND status = 'active'");
        $stmt->execute([$slotNumber]);
        
        if ($stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Cannot remove slot with active bookings']);
            exit;
        }
        
        $stmt = $db->prepare("DELETE FROM slots WHERE slot_number = ?");
        
        if ($stmt->execute([$slotNumber])) {
            echo json_encode(['success' => true, 'message' => 'Slot removed successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to remove slot']);
        }
    }
}
?>
