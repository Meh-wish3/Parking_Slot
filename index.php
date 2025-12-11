<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $_SESSION['user_name'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ParkEase - Smart Parking Slot Booking</title>
    <link rel="icon" type="image/png" href="favicon.png">
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
        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        @keyframes glow {
            0%, 100% { box-shadow: 0 0 20px rgba(16, 185, 129, 0.4); }
            50% { box-shadow: 0 0 40px rgba(16, 185, 129, 0.6); }
        }
        @keyframes carPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        @keyframes statusBlink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }
        .animate-fadeIn { animation: fadeIn 0.5s ease-out; }
        .animate-slideIn { animation: slideIn 0.3s ease-out; }
        .animate-pulse-slow { animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
        .animate-float { animation: float 3s ease-in-out infinite; }
        .animate-glow { animation: glow 2s ease-in-out infinite; }
        .animate-car-pulse { animation: carPulse 2s ease-in-out infinite; }
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
        /* Hero slot cards */
        .hero-slot {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .hero-slot:hover {
            transform: translateY(-5px) scale(1.05);
        }
        .hero-slot-available {
            background: linear-gradient(135deg, #10b981 0%, #059669 50%, #047857 100%);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
        }
        .hero-slot-available:hover {
            box-shadow: 0 15px 40px rgba(16, 185, 129, 0.5);
        }
        .hero-slot-occupied {
            background: linear-gradient(135deg, #f87171 0%, #ef4444 50%, #dc2626 100%);
            box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4);
        }
        .hero-slot-booking {
            background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 50%, #2563eb 100%);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
        }
        /* Shimmer effect */
        .shimmer {
            position: relative;
            overflow: hidden;
        }
        .shimmer::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            animation: shimmer 2s infinite;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-50 min-h-screen" data-logged-in="<?php echo $isLoggedIn ? 'true' : 'false'; ?>">
    
    <!-- Navbar -->
    <nav id="navbar" class="fixed top-0 w-full z-50 transition-all duration-300 glass" role="navigation" aria-label="Main navigation">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 md:h-20">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="#home" class="flex items-center gap-2">
                        <img src="favicon.png" alt="ParkEase Logo" class="w-10 h-10 rounded-lg">
                        <span class="text-2xl md:text-3xl font-bold bg-gradient-to-r from-teal-500 to-blue-600 bg-clip-text text-transparent">ParkEase</span>
                    </a>
                </div>
                
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#home" class="text-gray-700 hover:text-teal-600 transition-colors font-medium">Home</a>
                    <a href="#book" class="text-gray-700 hover:text-teal-600 transition-colors font-medium">Book Slot</a>
                    <a href="#pricing" class="text-gray-700 hover:text-teal-600 transition-colors font-medium">Pricing</a>
                    <a href="#contact" class="text-gray-700 hover:text-teal-600 transition-colors font-medium">Contact</a>
                </div>
                
                <!-- Auth Buttons -->
                <div class="hidden md:flex items-center space-x-4">
                    <?php if ($isLoggedIn): ?>
                        <span class="text-gray-700 font-medium">
                            <i class="fas fa-user-circle mr-1"></i><?php echo htmlspecialchars($userName); ?>
                        </span>
                        <a href="#" onclick="logout()" class="px-6 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-full hover:shadow-lg transform hover:scale-105 transition-all duration-200 font-medium">
                            Logout
                        </a>
                    <?php else: ?>
                        <a href="login.php" class="px-4 py-2 text-gray-700 hover:text-teal-600 transition-colors font-medium">Login</a>
                        <a href="signup.php" class="px-6 py-2 bg-gradient-to-r from-teal-500 to-blue-600 text-white rounded-full hover:shadow-lg transform hover:scale-105 transition-all duration-200 font-medium">
                            Register
                        </a>
                    <?php endif; ?>
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
                <a href="#contact" class="block px-3 py-2 text-white rounded-lg hover:bg-white/10 transition-colors">Contact</a>
                <div class="pt-4 border-t border-gray-300">
                    <?php if ($isLoggedIn): ?>
                        <div class="px-3 py-2 text-white font-medium">
                            <i class="fas fa-user-circle mr-1"></i><?php echo htmlspecialchars($userName); ?>
                        </div>
                        <a href="#" onclick="logout()" class="block w-full px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:shadow-lg transition-all text-center">Logout</a>
                    <?php else: ?>
                        <a href="login.php" class="block w-full px-4 py-2 text-white mb-2 rounded-lg hover:bg-white/10 transition-colors text-center">Login</a>
                        <a href="signup.php" class="block w-full px-4 py-2 bg-gradient-to-r from-teal-500 to-blue-600 text-white rounded-lg hover:shadow-lg transition-all text-center">Register</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Hero Section -->
    <section id="home" class="pt-24 md:pt-32 pb-16 md:pb-24 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100"></div>
        
        <div class="container mx-auto max-w-7xl relative z-10">
            <div class="grid md:grid-cols-2 gap-8 md:gap-12 items-center">
                <!-- Left Content -->
                <div class="text-center md:text-left animate-fadeIn">
                    <div class="inline-flex items-center px-4 py-2 bg-teal-100 text-teal-700 rounded-full text-sm font-semibold mb-6">
                        <i class="fas fa-parking mr-2"></i>Smart Parking Solution
                    </div>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 mb-6 leading-tight">
                        Find & Book Your
                        <span class="block bg-gradient-to-r from-teal-500 to-blue-600 bg-clip-text text-transparent">Parking Spot</span>
                        <span class="block text-3xl md:text-4xl lg:text-5xl mt-2">Instantly!</span>
                    </h1>
                    <p class="text-lg md:text-xl text-gray-600 mb-8 leading-relaxed">
                        No more circling for parking. Reserve your spot in advance, save time, and enjoy hassle-free parking.
                    </p>
                    
                    <!-- Stats Row -->
                    <div class="grid grid-cols-3 gap-4 mb-8">
                        <div class="text-center md:text-left">
                            <div class="text-2xl md:text-3xl font-bold text-teal-600">500+</div>
                            <div class="text-sm text-gray-500">Parking Spots</div>
                        </div>
                        <div class="text-center md:text-left">
                            <div class="text-2xl md:text-3xl font-bold text-green-600">24/7</div>
                            <div class="text-sm text-gray-500">Available</div>
                        </div>
                        <div class="text-center md:text-left">
                            <div class="text-2xl md:text-3xl font-bold text-blue-600">1000+</div>
                            <div class="text-sm text-gray-500">Happy Users</div>
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                        <a href="#book" class="px-8 py-4 bg-gradient-to-r from-teal-500 to-blue-600 text-white rounded-2xl hover:shadow-2xl transform hover:scale-105 transition-all duration-200 font-semibold text-lg">
                            <i class="fas fa-car mr-2"></i>Book Parking Now
                        </a>
                        <a href="#how-it-works" class="px-8 py-4 bg-white text-gray-700 rounded-2xl hover:shadow-xl transform hover:scale-105 transition-all duration-200 font-semibold text-lg border-2 border-gray-200">
                            <i class="fas fa-play-circle mr-2"></i>How It Works
                        </a>
                    </div>
                </div>
                
                <!-- Right Illustration - Parking Lot Visual -->
                <div class="hidden md:block animate-fadeIn" style="animation-delay: 0.2s;">
                    <div class="relative">
                        <!-- Main Parking Lot Visual -->
                        <div class="relative bg-gradient-to-br from-gray-800 to-gray-900 rounded-3xl p-8 shadow-2xl">
                            <!-- Parking Header -->
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-parking text-white text-lg"></i>
                                    </div>
                                    <div>
                                        <div class="text-white font-bold">ParkEase Lot</div>
                                        <div class="text-gray-400 text-sm">Level 1 - Zone A</div>
                                    </div>
                                </div>
                                <div class="text-green-400 text-sm font-semibold">
                                    <i class="fas fa-circle text-xs mr-1 animate-pulse"></i> Live
                                </div>
                            </div>
                            
                            <!-- Parking Grid -->
                            <div class="grid grid-cols-3 gap-4 mb-4">
                                <!-- Occupied Slot P01 -->
                                <div class="hero-slot hero-slot-occupied aspect-square rounded-2xl flex flex-col items-center justify-center text-white relative overflow-hidden group">
                                    <!-- Shimmer overlay -->
                                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                                    <!-- Inner glow -->
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                                    <!-- Content -->
                                    <div class="relative z-10 flex flex-col items-center">
                                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mb-2 backdrop-blur-sm animate-car-pulse">
                                            <i class="fas fa-car text-2xl drop-shadow-lg"></i>
                                        </div>
                                        <span class="font-bold text-lg tracking-wide">P01</span>
                                        <span class="text-xs opacity-80 mt-1">Occupied</span>
                                    </div>
                                    <!-- Status indicator -->
                                    <div class="absolute top-2 right-2 flex items-center gap-1">
                                        <span class="w-2 h-2 bg-red-300 rounded-full"></span>
                                    </div>
                                    <!-- Bottom accent line -->
                                    <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-red-300 via-red-200 to-red-300"></div>
                                </div>
                                
                                <!-- Available Slot P02 -->
                                <div class="hero-slot hero-slot-available aspect-square rounded-2xl flex flex-col items-center justify-center text-white relative overflow-hidden group animate-float" style="animation-delay: 0.2s;">
                                    <!-- Shimmer overlay -->
                                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                                    <!-- Glow effect -->
                                    <div class="absolute inset-0 animate-glow rounded-2xl"></div>
                                    <!-- Inner glow -->
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                                    <!-- Content -->
                                    <div class="relative z-10 flex flex-col items-center">
                                        <div class="w-12 h-12 bg-white/25 rounded-xl flex items-center justify-center mb-2 backdrop-blur-sm border-2 border-dashed border-white/50">
                                            <i class="fas fa-parking text-2xl drop-shadow-lg"></i>
                                        </div>
                                        <span class="font-bold text-lg tracking-wide">P02</span>
                                        <span class="text-xs opacity-90 mt-1 bg-white/20 px-2 py-0.5 rounded-full">Available</span>
                                    </div>
                                    <!-- Status indicator with ping -->
                                    <div class="absolute top-2 right-2">
                                        <span class="flex h-3 w-3">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-200 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-3 w-3 bg-green-300"></span>
                                        </span>
                                    </div>
                                    <!-- Bottom accent line -->
                                    <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-green-300 via-emerald-200 to-green-300"></div>
                                </div>
                                
                                <!-- Occupied Slot P03 -->
                                <div class="hero-slot hero-slot-occupied aspect-square rounded-2xl flex flex-col items-center justify-center text-white relative overflow-hidden group">
                                    <!-- Shimmer overlay -->
                                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                                    <!-- Inner glow -->
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                                    <!-- Content -->
                                    <div class="relative z-10 flex flex-col items-center">
                                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mb-2 backdrop-blur-sm">
                                            <i class="fas fa-car text-2xl drop-shadow-lg animate-car-pulse"></i>
                                        </div>
                                        <span class="font-bold text-lg tracking-wide">P03</span>
                                        <span class="text-xs opacity-80 mt-1">Occupied</span>
                                    </div>
                                    <!-- Status indicator -->
                                    <div class="absolute top-2 right-2 flex items-center gap-1">
                                        <span class="w-2 h-2 bg-red-300 rounded-full"></span>
                                    </div>
                                    <!-- Bottom accent line -->
                                    <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-red-300 via-red-200 to-red-300"></div>
                                </div>
                                
                                <!-- Available Slot P04 -->
                                <div class="hero-slot hero-slot-available aspect-square rounded-2xl flex flex-col items-center justify-center text-white relative overflow-hidden group animate-float" style="animation-delay: 0.4s;">
                                    <!-- Shimmer overlay -->
                                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                                    <!-- Glow effect -->
                                    <div class="absolute inset-0 animate-glow rounded-2xl"></div>
                                    <!-- Inner glow -->
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                                    <!-- Content -->
                                    <div class="relative z-10 flex flex-col items-center">
                                        <div class="w-12 h-12 bg-white/25 rounded-xl flex items-center justify-center mb-2 backdrop-blur-sm border-2 border-dashed border-white/50">
                                            <i class="fas fa-parking text-2xl drop-shadow-lg"></i>
                                        </div>
                                        <span class="font-bold text-lg tracking-wide">P04</span>
                                        <span class="text-xs opacity-90 mt-1 bg-white/20 px-2 py-0.5 rounded-full">Available</span>
                                    </div>
                                    <!-- Status indicator with ping -->
                                    <div class="absolute top-2 right-2">
                                        <span class="flex h-3 w-3">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-200 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-3 w-3 bg-green-300"></span>
                                        </span>
                                    </div>
                                    <!-- Bottom accent line -->
                                    <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-green-300 via-emerald-200 to-green-300"></div>
                                </div>
                                
                                <!-- Booking Slot P05 -->
                                <div class="hero-slot hero-slot-booking aspect-square rounded-2xl flex flex-col items-center justify-center text-white relative overflow-hidden group">
                                    <!-- Animated gradient background -->
                                    <div class="absolute inset-0 bg-gradient-to-br from-blue-400/50 to-purple-500/50 animate-pulse"></div>
                                    <!-- Shimmer overlay -->
                                    <div class="absolute inset-0 shimmer"></div>
                                    <!-- Inner glow -->
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                                    <!-- Content -->
                                    <div class="relative z-10 flex flex-col items-center">
                                        <div class="w-12 h-12 bg-white/25 rounded-xl flex items-center justify-center mb-2 backdrop-blur-sm">
                                            <i class="fas fa-spinner fa-spin text-2xl drop-shadow-lg"></i>
                                        </div>
                                        <span class="font-bold text-lg tracking-wide">P05</span>
                                        <span class="text-xs mt-1 bg-white/30 px-2 py-0.5 rounded-full animate-pulse">Booking...</span>
                                    </div>
                                    <!-- Status indicator with ping -->
                                    <div class="absolute top-2 right-2">
                                        <span class="flex h-3 w-3">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-200 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-3 w-3 bg-blue-300"></span>
                                        </span>
                                    </div>
                                    <!-- Bottom accent line -->
                                    <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-300 via-indigo-200 to-blue-300"></div>
                                </div>
                                
                                <!-- Available Slot P06 -->
                                <div class="hero-slot hero-slot-available aspect-square rounded-2xl flex flex-col items-center justify-center text-white relative overflow-hidden group animate-float" style="animation-delay: 0.6s;">
                                    <!-- Shimmer overlay -->
                                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                                    <!-- Glow effect -->
                                    <div class="absolute inset-0 animate-glow rounded-2xl"></div>
                                    <!-- Inner glow -->
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                                    <!-- Content -->
                                    <div class="relative z-10 flex flex-col items-center">
                                        <div class="w-12 h-12 bg-white/25 rounded-xl flex items-center justify-center mb-2 backdrop-blur-sm border-2 border-dashed border-white/50">
                                            <i class="fas fa-parking text-2xl drop-shadow-lg"></i>
                                        </div>
                                        <span class="font-bold text-lg tracking-wide">P06</span>
                                        <span class="text-xs opacity-90 mt-1 bg-white/20 px-2 py-0.5 rounded-full">Available</span>
                                    </div>
                                    <!-- Status indicator with ping -->
                                    <div class="absolute top-2 right-2">
                                        <span class="flex h-3 w-3">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-200 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-3 w-3 bg-green-300"></span>
                                        </span>
                                    </div>
                                    <!-- Bottom accent line -->
                                    <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-green-300 via-emerald-200 to-green-300"></div>
                                </div>
                            </div>
                            
                            <!-- Legend -->
                            <div class="flex justify-center gap-6 text-sm">
                                <div class="flex items-center text-gray-300">
                                    <div class="w-3 h-3 bg-green-500 rounded mr-2"></div>Available
                                </div>
                                <div class="flex items-center text-gray-300">
                                    <div class="w-3 h-3 bg-red-500 rounded mr-2"></div>Occupied
                                </div>
                                <div class="flex items-center text-gray-300">
                                    <div class="w-3 h-3 bg-blue-500 rounded mr-2"></div>Reserved
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- How It Works Section -->
    <section id="how-it-works" class="py-16 px-4 sm:px-6 lg:px-8 bg-white">
        <div class="container mx-auto max-w-6xl">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">How It Works</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Book your parking spot in just 3 simple steps</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Step 1 -->
                <div class="relative glass rounded-2xl p-8 text-center hover:shadow-xl transition-all duration-300 group">
                    <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 w-8 h-8 bg-gradient-to-r from-teal-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold shadow-lg">1</div>
                    <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-blue-100 to-blue-200 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-calendar-alt text-3xl text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Select Date & Time</h3>
                    <p class="text-gray-600">Choose your preferred check-in and check-out times</p>
                </div>
                
                <!-- Step 2 -->
                <div class="relative glass rounded-2xl p-8 text-center hover:shadow-xl transition-all duration-300 group">
                    <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 w-8 h-8 bg-gradient-to-r from-teal-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold shadow-lg">2</div>
                    <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-green-100 to-green-200 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-map-marker-alt text-3xl text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Choose Your Spot</h3>
                    <p class="text-gray-600">Pick from available parking slots on the live map</p>
                </div>
                
                <!-- Step 3 -->
                <div class="relative glass rounded-2xl p-8 text-center hover:shadow-xl transition-all duration-300 group">
                    <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 w-8 h-8 bg-gradient-to-r from-teal-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold shadow-lg">3</div>
                    <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-blue-100 to-blue-200 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-car text-3xl text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Park & Go!</h3>
                    <p class="text-gray-600">Get confirmation and drive straight to your reserved spot</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Quick Booking Bar -->
    <section id="book" class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="container mx-auto max-w-6xl">
            <div class="glass rounded-2xl p-6 md:p-8 shadow-xl">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-6 text-center">Quick Booking</h2>
                <form id="quickBookingForm" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    <div>
                        <label for="bookingDate" class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                        <input type="date" id="bookingDate" name="date" required 
                               class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:outline-none transition-colors"
                               min="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div>
                        <label for="checkInTime" class="block text-sm font-medium text-gray-700 mb-2">Check-in Time</label>
                        <input type="time" id="checkInTime" name="check_in_time" required
                               class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:outline-none transition-colors">
                    </div>
                    <div>
                        <label for="checkOutTime" class="block text-sm font-medium text-gray-700 mb-2">Check-out Time</label>
                        <input type="time" id="checkOutTime" name="check_out_time" required
                               class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:outline-none transition-colors">
                    </div>
                    <div>
                        <label for="vehicleNumber" class="block text-sm font-medium text-gray-700 mb-2">Vehicle Number</label>
                        <input type="text" id="vehicleNumber" name="vehicle_number" placeholder="ABC-1234" required
                               class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:outline-none transition-colors">
                    </div>
                    <div class="md:col-span-2 flex items-end">
                        <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-teal-500 to-blue-600 text-white rounded-xl hover:shadow-lg transform hover:scale-105 transition-all duration-200 font-semibold">
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
            <div id="loadingSlots" class="text-center py-12 hidden">
                <div class="inline-block animate-pulse-slow">
                    <i class="fas fa-spinner fa-spin text-4xl text-blue-600"></i>
                    <p class="mt-4 text-gray-600">Loading slots...</p>
                </div>
            </div>
            <div id="slotsGrid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 md:gap-6 hidden">
                <!-- Slots will be dynamically inserted here -->
            </div>
            <div id="noSlotsMessage" class="text-center py-12">
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
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                        <div class="px-4 py-3 rounded-xl bg-gray-100 font-semibold text-gray-900" id="modalDateDisplay"></div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Check-in</label>
                            <div class="px-4 py-3 rounded-xl bg-gray-100 font-semibold text-gray-900" id="modalCheckInDisplay"></div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Check-out</label>
                            <div class="px-4 py-3 rounded-xl bg-gray-100 font-semibold text-gray-900" id="modalCheckOutDisplay"></div>
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
                    <div class="text-4xl font-bold bg-gradient-to-r from-teal-500 to-blue-600 bg-clip-text text-transparent mb-4">₹50<span class="text-lg text-gray-600">/hour</span></div>
                    <ul class="space-y-3 mb-6">
                        <li class="flex items-center text-gray-700"><i class="fas fa-check text-green-500 mr-2"></i>Flexible timing</li>
                        <li class="flex items-center text-gray-700"><i class="fas fa-check text-green-500 mr-2"></i>Easy booking</li>
                        <li class="flex items-center text-gray-700"><i class="fas fa-check text-green-500 mr-2"></i>Secure parking</li>
                    </ul>
                </div>
                <div class="relative glass rounded-2xl p-8 shadow-xl hover:shadow-2xl transform hover:scale-105 transition-all duration-200 border-4 border-teal-500">
                    <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 bg-gradient-to-r from-teal-500 to-blue-600 text-white px-4 py-1 rounded-full text-sm font-semibold">Popular</div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Daily</h3>
                    <div class="text-4xl font-bold bg-gradient-to-r from-teal-500 to-blue-600 bg-clip-text text-transparent mb-4">₹100<span class="text-lg text-gray-600">/day</span></div>
                    <ul class="space-y-3 mb-6">
                        <li class="flex items-center text-gray-700"><i class="fas fa-check text-green-500 mr-2"></i>24/7 access</li>
                        <li class="flex items-center text-gray-700"><i class="fas fa-check text-green-500 mr-2"></i>Best value</li>
                        <li class="flex items-center text-gray-700"><i class="fas fa-check text-green-500 mr-2"></i>Priority support</li>
                    </ul>
                </div>
                <div class="glass rounded-2xl p-8 shadow-xl hover:shadow-2xl transform hover:scale-105 transition-all duration-200">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Monthly</h3>
                    <div class="text-4xl font-bold bg-gradient-to-r from-teal-500 to-blue-600 bg-clip-text text-transparent mb-4">₹500<span class="text-lg text-gray-600">/month</span></div>
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
                    <h3 class="text-2xl font-bold mb-4 bg-gradient-to-r from-teal-400 to-blue-400 bg-clip-text text-transparent">
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
                        <li><a href="#contact" class="hover:text-white transition-colors">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Contact</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><i class="fas fa-phone mr-2"></i>+91 123XXXXXXX</li>
                        <li><i class="fas fa-envelope mr-2"></i>info@parkease.com</li>
                        <li><i class="fas fa-map-marker-alt mr-2"></i>Guwahati, Assam</li>
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
                <p>&copy; 2025 ParkEase. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
    <script src="script.js"></script>
</body>
</html>

