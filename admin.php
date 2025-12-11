<?php
session_start();
require_once 'db.php';

// Check if user is logged in and is admin
$isAdmin = false;
if (isset($_SESSION['user_id'])) {
    try {
        $db = getDBConnection();
        $stmt = $db->prepare("SELECT is_admin FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $isAdmin = ($user && $user['is_admin'] == 1);
    } catch (Exception $e) {
        $isAdmin = false;
    }
}

if (!$isAdmin) {
    // Redirect non-admin users
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - ParkEase</title>
    <link rel="icon" type="image/png" href="favicon.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn { animation: fadeIn 0.5s ease-out; }
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-50 min-h-screen">
    
    <!-- Navbar -->
    <nav class="bg-white shadow-lg">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <a href="index.php" class="flex items-center gap-2">
                        <img src="favicon.png" alt="ParkEase Logo" class="w-10 h-10 rounded-lg">
                        <span class="text-2xl font-bold bg-gradient-to-r from-teal-500 to-blue-600 bg-clip-text text-transparent">ParkEase</span>
                    </a>
                    <span class="ml-4 text-gray-500">Admin Dashboard</span>
                </div>
                <a href="index.php" class="px-4 py-2 text-gray-700 hover:text-blue-600 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Home
                </a>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="glass rounded-2xl p-6 shadow-xl animate-fadeIn">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium mb-1">Total Slots</p>
                        <p id="statTotal" class="text-3xl font-bold text-gray-900">-</p>
                    </div>
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-parking text-white text-2xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="glass rounded-2xl p-6 shadow-xl animate-fadeIn" style="animation-delay: 0.1s;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium mb-1">Booked Slots</p>
                        <p id="statBooked" class="text-3xl font-bold text-red-600">-</p>
                    </div>
                    <div class="w-16 h-16 bg-gradient-to-br from-red-400 to-red-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-lock text-white text-2xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="glass rounded-2xl p-6 shadow-xl animate-fadeIn" style="animation-delay: 0.2s;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium mb-1">Available Slots</p>
                        <p id="statAvailable" class="text-3xl font-bold text-green-600">-</p>
                    </div>
                    <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-check-circle text-white text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Bookings Table -->
        <div class="glass rounded-2xl p-6 md:p-8 shadow-xl mb-8 animate-fadeIn">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900">All Bookings</h2>
                <button onclick="refreshBookings()" class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">
                    <i class="fas fa-sync-alt mr-2"></i>Refresh
                </button>
            </div>
            
            <div id="loadingBookings" class="text-center py-12">
                <div class="inline-block">
                    <i class="fas fa-spinner fa-spin text-4xl text-blue-600"></i>
                    <p class="mt-4 text-gray-600">Loading bookings...</p>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full hidden" id="bookingsTable">
                    <thead>
                        <tr class="border-b-2 border-gray-200">
                            <th class="text-left py-4 px-4 font-semibold text-gray-700">ID</th>
                            <th class="text-left py-4 px-4 font-semibold text-gray-700">Slot</th>
                            <th class="text-left py-4 px-4 font-semibold text-gray-700">Name</th>
                            <th class="text-left py-4 px-4 font-semibold text-gray-700">Vehicle</th>
                            <th class="text-left py-4 px-4 font-semibold text-gray-700">Phone</th>
                            <th class="text-left py-4 px-4 font-semibold text-gray-700">Date</th>
                            <th class="text-left py-4 px-4 font-semibold text-gray-700">Check-in</th>
                            <th class="text-left py-4 px-4 font-semibold text-gray-700">Check-out</th>
                            <th class="text-left py-4 px-4 font-semibold text-gray-700">Status</th>
                            <th class="text-left py-4 px-4 font-semibold text-gray-700">Booked At</th>
                        </tr>
                    </thead>
                    <tbody id="bookingsTableBody">
                        <!-- Bookings will be inserted here -->
                    </tbody>
                </table>
            </div>
            
            <div id="noBookingsMessage" class="text-center py-12 hidden">
                <i class="fas fa-inbox text-4xl text-gray-400 mb-4"></i>
                <p class="text-gray-600 text-lg">No bookings found</p>
            </div>
        </div>
        
        <!-- Slot Management -->
        <div class="glass rounded-2xl p-6 md:p-8 shadow-xl animate-fadeIn">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-6">Slot Management</h2>
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Add New Slot</h3>
                    <form id="addSlotForm" class="space-y-4">
                        <div>
                            <label for="newSlotNumber" class="block text-sm font-medium text-gray-700 mb-2">Slot Number</label>
                            <input type="text" id="newSlotNumber" name="slot_number" placeholder="P17" required
                                   class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:outline-none transition-colors">
                        </div>
                        <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-xl hover:shadow-lg transform hover:scale-105 transition-all duration-200 font-semibold">
                            <i class="fas fa-plus mr-2"></i>Add Slot
                        </button>
                    </form>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Remove Slot</h3>
                    <form id="removeSlotForm" class="space-y-4">
                        <div>
                            <label for="removeSlotNumber" class="block text-sm font-medium text-gray-700 mb-2">Slot Number</label>
                            <input type="text" id="removeSlotNumber" name="slot_number" placeholder="P17" required
                                   class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:outline-none transition-colors">
                        </div>
                        <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:shadow-lg transform hover:scale-105 transition-all duration-200 font-semibold">
                            <i class="fas fa-trash mr-2"></i>Remove Slot
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Toast Notification -->
    <div id="toast" class="fixed bottom-4 right-4 z-50 transform translate-y-full transition-transform duration-300">
        <div class="glass rounded-xl shadow-2xl p-4 min-w-[300px] flex items-center gap-4">
            <i id="toastIcon" class="fas fa-check-circle text-2xl"></i>
            <p id="toastMessage" class="flex-1 font-medium"></p>
            <button onclick="hideToast()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    
    <script src="script.js"></script>
    <script>
        // Admin-specific functions
        function refreshBookings() {
            loadBookings();
            loadStats();
        }
        
        function loadStats() {
            fetch('book.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'action=getStats'
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('statTotal').textContent = data.stats.total;
                    document.getElementById('statBooked').textContent = data.stats.booked;
                    document.getElementById('statAvailable').textContent = data.stats.available;
                }
            })
            .catch(err => console.error('Error loading stats:', err));
        }
        
        function loadBookings() {
            document.getElementById('loadingBookings').classList.remove('hidden');
            document.getElementById('bookingsTable').classList.add('hidden');
            document.getElementById('noBookingsMessage').classList.add('hidden');
            
            fetch('book.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'action=getBookings'
            })
            .then(res => res.json())
            .then(data => {
                document.getElementById('loadingBookings').classList.add('hidden');
                
                if (data.success && data.bookings.length > 0) {
                    const tbody = document.getElementById('bookingsTableBody');
                    tbody.innerHTML = '';
                    
                    data.bookings.forEach(booking => {
                        const row = document.createElement('tr');
                        row.className = 'border-b border-gray-100 hover:bg-gray-50 transition-colors';
                        row.innerHTML = `
                            <td class="py-4 px-4">${booking.id}</td>
                            <td class="py-4 px-4 font-semibold">${booking.slot_number}</td>
                            <td class="py-4 px-4">${booking.name}</td>
                            <td class="py-4 px-4">${booking.vehicle_number}</td>
                            <td class="py-4 px-4">${booking.phone_number}</td>
                            <td class="py-4 px-4">${booking.booking_date}</td>
                            <td class="py-4 px-4">${booking.check_in_time}</td>
                            <td class="py-4 px-4">${booking.check_out_time}</td>
                            <td class="py-4 px-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold ${
                                    booking.status === 'active' ? 'bg-green-100 text-green-800' :
                                    booking.status === 'completed' ? 'bg-blue-100 text-blue-800' :
                                    'bg-red-100 text-red-800'
                                }">${booking.status}</span>
                            </td>
                            <td class="py-4 px-4 text-sm text-gray-500">${new Date(booking.created_at).toLocaleString()}</td>
                        `;
                        tbody.appendChild(row);
                    });
                    
                    document.getElementById('bookingsTable').classList.remove('hidden');
                } else {
                    document.getElementById('noBookingsMessage').classList.remove('hidden');
                }
            })
            .catch(err => {
                console.error('Error loading bookings:', err);
                document.getElementById('loadingBookings').classList.add('hidden');
                showToast('Error loading bookings', 'error');
            });
        }
        
        // Add slot
        document.getElementById('addSlotForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const slotNumber = document.getElementById('newSlotNumber').value.trim();
            
            if (!slotNumber) {
                showToast('Please enter a slot number', 'error');
                return;
            }
            
            const formData = new FormData();
            formData.append('action', 'addSlot');
            formData.append('slot_number', slotNumber);
            
            fetch('book.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message || 'Slot added successfully', 'success');
                    document.getElementById('newSlotNumber').value = '';
                    loadStats();
                } else {
                    showToast(data.message || 'Failed to add slot', 'error');
                }
            })
            .catch(err => {
                console.error('Error:', err);
                showToast('An error occurred', 'error');
            });
        });
        
        // Remove slot
        document.getElementById('removeSlotForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const slotNumber = document.getElementById('removeSlotNumber').value.trim();
            
            if (!slotNumber) {
                showToast('Please enter a slot number', 'error');
                return;
            }
            
            if (!confirm(`Are you sure you want to remove slot ${slotNumber}?`)) {
                return;
            }
            
            const formData = new FormData();
            formData.append('action', 'removeSlot');
            formData.append('slot_number', slotNumber);
            
            fetch('book.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message || 'Slot removed successfully', 'success');
                    document.getElementById('removeSlotNumber').value = '';
                    loadStats();
                } else {
                    showToast(data.message || 'Failed to remove slot', 'error');
                }
            })
            .catch(err => {
                console.error('Error:', err);
                showToast('An error occurred', 'error');
            });
        });
        
        // Load data on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadStats();
            loadBookings();
            
            // Refresh every 30 seconds
            setInterval(() => {
                loadStats();
                loadBookings();
            }, 30000);
        });
    </script>
</body>
</html>

