// Global state
let selectedSlot = null;
let currentDate = '';
let currentTime = '';

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
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
    
    window.addEventListener('scroll', function() {
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
        mobileMenuBtn.addEventListener('click', function() {
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
        document.addEventListener('click', function(e) {
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
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const date = document.getElementById('bookingDate').value;
        const time = document.getElementById('bookingTime').value;
        const vehicleNumber = document.getElementById('vehicleNumber').value.trim();
        
        // Validation
        if (!date || !time || !vehicleNumber) {
            showToast('Please fill all fields', 'error');
            return;
        }
        
        // Validate vehicle number format (basic)
        if (vehicleNumber.length < 3) {
            showToast('Please enter a valid vehicle number', 'error');
            return;
        }
        
        currentDate = date;
        currentTime = time;
        
        checkAvailability(date, time);
    });
}

// Check slot availability
function checkAvailability(date, time) {
    const loadingDiv = document.getElementById('loadingSlots');
    const slotsGrid = document.getElementById('slotsGrid');
    const noSlotsMessage = document.getElementById('noSlotsMessage');
    
    loadingDiv.classList.remove('hidden');
    slotsGrid.classList.add('hidden');
    noSlotsMessage.classList.add('hidden');
    
    const formData = new FormData();
    formData.append('action', 'checkAvailability');
    formData.append('date', date);
    formData.append('time', time);
    
    fetch('book.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        loadingDiv.classList.add('hidden');
        
        if (data.success && data.slots) {
            displaySlots(data.slots, date, time);
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
function displaySlots(slots, date, time) {
    const slotsGrid = document.getElementById('slotsGrid');
    const noSlotsMessage = document.getElementById('noSlotsMessage');
    
    slotsGrid.innerHTML = '';
    
    if (slots.length === 0) {
        noSlotsMessage.classList.remove('hidden');
        return;
    }
    
    slots.forEach(slot => {
        const isAvailable = slot.is_available;
        const slotCard = createSlotCard(slot, isAvailable, date, time);
        slotsGrid.appendChild(slotCard);
    });
    
    slotsGrid.classList.remove('hidden');
    noSlotsMessage.classList.add('hidden');
    
    // Smooth scroll to slots
    slotsGrid.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

// Create slot card element
function createSlotCard(slot, isAvailable, date, time) {
    const card = document.createElement('div');
    card.className = `glass rounded-2xl p-6 shadow-xl hover:shadow-2xl transform hover:scale-105 transition-all duration-200 cursor-pointer ${
        isAvailable ? 'border-2 border-green-300' : 'border-2 border-red-300 opacity-75'
    }`;
    
    const statusClass = isAvailable 
        ? 'bg-gradient-to-br from-green-400 to-green-600' 
        : 'bg-gradient-to-br from-red-400 to-red-600';
    
    const statusText = isAvailable ? 'Available' : 'Booked';
    const statusIcon = isAvailable ? 'fa-check-circle' : 'fa-lock';
    
    card.innerHTML = `
        <div class="text-center">
            <div class="w-20 h-20 ${statusClass} rounded-xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                <i class="fas ${statusIcon} text-white text-3xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">${slot.slot_number}</h3>
            <span class="inline-block px-4 py-1 rounded-full text-sm font-semibold mb-4 ${
                isAvailable ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
            }">${statusText}</span>
            ${isAvailable ? `
                <button onclick="openBookingModal('${slot.id}', '${slot.slot_number}', '${date}', '${time}')" 
                        class="w-full px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:shadow-lg transform hover:scale-105 transition-all duration-200 font-semibold">
                    <i class="fas fa-calendar-check mr-2"></i>Book Now
                </button>
            ` : `
                <button disabled class="w-full px-4 py-2 bg-gray-300 text-gray-500 rounded-xl cursor-not-allowed font-semibold">
                    Unavailable
                </button>
            `}
        </div>
    `;
    
    return card;
}

// Open booking modal
function openBookingModal(slotId, slotNumber, date, time) {
    selectedSlot = { id: slotId, number: slotNumber };
    
    const modal = document.getElementById('bookingModal');
    const modalSlotId = document.getElementById('modalSlotId');
    const modalSlotNumber = document.getElementById('modalSlotNumber');
    const modalSlotDisplay = document.getElementById('modalSlotDisplay');
    const modalDateDisplay = document.getElementById('modalDateDisplay');
    const modalTimeDisplay = document.getElementById('modalTimeDisplay');
    const modalVehicle = document.getElementById('modalVehicle');
    
    // Pre-fill form
    modalSlotId.value = slotId;
    modalSlotNumber.value = slotNumber;
    modalSlotDisplay.textContent = slotNumber;
    modalDateDisplay.textContent = formatDate(date);
    modalTimeDisplay.textContent = formatTime(time);
    
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
document.addEventListener('DOMContentLoaded', function() {
    const bookingForm = document.getElementById('bookingForm');
    if (bookingForm) {
        bookingForm.addEventListener('submit', function(e) {
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
    formData.append('time', currentTime);
    
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
                checkAvailability(currentDate, currentTime);
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
        anchor.addEventListener('click', function(e) {
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
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('bookingModal');
        if (modal && !modal.classList.contains('hidden')) {
            closeBookingModal();
        }
    }
});

