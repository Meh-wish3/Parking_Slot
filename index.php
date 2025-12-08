<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ParkEase - Smart Parking Slot Booking</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideIn {
            from { transform: translateX(-100%); }
            to { transform: translateX(0); }
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .animate-fadeIn { animation: fadeIn 0.5s ease-out; }
        .animate-slideIn { animation: slideIn 0.3s ease-out; }
        .animate-pulse-slow { animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        .glass-dark {
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 min-h-screen">
    
    <!-- Navbar -->
    <nav id="navbar" class="fixed top-0 w-full z-50 transition-all duration-300 glass" role="navigation" aria-label="Main navigation">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 md:h-20">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="#home" class="text-2xl md:text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                        <i class="fas fa-parking mr-2"></i>ParkEase
                    </a>
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#home" class="text-gray-700 hover:text-blue-600 transition-colors font-medium">Home</a>
                    <a href="#book" class="text-gray-700 hover:text-blue-600 transition-colors font-medium">Book Slot</a>
                    <a href="#pricing" class="text-gray-700 hover:text-blue-600 transition-colors font-medium">Pricing</a>
                    <a href="admin.php" class="text-gray-700 hover:text-blue-600 transition-colors font-medium">Admin</a>
                    <a href="#contact" class="text-gray-700 hover:text-blue-600 transition-colors font-medium">Contact</a>
                </div>
                
                <!-- Auth Buttons -->
                <div class="hidden md:flex items-center space-x-4">
                    <button class="px-4 py-2 text-gray-700 hover:text-blue-600 transition-colors font-medium">Login</button>
                    <button class="px-6 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-full hover:shadow-lg transform hover:scale-105 transition-all duration-200 font-medium">
                        Register
                    </button>
                </div>
                
                <!-- Mobile Menu Button -->
                <button id="mobileMenuBtn" class="md:hidden text-gray-700 focus:outline-none" aria-label="Toggle mobile menu" aria-expanded="false">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden md:hidden glass-dark border-t border-gray-200">
            <div class="px-4 pt-2 pb-4 space-y-2">
                <a href="#home" class="block px-3 py-2 text-white rounded-lg hover:bg-white/10 transition-colors">Home</a>
                <a href="#book" class="block px-3 py-2 text-white rounded-lg hover:bg-white/10 transition-colors">Book Slot</a>
                <a href="#pricing" class="block px-3 py-2 text-white rounded-lg hover:bg-white/10 transition-colors">Pricing</a>
                <a href="admin.php" class="block px-3 py-2 text-white rounded-lg hover:bg-white/10 transition-colors">Admin</a>
                <a href="#contact" class="block px-3 py-2 text-white rounded-lg hover:bg-white/10 transition-colors">Contact</a>
                <div class="pt-4 border-t border-gray-300">
                    <button class="w-full px-4 py-2 text-white mb-2 rounded-lg hover:bg-white/10 transition-colors">Login</button>
                    <button class="w-full px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:shadow-lg transition-all">Register</button>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Hero Section -->
    <section id="home" class="pt-24 md:pt-32 pb-16 md:pb-24 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
        <div class="absolute inset-0 bg-grid-pattern opacity-5"></div>
        <div class="container mx-auto max-w-7xl">
            <div class="grid md:grid-cols-2 gap-8 md:gap-12 items-center">
                <!-- Left Content -->
                <div class="text-center md:text-left animate-fadeIn">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 mb-6 leading-tight">
                        Book Your Parking Slot
                        <span class="block bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">in Seconds</span>
                    </h1>
                    <p class="text-lg md:text-xl text-gray-600 mb-8 leading-relaxed">
                        Smart, fast and secure parking slot booking system
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                        <a href="#book" class="px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-2xl hover:shadow-2xl transform hover:scale-105 transition-all duration-200 font-semibold text-lg">
                            <i class="fas fa-search mr-2"></i>Find Parking
                        </a>
                        <a href="admin.php" class="px-8 py-4 bg-white text-gray-700 rounded-2xl hover:shadow-xl transform hover:scale-105 transition-all duration-200 font-semibold text-lg border-2 border-gray-200">
                            <i class="fas fa-tachometer-alt mr-2"></i>Admin Panel
                        </a>
                    </div>
                </div>
                
                <!-- Right Illustration -->
                <div class="hidden md:block animate-fadeIn" style="animation-delay: 0.2s;">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-400 to-purple-400 rounded-3xl transform rotate-6 opacity-20"></div>
                        <div class="relative bg-white/80 backdrop-blur-sm rounded-3xl p-8 shadow-2xl">
                            <div class="grid grid-cols-3 gap-4">
                                <div class="aspect-square bg-gradient-to-br from-green-400 to-green-600 rounded-2xl flex items-center justify-center text-white text-2xl font-bold shadow-lg">
                                    P01
                                </div>
                                <div class="aspect-square bg-gradient-to-br from-red-400 to-red-600 rounded-2xl flex items-center justify-center text-white text-2xl font-bold shadow-lg">
                                    P02
                                </div>
                                <div class="aspect-square bg-gradient-to-br from-green-400 to-green-600 rounded-2xl flex items-center justify-center text-white text-2xl font-bold shadow-lg">
                                    P03
                                </div>
                                <div class="aspect-square bg-gradient-to-br from-green-400 to-green-600 rounded-2xl flex items-center justify-center text-white text-2xl font-bold shadow-lg">
                                    P04
                                </div>
                                <div class="aspect-square bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center text-white text-2xl font-bold shadow-lg">
                                    P05
                                </div>
                                <div class="aspect-square bg-gradient-to-br from-green-400 to-green-600 rounded-2xl flex items-center justify-center text-white text-2xl font-bold shadow-lg">
                                    P06
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Quick Booking Bar -->
    <section id="book" class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="container mx-auto max-w-6xl">
            <div class="glass rounded-2xl p-6 md:p-8 shadow-xl">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-6 text-center">Quick Booking</h2>
                <form id="quickBookingForm" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="bookingDate" class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                        <input type="date" id="bookingDate" name="date" required 
                               class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:outline-none transition-colors"
                               min="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div>
                        <label for="bookingTime" class="block text-sm font-medium text-gray-700 mb-2">Time</label>
                        <input type="time" id="bookingTime" name="time" required
                               class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:outline-none transition-colors">
                    </div>
                    <div>
                        <label for="vehicleNumber" class="block text-sm font-medium text-gray-700 mb-2">Vehicle Number</label>
                        <input type="text" id="vehicleNumber" name="vehicle_number" placeholder="ABC-1234" required
                               class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:outline-none transition-colors">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:shadow-lg transform hover:scale-105 transition-all duration-200 font-semibold">
                            <i class="fas fa-search mr-2"></i>Check Availability
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    
    <!-- Parking Slot Grid -->
    <section class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="container mx-auto max-w-6xl">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-8 text-center">Available Parking Slots</h2>
            <div id="loadingSlots" class="text-center py-12">
                <div class="inline-block animate-pulse-slow">
                    <i class="fas fa-spinner fa-spin text-4xl text-blue-600"></i>
                    <p class="mt-4 text-gray-600">Loading slots...</p>
                </div>
            </div>
            <div id="slotsGrid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 md:gap-6 hidden">
                <!-- Slots will be dynamically inserted here -->
            </div>
            <div id="noSlotsMessage" class="text-center py-12 hidden">
                <i class="fas fa-info-circle text-4xl text-gray-400 mb-4"></i>
                <p class="text-gray-600 text-lg">Please select date and time to check availability</p>
            </div>
        </div>
    </section>
    
    <!-- Booking Modal -->
    <div id="bookingModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" onclick="closeBookingModal()"></div>
        <div class="relative glass rounded-2xl shadow-2xl max-w-md w-full p-6 md:p-8 transform transition-all">
            <button onclick="closeBookingModal()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 transition-colors" aria-label="Close modal">
                <i class="fas fa-times text-2xl"></i>
            </button>
            <h3 id="modal-title" class="text-2xl font-bold text-gray-900 mb-6">Confirm Booking</h3>
            <form id="bookingForm">
                <input type="hidden" id="modalSlotId" name="slot_id">
                <input type="hidden" id="modalSlotNumber" name="slot_number">
                <div class="space-y-4">
                    <div>
                        <label for="modalName" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                        <input type="text" id="modalName" name="name" required
                               class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:outline-none transition-colors">
                    </div>
                    <div>
                        <label for="modalVehicle" class="block text-sm font-medium text-gray-700 mb-2">Vehicle Number</label>
                        <input type="text" id="modalVehicle" name="vehicle_number" required
                               class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:outline-none transition-colors">
                    </div>
                    <div>
                        <label for="modalPhone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input type="tel" id="modalPhone" name="phone_number" required
                               class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:outline-none transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Selected Slot</label>
                        <div class="px-4 py-3 rounded-xl bg-gray-100 font-semibold text-gray-900" id="modalSlotDisplay"></div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                            <div class="px-4 py-3 rounded-xl bg-gray-100 font-semibold text-gray-900" id="modalDateDisplay"></div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Time</label>
                            <div class="px-4 py-3 rounded-xl bg-gray-100 font-semibold text-gray-900" id="modalTimeDisplay"></div>
                        </div>
                    </div>
                    <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:shadow-lg transform hover:scale-105 transition-all duration-200 font-semibold mt-6">
                        <i class="fas fa-check mr-2"></i>Confirm Booking
                    </button>
                </div>
            </form>
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
    
    <!-- Pricing Section -->
    <section id="pricing" class="py-16 px-4 sm:px-6 lg:px-8 bg-white/50">
        <div class="container mx-auto max-w-6xl">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-12 text-center">Pricing Plans</h2>
            <div class="grid md:grid-cols-3 gap-6 md:gap-8">
                <div class="glass rounded-2xl p-8 shadow-xl hover:shadow-2xl transform hover:scale-105 transition-all duration-200">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Hourly</h3>
                    <div class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-4">$5<span class="text-lg text-gray-600">/hour</span></div>
                    <ul class="space-y-3 mb-6">
                        <li class="flex items-center text-gray-700"><i class="fas fa-check text-green-500 mr-2"></i>Flexible timing</li>
                        <li class="flex items-center text-gray-700"><i class="fas fa-check text-green-500 mr-2"></i>Easy booking</li>
                        <li class="flex items-center text-gray-700"><i class="fas fa-check text-green-500 mr-2"></i>Secure parking</li>
                    </ul>
                </div>
                <div class="relative glass rounded-2xl p-8 shadow-xl hover:shadow-2xl transform hover:scale-105 transition-all duration-200 border-4 border-blue-500">
                    <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 bg-gradient-to-r from-blue-600 to-purple-600 text-white px-4 py-1 rounded-full text-sm font-semibold">Popular</div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Daily</h3>
                    <div class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-4">$30<span class="text-lg text-gray-600">/day</span></div>
                    <ul class="space-y-3 mb-6">
                        <li class="flex items-center text-gray-700"><i class="fas fa-check text-green-500 mr-2"></i>24/7 access</li>
                        <li class="flex items-center text-gray-700"><i class="fas fa-check text-green-500 mr-2"></i>Best value</li>
                        <li class="flex items-center text-gray-700"><i class="fas fa-check text-green-500 mr-2"></i>Priority support</li>
                    </ul>
                </div>
                <div class="glass rounded-2xl p-8 shadow-xl hover:shadow-2xl transform hover:scale-105 transition-all duration-200">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Monthly</h3>
                    <div class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-4">$500<span class="text-lg text-gray-600">/month</span></div>
                    <ul class="space-y-3 mb-6">
                        <li class="flex items-center text-gray-700"><i class="fas fa-check text-green-500 mr-2"></i>Unlimited access</li>
                        <li class="flex items-center text-gray-700"><i class="fas fa-check text-green-500 mr-2"></i>Dedicated slot</li>
                        <li class="flex items-center text-gray-700"><i class="fas fa-check text-green-500 mr-2"></i>VIP support</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <footer id="contact" class="bg-gray-900 text-white py-12 px-4 sm:px-6 lg:px-8">
        <div class="container mx-auto max-w-6xl">
            <div class="grid md:grid-cols-3 gap-8 mb-8">
                <div>
                    <h3 class="text-2xl font-bold mb-4 bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent">
                        <i class="fas fa-parking mr-2"></i>ParkEase
                    </h3>
                    <p class="text-gray-400">Smart parking solutions for modern cities.</p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#home" class="hover:text-white transition-colors">Home</a></li>
                        <li><a href="#book" class="hover:text-white transition-colors">Book Slot</a></li>
                        <li><a href="#pricing" class="hover:text-white transition-colors">Pricing</a></li>
                        <li><a href="admin.php" class="hover:text-white transition-colors">Admin</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Contact</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><i class="fas fa-phone mr-2"></i>+1 (555) 123-4567</li>
                        <li><i class="fas fa-envelope mr-2"></i>info@parkease.com</li>
                        <li><i class="fas fa-map-marker-alt mr-2"></i>123 Parking St, City</li>
                    </ul>
                    <div class="flex space-x-4 mt-4">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors" aria-label="Facebook"><i class="fab fa-facebook text-xl"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors" aria-label="Twitter"><i class="fab fa-twitter text-xl"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors" aria-label="Instagram"><i class="fab fa-instagram text-xl"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors" aria-label="LinkedIn"><i class="fab fa-linkedin text-xl"></i></a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 text-center text-gray-400">
                <p>&copy; 2024 ParkEase. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
    <script src="script.js"></script>
</body>
</html>

