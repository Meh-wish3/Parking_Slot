# ParkEase - Parking Slot Booking System

A modern, fully responsive parking slot booking website built with HTML5, Tailwind CSS, Vanilla JavaScript, PHP, and MySQL.

## Features

- 🎨 **Modern UI/UX**: Clean SaaS-style interface with glassmorphism effects, gradients, and smooth animations
- 📱 **Fully Responsive**: Mobile-first design that works on all devices
- ⚡ **Fast Booking**: Quick booking bar with real-time availability checking
- 🎯 **Interactive Slot Grid**: Visual representation of parking slots with status indicators
- 📊 **Admin Dashboard**: Real-time statistics and booking management
- 🔔 **Toast Notifications**: User-friendly feedback for all actions
- ♿ **Accessible**: Semantic HTML with ARIA labels

## Tech Stack

- **Frontend**: HTML5, Tailwind CSS, Vanilla JavaScript
- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+

## Installation

### Prerequisites

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- Composer (optional, for dependency management)

### Setup Steps

1. **Clone or download the project**
   ```bash
   cd "web tech project"
   ```

2. **Configure Database**
   - Open `db.php` and update database credentials:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_USER', 'your_username');
     define('DB_PASS', 'your_password');
     define('DB_NAME', 'parkease_db');
     ```

3. **Create Database**
   ```sql
   CREATE DATABASE parkease_db;
   ```

4. **Initialize Database Tables**
   - The database tables will be created automatically on first page load
   - Or you can manually run the SQL from `db.php`

5. **Start Web Server**
   ```bash
   # Using PHP built-in server
   php -S localhost:8000
   
   # Or configure Apache/Nginx to point to project directory
   ```

6. **Access the Application**
   - Main site: `http://localhost:8000/index.php`
   - Admin panel: `http://localhost:8000/admin.php`

## File Structure

```
web tech project/
├── index.php          # Main landing page with booking interface
├── admin.php          # Admin dashboard
├── book.php           # Booking API endpoints
├── db.php             # Database connection and initialization
├── script.js          # All JavaScript functionality
└── README.md          # This file
```

## Usage

### For Users

1. **Book a Slot**:
   - Select date and time
   - Enter vehicle number
   - Click "Check Availability"
   - Choose an available slot
   - Fill booking details in modal
   - Confirm booking

2. **View Bookings**: Currently available through admin panel

### For Admins

1. **View Statistics**: See total, booked, and available slots
2. **View All Bookings**: Complete list of all bookings with details
3. **Manage Slots**: Add or remove parking slots (UI ready, backend needs implementation)

## API Endpoints

### `book.php`

- **POST** `action=checkAvailability`: Check slot availability for date/time
- **POST** `action=bookSlot`: Create a new booking
- **POST** `action=getBookings`: Get all bookings (admin)
- **POST** `action=getStats`: Get statistics (admin)

## Database Schema

### `slots` Table
- `id` (INT, Primary Key)
- `slot_number` (VARCHAR, Unique)
- `status` (ENUM: 'available', 'booked')
- `created_at` (TIMESTAMP)

### `bookings` Table
- `id` (INT, Primary Key)
- `slot_id` (INT, Foreign Key)
- `slot_number` (VARCHAR)
- `name` (VARCHAR)
- `vehicle_number` (VARCHAR)
- `phone_number` (VARCHAR)
- `booking_date` (DATE)
- `booking_time` (TIME)
- `status` (ENUM: 'active', 'completed', 'cancelled')
- `created_at` (TIMESTAMP)

## Customization

### Colors & Styling
- All styling uses Tailwind CSS classes
- Modify gradient colors in HTML classes (e.g., `from-blue-600 to-purple-600`)
- Glassmorphism effects can be adjusted in the `<style>` section

### Slot Count
- Default: 16 slots (P01-P16)
- Modify in `db.php` initialization function

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

## Security Notes

⚠️ **Important**: This is a demo/development version. For production:

1. Add input sanitization and validation
2. Implement authentication/authorization
3. Use prepared statements (already implemented)
4. Add CSRF protection
5. Implement rate limiting
6. Add proper error handling
7. Use HTTPS
8. Validate and sanitize all user inputs

## Future Enhancements

- User authentication system
- Email notifications
- Payment integration
- Booking cancellation
- Recurring bookings
- Slot management backend
- Advanced filtering and search
- Export booking reports

## License

This project is open source and available for educational purposes.

## Support

For issues or questions, please check the code comments or create an issue in your repository.

---

Built with ❤️ using modern web technologies

