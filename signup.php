<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - ParkEase</title>
    <link rel="icon" type="image/png" href="favicon.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        /* Animated gradient background */
        .gradient-bg {
            background: linear-gradient(-45deg, #042f2e, #134e4a, #0f766e, #0d9488);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
        }
        
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        /* Floating particles */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
            z-index: 0;
        }
        
        .particle {
            position: absolute;
            width: 6px;
            height: 6px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 20s infinite;
        }
        
        @keyframes float {
            0%, 100% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% {
                transform: translateY(-100vh) rotate(720deg);
                opacity: 0;
            }
        }
        
        /* Glass card effect */
        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 
                0 8px 32px rgba(0, 0, 0, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }
        
        /* Glowing orbs */
        .glow-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.5;
            animation: pulse 8s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.2); opacity: 0.3; }
        }
        
        /* Form animations */
        @keyframes slideUp {
            from { 
                opacity: 0; 
                transform: translateY(30px); 
            }
            to { 
                opacity: 1; 
                transform: translateY(0); 
            }
        }
        
        .animate-slideUp {
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        
        /* Input styling */
        .custom-input {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        
        .custom-input:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(20, 184, 166, 0.5);
            box-shadow: 0 0 0 4px rgba(20, 184, 166, 0.1);
        }
        
        .custom-input::placeholder {
            color: rgba(255, 255, 255, 0.3);
        }
        
        /* Button gradient */
        .btn-gradient {
            background: linear-gradient(135deg, #14b8a6 0%, #0284c7 100%);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-gradient::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s ease;
        }
        
        .btn-gradient:hover::before {
            left: 100%;
        }
        
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 40px rgba(20, 184, 166, 0.4);
        }
        
        /* Logo animation */
        @keyframes logoGlow {
            0%, 100% { text-shadow: 0 0 20px rgba(20, 184, 166, 0.5); }
            50% { text-shadow: 0 0 40px rgba(6, 182, 212, 0.8); }
        }
        
        .logo-glow {
            animation: logoGlow 3s ease-in-out infinite;
        }
        
        /* Floating shapes */
        .floating-shape {
            position: absolute;
            opacity: 0.1;
            animation: floatShape 20s ease-in-out infinite;
        }
        
        @keyframes floatShape {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-30px) rotate(180deg); }
        }
        
        /* Password toggle */
        .password-toggle {
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .password-toggle:hover {
            color: rgba(255, 255, 255, 0.8);
        }
        
        /* Link hover effect */
        .link-hover {
            position: relative;
        }
        
        .link-hover::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #14b8a6, #0284c7);
            transition: width 0.3s ease;
        }
        
        .link-hover:hover::after {
            width: 100%;
        }
        
        /* Success animation */
        @keyframes successPulse {
            0% { transform: scale(0); opacity: 0; }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); opacity: 1; }
        }
        
        .success-animation {
            animation: successPulse 0.5s ease forwards;
        }
        
        /* Password strength indicator */
        .strength-bar {
            height: 4px;
            border-radius: 2px;
            transition: all 0.3s ease;
        }
        
        /* Scrollbar styling */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }
        
        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4 overflow-x-hidden">
    
    <!-- Animated particles -->
    <div class="particles" id="particles"></div>
    
    <!-- Glowing orbs -->
    <div class="glow-orb w-96 h-96 bg-purple-600" style="top: -10%; right: -10%;"></div>
    <div class="glow-orb w-80 h-80 bg-indigo-600" style="bottom: -10%; left: -10%;"></div>
    <div class="glow-orb w-64 h-64 bg-blue-600" style="top: 40%; right: 50%;"></div>
    
    <!-- Floating shapes -->
    <div class="floating-shape" style="top: 15%; right: 10%;">
        <svg width="50" height="50" viewBox="0 0 50 50" fill="white">
            <polygon points="25,5 45,40 5,40"/>
        </svg>
    </div>
    <div class="floating-shape" style="bottom: 25%; left: 10%; animation-delay: -7s;">
        <svg width="35" height="35" viewBox="0 0 35 35" fill="white">
            <rect width="35" height="35" rx="6"/>
        </svg>
    </div>
    <div class="floating-shape" style="top: 70%; right: 5%; animation-delay: -12s;">
        <svg width="25" height="25" viewBox="0 0 25 25" fill="white">
            <circle cx="12.5" cy="12.5" r="12.5"/>
        </svg>
    </div>
    
    <div class="w-full max-w-md relative z-10 my-8">
        <!-- Logo -->
        <div class="text-center mb-8 animate-slideUp" style="animation-delay: 0.1s; opacity: 0;">
            <a href="index.php" class="inline-flex items-center gap-3 group">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-teal-500 to-blue-600 flex items-center justify-center shadow-lg shadow-teal-500/30 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-parking text-white text-2xl"></i>
                </div>
                <span class="text-4xl font-bold text-white logo-glow">ParkEase</span>
            </a>
            <p class="text-gray-400 mt-4 text-lg">Create your account to start booking!</p>
        </div>
        
        <!-- Signup Form Card -->
        <div class="glass-card rounded-3xl p-8 animate-slideUp" style="animation-delay: 0.2s; opacity: 0;">
            <div class="flex items-center justify-center mb-8">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-1 rounded-full bg-gradient-to-r from-teal-500 to-blue-500"></div>
                    <h2 class="text-2xl font-bold text-white">Create Account</h2>
                    <div class="w-8 h-1 rounded-full bg-gradient-to-r from-blue-500 to-teal-500"></div>
                </div>
            </div>
            
            <form id="signupForm" class="space-y-5">
                <!-- Name Field -->
                <div class="animate-slideUp" style="animation-delay: 0.3s; opacity: 0;">
                    <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                        <i class="fas fa-user mr-2 text-teal-400"></i>Full Name
                    </label>
                    <div class="relative group">
                        <input type="text" id="name" name="name" required
                               class="custom-input w-full px-5 py-4 rounded-xl text-white focus:outline-none"
                               placeholder="Your Full Name">
                        <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-teal-500/20 to-blue-500/20 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>
                    </div>
                </div>
                
                <!-- Email Field -->
                <div class="animate-slideUp" style="animation-delay: 0.35s; opacity: 0;">
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                        <i class="fas fa-envelope mr-2 text-teal-400"></i>Email Address
                    </label>
                    <div class="relative group">
                        <input type="email" id="email" name="email" required
                               class="custom-input w-full px-5 py-4 rounded-xl text-white focus:outline-none"
                               placeholder="Your Email Address">
                        <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-teal-500/20 to-blue-500/20 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>
                    </div>
                </div>
                
                <!-- Phone Field -->
                <div class="animate-slideUp" style="animation-delay: 0.4s; opacity: 0;">
                    <label for="phone" class="block text-sm font-medium text-gray-300 mb-2">
                        <i class="fas fa-phone mr-2 text-teal-400"></i>Phone Number
                    </label>
                    <div class="relative group">
                        <input type="tel" id="phone" name="phone"
                               class="custom-input w-full px-5 py-4 rounded-xl text-white focus:outline-none"
                               placeholder="Your Phone Number">
                        <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-teal-500/20 to-blue-500/20 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>
                    </div>
                </div>
                
                <!-- Password Field -->
                <div class="animate-slideUp" style="animation-delay: 0.45s; opacity: 0;">
                    <label for="password" class="block text-sm font-medium text-gray-300 mb-2">
                        <i class="fas fa-lock mr-2 text-teal-400"></i>Password
                    </label>
                    <div class="relative group">
                        <input type="password" id="password" name="password" required minlength="6"
                               class="custom-input w-full px-5 py-4 rounded-xl text-white focus:outline-none pr-12"
                               placeholder="••••••••">
                        <button type="button" class="password-toggle absolute right-4 top-1/2 -translate-y-1/2 text-gray-500" data-target="password">
                            <i class="fas fa-eye"></i>
                        </button>
                        <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-teal-500/20 to-blue-500/20 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>
                    </div>
                    <!-- Password strength indicator -->
                    <div class="mt-2 flex gap-1">
                        <div id="strength1" class="strength-bar flex-1 bg-gray-700"></div>
                        <div id="strength2" class="strength-bar flex-1 bg-gray-700"></div>
                        <div id="strength3" class="strength-bar flex-1 bg-gray-700"></div>
                        <div id="strength4" class="strength-bar flex-1 bg-gray-700"></div>
                    </div>
                    <p id="strengthText" class="text-xs text-gray-500 mt-1">Must be at least 6 characters</p>
                </div>
                
                <!-- Confirm Password Field -->
                <div class="animate-slideUp" style="animation-delay: 0.5s; opacity: 0;">
                    <label for="confirm_password" class="block text-sm font-medium text-gray-300 mb-2">
                        <i class="fas fa-lock mr-2 text-teal-400"></i>Confirm Password
                    </label>
                    <div class="relative group">
                        <input type="password" id="confirm_password" name="confirm_password" required
                               class="custom-input w-full px-5 py-4 rounded-xl text-white focus:outline-none pr-12"
                               placeholder="••••••••">
                        <button type="button" class="password-toggle absolute right-4 top-1/2 -translate-y-1/2 text-gray-500" data-target="confirm_password">
                            <i class="fas fa-eye"></i>
                        </button>
                        <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-teal-500/20 to-blue-500/20 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>
                    </div>
                    <p id="matchStatus" class="text-xs mt-1 hidden"></p>
                </div>
                
                <!-- Error Message -->
                <div id="errorMessage" class="hidden bg-red-500/10 border border-red-500/30 text-red-400 px-4 py-3 rounded-xl text-sm backdrop-blur-sm">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span id="errorText"></span>
                </div>
                
                <!-- Submit Button -->
                <div class="animate-slideUp pt-2" style="animation-delay: 0.55s; opacity: 0;">
                    <button type="submit" id="submitBtn"
                            class="btn-gradient w-full px-6 py-4 text-white rounded-xl font-semibold text-lg flex items-center justify-center gap-2">
                        <i class="fas fa-user-plus"></i>
                        <span>Create Account</span>
                    </button>
                </div>
            </form>
            
            <!-- Divider -->
            <div class="flex items-center my-6 animate-slideUp" style="animation-delay: 0.6s; opacity: 0;">
                <div class="flex-1 h-px bg-gradient-to-r from-transparent via-gray-600 to-transparent"></div>
                <span class="px-4 text-gray-500 text-sm">or</span>
                <div class="flex-1 h-px bg-gradient-to-r from-transparent via-gray-600 to-transparent"></div>
            </div>
            
            <!-- Login Link -->
            <div class="text-center animate-slideUp" style="animation-delay: 0.65s; opacity: 0;">
                <p class="text-gray-400">
                    Already have an account? 
                    <a href="login.php" class="text-teal-400 hover:text-teal-300 font-semibold link-hover transition-colors">
                        Login
                    </a>
                </p>
            </div>
        </div>
        
        <!-- Back to Home -->
        <div class="text-center mt-8 animate-slideUp" style="animation-delay: 0.7s; opacity: 0;">
            <a href="index.php" class="text-gray-500 hover:text-white transition-colors inline-flex items-center gap-2 group">
                <i class="fas fa-arrow-left transform group-hover:-translate-x-1 transition-transform"></i>
                <span>Back to Home</span>
            </a>
        </div>
    </div>
    
    <script>
        // Generate particles
        const particlesContainer = document.getElementById('particles');
        for (let i = 0; i < 50; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.animationDelay = Math.random() * 20 + 's';
            particle.style.animationDuration = (15 + Math.random() * 10) + 's';
            particlesContainer.appendChild(particle);
        }
        
        // Password visibility toggle
        document.querySelectorAll('.password-toggle').forEach(toggle => {
            toggle.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });
        });
        
        // Password strength checker
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('confirm_password');
        const strengthBars = [
            document.getElementById('strength1'),
            document.getElementById('strength2'),
            document.getElementById('strength3'),
            document.getElementById('strength4')
        ];
        const strengthText = document.getElementById('strengthText');
        const matchStatus = document.getElementById('matchStatus');
        
        function checkPasswordStrength(password) {
            let strength = 0;
            if (password.length >= 6) strength++;
            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password) && /[a-z]/.test(password)) strength++;
            if (/[0-9]/.test(password) || /[^A-Za-z0-9]/.test(password)) strength++;
            return strength;
        }
        
        function updateStrengthIndicator(strength) {
            const colors = ['bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-green-500'];
            const texts = ['Weak', 'Fair', 'Good', 'Strong'];
            
            strengthBars.forEach((bar, index) => {
                bar.className = 'strength-bar flex-1 rounded-full';
                if (index < strength) {
                    bar.classList.add(colors[strength - 1]);
                } else {
                    bar.classList.add('bg-gray-700');
                }
            });
            
            if (passwordInput.value.length > 0) {
                strengthText.textContent = texts[strength - 1] || 'Too short';
                strengthText.className = 'text-xs mt-1 ' + (strength >= 3 ? 'text-green-400' : strength >= 2 ? 'text-yellow-400' : 'text-red-400');
            } else {
                strengthText.textContent = 'Must be at least 6 characters';
                strengthText.className = 'text-xs text-gray-500 mt-1';
            }
        }
        
        passwordInput.addEventListener('input', function() {
            const strength = checkPasswordStrength(this.value);
            updateStrengthIndicator(strength);
            checkPasswordMatch();
        });
        
        function checkPasswordMatch() {
            if (confirmInput.value.length > 0) {
                matchStatus.classList.remove('hidden');
                if (passwordInput.value === confirmInput.value) {
                    matchStatus.textContent = '✓ Passwords match';
                    matchStatus.className = 'text-xs mt-1 text-green-400';
                } else {
                    matchStatus.textContent = '✗ Passwords do not match';
                    matchStatus.className = 'text-xs mt-1 text-red-400';
                }
            } else {
                matchStatus.classList.add('hidden');
            }
        }
        
        confirmInput.addEventListener('input', checkPasswordMatch);
        
        // Form submission
        document.getElementById('signupForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submitBtn');
            const errorDiv = document.getElementById('errorMessage');
            const errorText = document.getElementById('errorText');
            const originalText = submitBtn.innerHTML;
            
            // Reset error
            errorDiv.classList.add('hidden');
            
            // Client-side validation
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (password !== confirmPassword) {
                errorText.textContent = 'Passwords do not match';
                errorDiv.classList.remove('hidden');
                return;
            }
            
            // Show loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i><span>Creating account...</span>';
            
            const formData = new FormData(this);
            formData.append('action', 'signup');
            
            try {
                const response = await fetch('auth.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Success animation
                    submitBtn.innerHTML = '<i class="fas fa-check success-animation"></i><span>Account Created!</span>';
                    submitBtn.classList.remove('btn-gradient');
                    submitBtn.classList.add('bg-green-500');
                    
                    setTimeout(() => {
                        // Redirect to home page
                        window.location.href = 'index.php';
                    }, 800);
                } else {
                    errorText.textContent = data.message || 'Signup failed. Please try again.';
                    errorDiv.classList.remove('hidden');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            } catch (error) {
                console.error('Error:', error);
                errorText.textContent = 'An error occurred. Please try again.';
                errorDiv.classList.remove('hidden');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
    </script>
</body>
</html>
