// Global state
let selectedSlot = null;
let currentDate = '';
let currentCheckInTime = '';
let currentCheckOutTime = '';

// Initialize on page load
document.addEventListener('DOMContentLoaded', function () {
    initNavbar();
    initQuickBooking();
    initSmoothScroll();
    setMinDate();
});

// Navbar scroll effect
function initNavbar() {
    const navbar = document.getElementById('navbar');
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    let isMenuOpen = false;

    window.addEventListener('scroll', function () {
        if (window.scrollY > 50) {
            navbar.classList.remove('glass');
            navbar.classList.add('bg-white', 'shadow-lg');
        } else {
            navbar.classList.add('glass');
            navbar.classList.remove('bg-white', 'shadow-lg');
        }
    });

    // Mobile menu toggle
    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', function () {
            isMenuOpen = !isMenuOpen;
            mobileMenu.classList.toggle('hidden');
            mobileMenuBtn.setAttribute('aria-expanded', isMenuOpen);

            // Animate icon
            const icon = mobileMenuBtn.querySelector('i');
            if (isMenuOpen) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function (e) {
            if (isMenuOpen && !mobileMenu.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
                mobileMenu.classList.add('hidden');
                isMenuOpen = false;
                mobileMenuBtn.setAttribute('aria-expanded', 'false');
                const icon = mobileMenuBtn.querySelector('i');
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
    }
}

// Set minimum date to today
function setMinDate() {
    const dateInput = document.getElementById('bookingDate');
    if (dateInput) {
        const today = new Date().toISOString().split('T')[0];
        dateInput.setAttribute('min', today);
    }
}

// Quick booking form
function initQuickBooking() {
    const form = document.getElementById('quickBookingForm');
    if (!form) return;

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const date = document.getElementById('bookingDate').value;
        const checkInTime = document.getElementById('checkInTime').value;
        const checkOutTime = document.getElementById('checkOutTime').value;
        const vehicleNumber = document.getElementById('vehicleNumber').value.trim();

        // Validation
        if (!date || !checkInTime || !checkOutTime || !vehicleNumber) {
            showToast('Please fill all fields', 'error');
            return;
        }

        // Validate check-out is after check-in
        if (checkOutTime <= checkInTime) {
            showToast('Check-out time must be after check-in time', 'error');
            return;
        }

        // Validate vehicle number format (basic)
        if (vehicleNumber.length < 3) {
            showToast('Please enter a valid vehicle number', 'error');
            return;
        }

        currentDate = date;
        currentCheckInTime = checkInTime;
        currentCheckOutTime = checkOutTime;

        checkAvailability(date, checkInTime, checkOutTime);
    });
}

// Check slot availability
function checkAvailability(date, checkInTime, checkOutTime) {
    const loadingDiv = document.getElementById('loadingSlots');
    const slotsGrid = document.getElementById('slotsGrid');
    const noSlotsMessage = document.getElementById('noSlotsMessage');

    loadingDiv.classList.remove('hidden');
    slotsGrid.classList.add('hidden');
    noSlotsMessage.classList.add('hidden');

    const formData = new FormData();
    formData.append('action', 'checkAvailability');
    formData.append('date', date);
    formData.append('check_in_time', checkInTime);
    formData.append('check_out_time', checkOutTime);

    fetch('book.php', {
        method: 'POST',
        body: formData
    })
        .then(res => res.json())
        .then(data => {
            loadingDiv.classList.add('hidden');

            if (data.success && data.slots) {
                displaySlots(data.slots, date, checkInTime, checkOutTime);
            } else {
                showToast('Error checking availability', 'error');
                noSlotsMessage.classList.remove('hidden');
            }
        })
        .catch(err => {
            console.error('Error:', err);
            loadingDiv.classList.add('hidden');
            showToast('Error checking availability', 'error');
            noSlotsMessage.classList.remove('hidden');
        });
}

// Display slots in grid
function displaySlots(slots, date, checkInTime, checkOutTime) {
    const slotsGrid = document.getElementById('slotsGrid');
    const noSlotsMessage = document.getElementById('noSlotsMessage');

    slotsGrid.innerHTML = '';

    if (slots.length === 0) {
        noSlotsMessage.classList.remove('hidden');
        return;
    }

    slots.forEach(slot => {
        const isAvailable = slot.is_available;
        const slotCard = createSlotCard(slot, isAvailable, date, checkInTime, checkOutTime);
        slotsGrid.appendChild(slotCard);
    });

    slotsGrid.classList.remove('hidden');
    noSlotsMessage.classList.add('hidden');

    // Smooth scroll to slots
    slotsGrid.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

// Create slot card element
function createSlotCard(slot, isAvailable, date, checkInTime, checkOutTime) {
    const card = document.createElement('div');

    // Enhanced card with 3D effects and animations
    if (isAvailable) {
        card.className = 'slot-card slot-available relative overflow-hidden rounded-3xl p-1 cursor-pointer transform transition-all duration-500 hover:scale-105 hover:-translate-y-2';
        card.style.background = 'linear-gradient(135deg, #10b981, #059669, #047857)';
        card.style.boxShadow = '0 10px 40px rgba(16, 185, 129, 0.3)';
    } else {
        card.className = 'slot-card slot-occupied relative overflow-hidden rounded-3xl p-1 cursor-pointer transform transition-all duration-500 hover:scale-102';
        card.style.background = 'linear-gradient(135deg, #ef4444, #dc2626, #b91c1c)';
        card.style.boxShadow = '0 10px 40px rgba(239, 68, 68, 0.3)';
    }

    const statusText = isAvailable ? 'Available' : 'Occupied';

    card.innerHTML = `
        <!-- Animated background glow -->
        <div class="absolute inset-0 ${isAvailable ? 'bg-gradient-to-br from-green-400/20 to-emerald-600/20' : 'bg-gradient-to-br from-red-400/20 to-rose-600/20'} blur-xl animate-pulse"></div>
        
        <!-- Inner card content -->
        <div class="relative bg-white rounded-[20px] p-5 h-full" style="background: linear-gradient(180deg, #ffffff 0%, ${isAvailable ? '#f0fdf4' : '#fef2f2'} 100%);">
            <!-- Slot number badge -->
            <div class="absolute -top-1 -right-1">
                <div class="${isAvailable ? 'bg-gradient-to-r from-green-500 to-emerald-600' : 'bg-gradient-to-r from-red-500 to-rose-600'} text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">
                    ${slot.slot_number}
                </div>
            </div>
            
            <!-- Animated status indicator -->
            <div class="flex justify-center mb-4">
                <div class="relative">
                    <!-- Outer glow ring -->
                    <div class="absolute inset-0 ${isAvailable ? 'bg-green-400' : 'bg-red-400'} rounded-2xl blur-md opacity-50 ${isAvailable ? 'animate-pulse' : ''}"></div>
                    
                    <!-- Main icon container -->
                    <div class="relative w-20 h-20 ${isAvailable ? 'bg-gradient-to-br from-green-400 via-emerald-500 to-green-600' : 'bg-gradient-to-br from-red-400 via-rose-500 to-red-600'} rounded-2xl flex items-center justify-center shadow-xl transform transition-transform duration-300 hover:rotate-3">
                        <!-- Shine effect -->
                        <div class="absolute inset-0 bg-gradient-to-tr from-white/30 to-transparent rounded-2xl"></div>
                        
                        ${isAvailable ? `
                            <!-- Parking icon with check -->
                            <div class="relative">
                                <i class="fas fa-parking text-white text-3xl"></i>
                                <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-white rounded-full flex items-center justify-center shadow-md">
                                    <i class="fas fa-check text-green-500 text-xs"></i>
                                </div>
                            </div>
                        ` : `
                            <!-- Car icon -->
                            <div class="relative">
                                <i class="fas fa-car text-white text-3xl animate-pulse"></i>
                                <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-white rounded-full flex items-center justify-center shadow-md">
                                    <i class="fas fa-times text-red-500 text-xs"></i>
                                </div>
                            </div>
                        `}
                    </div>
                </div>
            </div>
            
            <!-- Status text with animated bar -->
            <div class="text-center mb-4">
                <div class="flex items-center justify-center gap-2 mb-2">
                    <span class="relative flex h-3 w-3">
                        ${isAvailable ? `
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                        ` : `
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                        `}
                    </span>
                    <span class="font-bold text-lg ${isAvailable ? 'text-green-700' : 'text-red-700'}">${statusText}</span>
                </div>
                
                <!-- Animated progress bar -->
                <div class="w-full h-1.5 bg-gray-200 rounded-full overflow-hidden">
                    <div class="${isAvailable ? 'bg-gradient-to-r from-green-400 to-emerald-500' : 'bg-gradient-to-r from-red-400 to-rose-500'} h-full rounded-full ${isAvailable ? 'w-full' : 'w-full'}" style="animation: ${isAvailable ? 'shimmer 2s infinite' : 'none'}"></div>
                </div>
            </div>
            
            <!-- Action button -->
            ${isAvailable ? `
                <button onclick="openBookingModal('${slot.id}', '${slot.slot_number}', '${date}', '${checkInTime}', '${checkOutTime}')" 
                        class="group relative w-full overflow-hidden rounded-xl bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 px-4 py-3 text-white font-semibold transition-all duration-300 hover:shadow-xl hover:shadow-blue-500/30">
                    <!-- Button shine effect -->
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                    
                    <div class="relative flex items-center justify-center gap-2">
                        <i class="fas fa-bolt text-yellow-300"></i>
                        <span>Book Now</span>
                        <i class="fas fa-arrow-right transform group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </button>
            ` : `
                <div class="relative w-full rounded-xl bg-gray-100 px-4 py-3 text-center">
                    <div class="flex items-center justify-center gap-2 text-gray-500 font-medium">
                        <i class="fas fa-clock"></i>
                        <span>Currently in Use</span>
                    </div>
                </div>
            `}
        </div>
    `;

    // Add hover effects via JavaScript
    card.addEventListener('mouseenter', function () {
        if (isAvailable) {
            this.style.boxShadow = '0 20px 60px rgba(16, 185, 129, 0.4)';
        }
    });

    card.addEventListener('mouseleave', function () {
        if (isAvailable) {
            this.style.boxShadow = '0 10px 40px rgba(16, 185, 129, 0.3)';
        } else {
            this.style.boxShadow = '0 10px 40px rgba(239, 68, 68, 0.3)';
        }
    });

    return card;
}

// Open booking modal
function openBookingModal(slotId, slotNumber, date, checkInTime, checkOutTime) {
    // Check if user is logged in
    const isLoggedIn = document.body.dataset.loggedIn === 'true';
    if (!isLoggedIn) {
        showToast('Please login to book a slot', 'warning');
        setTimeout(() => {
            window.location.href = 'login.php';
        }, 1500);
        return;
    }

    selectedSlot = { id: slotId, number: slotNumber };

    const modal = document.getElementById('bookingModal');
    const modalSlotId = document.getElementById('modalSlotId');
    const modalSlotNumber = document.getElementById('modalSlotNumber');
    const modalSlotDisplay = document.getElementById('modalSlotDisplay');
    const modalDateDisplay = document.getElementById('modalDateDisplay');
    const modalCheckInDisplay = document.getElementById('modalCheckInDisplay');
    const modalCheckOutDisplay = document.getElementById('modalCheckOutDisplay');
    const modalVehicle = document.getElementById('modalVehicle');

    // Pre-fill form
    modalSlotId.value = slotId;
    modalSlotNumber.value = slotNumber;
    modalSlotDisplay.textContent = slotNumber;
    modalDateDisplay.textContent = formatDate(date);
    modalCheckInDisplay.textContent = formatTime(checkInTime);
    modalCheckOutDisplay.textContent = formatTime(checkOutTime);

    // Pre-fill vehicle number if available
    const quickVehicleInput = document.getElementById('vehicleNumber');
    if (quickVehicleInput && quickVehicleInput.value) {
        modalVehicle.value = quickVehicleInput.value;
    }

    // Show modal
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

// Close booking modal
function closeBookingModal() {
    const modal = document.getElementById('bookingModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = '';

    // Reset form
    document.getElementById('bookingForm').reset();
    selectedSlot = null;
}

// Format date for display
function formatDate(dateString) {
    const date = new Date(dateString + 'T00:00:00');
    return date.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
}

// Format time for display
function formatTime(timeString) {
    const [hours, minutes] = timeString.split(':');
    const hour = parseInt(hours);
    const ampm = hour >= 12 ? 'PM' : 'AM';
    const displayHour = hour % 12 || 12;
    return `${displayHour}:${minutes} ${ampm}`;
}

// Booking form submission
document.addEventListener('DOMContentLoaded', function () {
    const bookingForm = document.getElementById('bookingForm');
    if (bookingForm) {
        bookingForm.addEventListener('submit', function (e) {
            e.preventDefault();
            submitBooking();
        });
    }
});

// Submit booking
function submitBooking() {
    const form = document.getElementById('bookingForm');
    const formData = new FormData(form);
    formData.append('action', 'bookSlot');
    formData.append('date', currentDate);
    formData.append('check_in_time', currentCheckInTime);
    formData.append('check_out_time', currentCheckOutTime);

    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';

    fetch('book.php', {
        method: 'POST',
        body: formData
    })
        .then(res => res.json())
        .then(data => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;

            if (data.success) {
                showToast(data.message || 'Booking confirmed successfully!', 'success');
                closeBookingModal();

                // Refresh slots
                setTimeout(() => {
                    checkAvailability(currentDate, currentCheckInTime, currentCheckOutTime);
                }, 1000);

                // Reset quick booking form
                const quickForm = document.getElementById('quickBookingForm');
                if (quickForm) {
                    quickForm.reset();
                }
            } else {
                showToast(data.message || 'Booking failed. Please try again.', 'error');
            }
        })
        .catch(err => {
            console.error('Error:', err);
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
            showToast('An error occurred. Please try again.', 'error');
        });
}

// Toast notification
function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    const toastIcon = document.getElementById('toastIcon');
    const toastMessage = document.getElementById('toastMessage');

    if (!toast || !toastIcon || !toastMessage) return;

    // Set icon and color based on type
    const icons = {
        success: 'fa-check-circle text-green-500',
        error: 'fa-exclamation-circle text-red-500',
        info: 'fa-info-circle text-blue-500',
        warning: 'fa-exclamation-triangle text-yellow-500'
    };

    toastIcon.className = `fas ${icons[type] || icons.success} text-2xl`;
    toastMessage.textContent = message;

    // Show toast
    toast.classList.remove('translate-y-full');
    toast.classList.add('translate-y-0');

    // Auto hide after 5 seconds
    setTimeout(() => {
        hideToast();
    }, 5000);
}

function hideToast() {
    const toast = document.getElementById('toast');
    if (toast) {
        toast.classList.add('translate-y-full');
        toast.classList.remove('translate-y-0');
    }
}

// Smooth scrolling for anchor links
function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href === '#' || href === '#home') {
                e.preventDefault();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } else if (href.startsWith('#')) {
                const target = document.querySelector(href);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });

                    // Close mobile menu if open
                    const mobileMenu = document.getElementById('mobileMenu');
                    if (mobileMenu && !mobileMenu.classList.contains('hidden')) {
                        mobileMenu.classList.add('hidden');
                        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
                        if (mobileMenuBtn) {
                            const icon = mobileMenuBtn.querySelector('i');
                            icon.classList.remove('fa-times');
                            icon.classList.add('fa-bars');
                        }
                    }
                }
            }
        });
    });
}

// Close modal on Escape key
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('bookingModal');
        if (modal && !modal.classList.contains('hidden')) {
            closeBookingModal();
        }
    }
});

// Logout function
async function logout() {
    try {
        const formData = new FormData();
        formData.append('action', 'logout');

        const response = await fetch('auth.php', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            showToast('Logged out successfully', 'success');
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        }
    } catch (error) {
        console.error('Logout error:', error);
        window.location.reload();
    }
}
