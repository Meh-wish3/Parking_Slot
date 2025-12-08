-- ParkEase Database Schema
-- Run this SQL file if you want to manually set up the database

CREATE DATABASE IF NOT EXISTS parkease_db;
USE parkease_db;

-- Slots table
CREATE TABLE IF NOT EXISTS slots (
    id INT AUTO_INCREMENT PRIMARY KEY,
    slot_number VARCHAR(10) UNIQUE NOT NULL,
    status ENUM('available', 'booked') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bookings table
CREATE TABLE IF NOT EXISTS bookings (
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
);

-- Insert default slots (P01-P16)
INSERT INTO slots (slot_number, status) VALUES
('P01', 'available'),
('P02', 'available'),
('P03', 'available'),
('P04', 'available'),
('P05', 'available'),
('P06', 'available'),
('P07', 'available'),
('P08', 'available'),
('P09', 'available'),
('P10', 'available'),
('P11', 'available'),
('P12', 'available'),
('P13', 'available'),
('P14', 'available'),
('P15', 'available'),
('P16', 'available')
ON DUPLICATE KEY UPDATE slot_number=slot_number;

