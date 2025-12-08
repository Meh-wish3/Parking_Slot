<?php
header('Content-Type: application/json');
require_once 'db.php';

$conn = getDBConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'checkAvailability') {
        $date = $_POST['date'] ?? '';
        $time = $_POST['time'] ?? '';
        
        // Get all slots
        $slots = $conn->query("SELECT * FROM slots ORDER BY slot_number");
        $availableSlots = [];
        
        while ($slot = $slots->fetch_assoc()) {
            // Check if slot is booked for this date and time
            $checkBooking = $conn->prepare("SELECT id FROM bookings WHERE slot_id = ? AND booking_date = ? AND booking_time = ? AND status = 'active'");
            $checkBooking->bind_param("iss", $slot['id'], $date, $time);
            $checkBooking->execute();
            $result = $checkBooking->get_result();
            
            $slot['is_available'] = ($result->num_rows == 0 && $slot['status'] === 'available');
            $availableSlots[] = $slot;
            
            $checkBooking->close();
        }
        
        echo json_encode(['success' => true, 'slots' => $availableSlots]);
    }
    
    elseif ($action === 'bookSlot') {
        $slotId = intval($_POST['slot_id'] ?? 0);
        $slotNumber = $_POST['slot_number'] ?? '';
        $name = trim($_POST['name'] ?? '');
        $vehicleNumber = trim($_POST['vehicle_number'] ?? '');
        $phoneNumber = trim($_POST['phone_number'] ?? '');
        $date = $_POST['date'] ?? '';
        $time = $_POST['time'] ?? '';
        
        // Validation
        if (empty($name) || empty($vehicleNumber) || empty($phoneNumber) || empty($date) || empty($time)) {
            echo json_encode(['success' => false, 'message' => 'All fields are required']);
            exit;
        }
        
        // Check if slot is still available
        $checkSlot = $conn->prepare("SELECT status FROM slots WHERE id = ?");
        $checkSlot->bind_param("i", $slotId);
        $checkSlot->execute();
        $slotResult = $checkSlot->get_result();
        
        if ($slotResult->num_rows == 0) {
            echo json_encode(['success' => false, 'message' => 'Slot not found']);
            exit;
        }
        
        $slotData = $slotResult->fetch_assoc();
        
        // Check for existing booking at same date/time
        $checkBooking = $conn->prepare("SELECT id FROM bookings WHERE slot_id = ? AND booking_date = ? AND booking_time = ? AND status = 'active'");
        $checkBooking->bind_param("iss", $slotId, $date, $time);
        $checkBooking->execute();
        $bookingResult = $checkBooking->get_result();
        
        if ($bookingResult->num_rows > 0) {
            echo json_encode(['success' => false, 'message' => 'Slot is already booked for this date and time']);
            exit;
        }
        
        // Insert booking
        $stmt = $conn->prepare("INSERT INTO bookings (slot_id, slot_number, name, vehicle_number, phone_number, booking_date, booking_time) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssss", $slotId, $slotNumber, $name, $vehicleNumber, $phoneNumber, $date, $time);
        
        if ($stmt->execute()) {
            // Update slot status
            $updateSlot = $conn->prepare("UPDATE slots SET status = 'booked' WHERE id = ?");
            $updateSlot->bind_param("i", $slotId);
            $updateSlot->execute();
            $updateSlot->close();
            
            echo json_encode(['success' => true, 'message' => 'Booking confirmed successfully!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to create booking']);
        }
        
        $stmt->close();
        $checkSlot->close();
        $checkBooking->close();
    }
    
    elseif ($action === 'getBookings') {
        $bookings = $conn->query("SELECT b.*, s.slot_number FROM bookings b JOIN slots s ON b.slot_id = s.id ORDER BY b.created_at DESC");
        $bookingList = [];
        
        while ($booking = $bookings->fetch_assoc()) {
            $bookingList[] = $booking;
        }
        
        echo json_encode(['success' => true, 'bookings' => $bookingList]);
    }
    
    elseif ($action === 'getStats') {
        $totalSlots = $conn->query("SELECT COUNT(*) as count FROM slots")->fetch_assoc()['count'];
        $bookedSlots = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE status = 'active' AND booking_date >= CURDATE()")->fetch_assoc()['count'];
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
        $check = $conn->prepare("SELECT id FROM slots WHERE slot_number = ?");
        $check->bind_param("s", $slotNumber);
        $check->execute();
        
        if ($check->get_result()->num_rows > 0) {
            echo json_encode(['success' => false, 'message' => 'Slot already exists']);
            exit;
        }
        
        $stmt = $conn->prepare("INSERT INTO slots (slot_number, status) VALUES (?, 'available')");
        $stmt->bind_param("s", $slotNumber);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Slot added successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add slot']);
        }
        
        $stmt->close();
        $check->close();
    }
    
    elseif ($action === 'removeSlot') {
        $slotNumber = trim($_POST['slot_number'] ?? '');
        
        if (empty($slotNumber)) {
            echo json_encode(['success' => false, 'message' => 'Slot number is required']);
            exit;
        }
        
        // Check if slot has active bookings
        $checkBooking = $conn->prepare("SELECT id FROM bookings WHERE slot_number = ? AND status = 'active'");
        $checkBooking->bind_param("s", $slotNumber);
        $checkBooking->execute();
        
        if ($checkBooking->get_result()->num_rows > 0) {
            echo json_encode(['success' => false, 'message' => 'Cannot remove slot with active bookings']);
            exit;
        }
        
        $stmt = $conn->prepare("DELETE FROM slots WHERE slot_number = ?");
        $stmt->bind_param("s", $slotNumber);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Slot removed successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to remove slot']);
        }
        
        $stmt->close();
        $checkBooking->close();
    }
}

$conn->close();
?>

