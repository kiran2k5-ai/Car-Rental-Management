
<?php
// Start the session
// session_start();

// // Check if user is logged in, if not redirect to login page
// if (!isset($_SESSION['user_id'])) {
//     header("Location: login.php");
//     exit();
// }

// Database connection
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "car_rental"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user information
// $user_id = $_SESSION['user_id'];
$user_query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($user_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();
$stmt->close();

// Get user's total booking count
$total_bookings_query = "SELECT COUNT(*) as total FROM bookings WHERE user_id = ?";
$stmt = $conn->prepare($total_bookings_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$total_bookings_result = $stmt->get_result();
$total_bookings = $total_bookings_result->fetch_assoc()['total'];
$stmt->close();

// Get active rentals count
$active_rentals_query = "SELECT COUNT(*) as active FROM bookings 
                         WHERE user_id = ? AND status = 'confirmed' 
                         AND start_date <= CURDATE() AND end_date >= CURDATE()";
$stmt = $conn->prepare($active_rentals_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$active_rentals_result = $stmt->get_result();
$active_rentals = $active_rentals_result->fetch_assoc()['active'];
$stmt->close();

// Calculate total days driven (sum of days from completed bookings)
$days_driven_query = "SELECT SUM(DATEDIFF(end_date, start_date)) as total_days 
                      FROM bookings 
                      WHERE user_id = ? AND status = 'confirmed' AND end_date < CURDATE()";
$stmt = $conn->prepare($days_driven_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$days_driven_result = $stmt->get_result();
$days_driven = $days_driven_result->fetch_assoc()['total_days'];
if($days_driven === NULL) $days_driven = 0;
$stmt->close();

// Loyalty points (simplified calculation - 1 point per rental)
$loyalty_points_query = "SELECT COUNT(*) as points FROM bookings 
                        WHERE user_id = ? AND status = 'confirmed'";
$stmt = $conn->prepare($loyalty_points_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$loyalty_points_result = $stmt->get_result();
$loyalty_points = $loyalty_points_result->fetch_assoc()['points'];
$stmt->close();

// Get current bookings (active and upcoming)
$current_bookings_query = "SELECT b.*, c.model, c.brand, c.image 
                           FROM bookings b 
                           JOIN cars c ON b.car_id = c.id 
                           WHERE b.user_id = ? AND b.status = 'confirmed' 
                           AND b.end_date >= CURDATE() 
                           ORDER BY b.start_date ASC 
                           LIMIT 2";
$stmt = $conn->prepare($current_bookings_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$current_bookings_result = $stmt->get_result();
$current_bookings = [];
while ($booking = $current_bookings_result->fetch_assoc()) {
    $current_bookings[] = $booking;
}
$stmt->close();

// Get booking history
$booking_history_query = "SELECT b.*, c.model, c.brand
                          FROM bookings b
                          JOIN cars c ON b.car_id = c.id
                          WHERE b.user_id = ?
                          ORDER BY b.created_at DESC
                          LIMIT 5";
$stmt = $conn->prepare($booking_history_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$booking_history_result = $stmt->get_result();
$booking_history = [];
while ($booking = $booking_history_result->fetch_assoc()) {
    $booking_history[] = $booking;
}
$stmt->close();

// Get current date for display
$current_date = date("l, F j, Y");

// Calculate progress for active bookings
function calculateProgress($start_date, $end_date) {
    $start = strtotime($start_date);
    $end = strtotime($end_date);
    $today = time();
    
    if ($today < $start) {
        return 0;
    } elseif ($today > $end) {
        return 100;
    } else {
        $total_duration = $end - $start;
        $elapsed = $today - $start;
        return ($elapsed / $total_duration) * 100;
    }
}

// Format date range
function formatDateRange($start_date, $end_date) {
    $start = new DateTime($start_date);
    $end = new DateTime($end_date);
    
    return $start->format('M j') . ' - ' . $end->format('M j, Y');
}

// Generate booking status badge
function getStatusBadge($status, $start_date, $end_date) {
    $today = date('Y-m-d');
    
    if ($status == 'confirmed') {
        if ($today >= $start_date && $today <= $end_date) {
            return '<span class="status-badge status-active">Active</span>';
        } elseif ($today < $start_date) {
            return '<span class="status-badge status-upcoming">Upcoming</span>';
        } elseif ($today > $end_date) {
            return '<span class="status-badge status-complete">Completed</span>';
        }
    } elseif ($status == 'pending') {
        return '<span class="status-badge status-pending">Pending</span>';
    } elseif ($status == 'cancelled') {
        return '<span class="status-badge status-cancelled">Cancelled</span>';
    }
    
    return '<span class="status-badge">' . ucfirst($status) . '</span>';
}

// Function to get first letter of each word for avatar
function getInitials($name) {
    $words = explode(" ", $name);
    $initials = "";
    
    foreach ($words as $word) {
        $initials .= strtoupper(substr($word, 0, 1));
    }
    
    return $initials;
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DriveFuture - Car Rental Dashboard</title>
  <link rel="stylesheet" href="dashboard.css">
</head>
<body>
  <div class="container">
    <header>
      <div class="logo">DriveFuture</div>
      <div class="user-profile">
        <div class="user-name"><?php echo htmlspecialchars($user['name']); ?></div>
        <div class="user-avatar"><?php echo getInitials($user['name']); ?></div>
      </div>
    </header>
    <style>
        /* Dashboard.css for DriveFuture Car Rental Dashboard */

/* General Styling and Reset */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

:root {
  --primary-color: #4361ee;
  --secondary-color: #3f37c9;
  --accent-color: #4cc9f0;
  --text-color: #2b2d42;
  --text-light: #8d99ae;
  --background: #f8f9fa;
  --card-bg: rgba(255, 255, 255, 0.8);
  --success-color: #4ade80;
  --warning-color: #fbbf24;
  --danger-color: #f87171;
  --info-color: #60a5fa;
  --border-radius: 12px;
  --shadow: 0 8px 32px rgba(31, 38, 135, 0.1);
}

body {
  background: linear-gradient(135deg, #f5f7fa 0%, #e4ecfb 100%);
  color: var(--text-color);
  line-height: 1.6;
  min-height: 100vh;
  padding-bottom: 80px;
}

.container {
  max-width: 1280px;
  margin: 0 auto;
  padding: 0 20px;
}

/* Glass Card Effect */
.glass-card {
  background: var(--card-bg);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  border-radius: var(--border-radius);
  border: 1px solid rgba(255, 255, 255, 0.18);
  box-shadow: var(--shadow);
  overflow: hidden;
}

/* 3D Card Effect */
.card-3d {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card-3d:hover {
  transform: translateY(-5px);
  box-shadow: 0 15px 35px rgba(31, 38, 135, 0.15);
}

/* Header Styling */
header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px 0;
  margin-bottom: 20px;
}

.logo {
  font-size: 24px;
  font-weight: 700;
  color: var(--primary-color);
  letter-spacing: -0.5px;
}

.user-profile {
  display: flex;
  align-items: center;
  gap: 10px;
}

.user-name {
  font-weight: 500;
}

.user-avatar {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
  color: white;
  font-weight: 600;
}

/* Dashboard Grid Layout */
.dashboard-grid {
  display: grid;
  grid-template-columns: repeat(12, 1fr);
  gap: 20px;
  margin-bottom: 40px;
}

.welcome-section {
  grid-column: span 12;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 30px;
  margin-bottom: 20px;
}

.welcome-text h1 {
  font-size: 28px;
  font-weight: 700;
  margin-bottom: 8px;
}

.welcome-text p {
  color: var(--text-light);
}

.date-display {
  font-size: 16px;
  color: var(--text-light);
  font-weight: 500;
}

/* Stats Section */
.stats-section {
  grid-column: span 12;
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 20px;
}

.stat-card {
  display: flex;
  flex-direction: column;
  justify-content: center;
  padding: 20px;
}

.card-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
}

.stat-card .icon {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 50px;
  height: 50px;
  border-radius: 50%;
  margin-bottom: 15px;
}

.stat-card h3 {
  font-size: 24px;
  font-weight: 700;
  margin-bottom: 5px;
}

.stat-card p {
  color: var(--text-light);
  font-size: 14px;
}

/* Gradient Icons */
.blue-gradient {
  background: linear-gradient(135deg, #4361ee, #4cc9f0);
  color: white;
}

.purple-gradient {
  background: linear-gradient(135deg, #8338ec, #c77dff);
  color: white;
}

.green-gradient {
  background: linear-gradient(135deg, #10b981, #34d399);
  color: white;
}

.orange-gradient {
  background: linear-gradient(135deg, #f59e0b, #fbbf24);
  color: white;
}

/* Current Booking Section */
.current-booking {
  grid-column: span 8;
  padding: 30px;
}

.section-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.section-header h2 {
  font-size: 20px;
  font-weight: 600;
}

.btn {
  background: var(--primary-color);
  color: white;
  border: none;
  border-radius: var(--border-radius);
  padding: 8px 16px;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  transition: background 0.3s ease;
  text-decoration: none;
  display: inline-block;
}

.btn:hover {
  background: var(--secondary-color);
}

.booking-card {
  display: flex;
  gap: 20px;
  margin-bottom: 20px;
  padding: 15px;
  border-radius: var(--border-radius);
  background: rgba(255, 255, 255, 0.5);
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
}

.car-image {
  width: 150px;
  min-width: 150px;
  height: 100px;
  border-radius: 8px;
  overflow: hidden;
  background: #e2e8f0;
}

.car-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.booking-details h3 {
  font-size: 18px;
  font-weight: 600;
  margin-bottom: 5px;
}

.booking-details p {
  color: var(--text-light);
  font-size: 14px;
  margin-bottom: 10px;
}

.booking-info {
  display: flex;
  gap: 20px;
  margin-bottom: 15px;
}

.booking-info-item {
  display: flex;
  align-items: center;
  gap: 8px;
}

.booking-info-item .icon {
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--primary-color);
}

.booking-info-item p {
  margin: 0;
  font-size: 14px;
  color: var(--text-color);
}

.booking-progress {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-top: 15px;
}

.progress-bar {
  flex: 1;
  height: 6px;
  background: #e2e8f0;
  border-radius: 10px;
  overflow: hidden;
}

.progress-fill {
  height: 100%;
  background: linear-gradient(to right, var(--primary-color), var(--accent-color));
  border-radius: 10px;
}

.booking-progress span {
  font-size: 12px;
  color: var(--text-light);
}

.no-bookings {
  text-align: center;
  padding: 40px 20px;
}

.no-bookings p {
  color: var(--text-light);
  margin-bottom: 20px;
}

/* Quick Links Section */
.quick-links {
  grid-column: span 4;
  padding: 30px;
}

.link-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 15px;
}

.quick-link {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 20px;
  background: rgba(255, 255, 255, 0.5);
  border-radius: var(--border-radius);
  text-decoration: none;
  color: var(--text-color);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.quick-link:hover {
  transform: translateY(-3px);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.quick-link .icon {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  margin-bottom: 10px;
}

.quick-link p {
  font-size: 14px;
  font-weight: 500;
  margin: 0;
}

/* Booking History Section */
.booking-history {
  grid-column: span 12;
  padding: 30px;
}

.booking-table {
  width: 100%;
  border-collapse: collapse;
}

.booking-table th {
  text-align: left;
  padding: 12px 15px;
  border-bottom: 1px solid #e2e8f0;
  font-weight: 600;
  font-size: 14px;
}

.booking-table td {
  padding: 15px;
  border-bottom: 1px solid #e2e8f0;
  font-size: 14px;
}

.booking-table tr:last-child td {
  border-bottom: none;
}

.status-badge {
  display: inline-block;
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 500;
}

.status-active {
  background-color: rgba(74, 222, 128, 0.2);
  color: #16a34a;
}

.status-upcoming {
  background-color: rgba(96, 165, 250, 0.2);
  color: #2563eb;
}

.status-complete {
  background-color: rgba(148, 163, 184, 0.2);
  color: #475569;
}

.status-pending {
  background-color: rgba(251, 191, 36, 0.2);
  color: #d97706;
}

.status-cancelled {
  background-color: rgba(248, 113, 113, 0.2);
  color: #dc2626;
}

/* Footer Styling */
footer {
  margin-top: 40px;
  padding: 40px 0 20px;
}

.footer-content {
  display: grid;
  grid-template-columns: 2fr 6fr 2fr;
  gap: 40px;
  margin-bottom: 30px;
}

.footer-logo p {
  color: var(--text-light);
  margin-top: 15px;
  font-size: 14px;
}

.footer-links {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
}

.footer-column h4 {
  font-size: 16px;
  font-weight: 600;
  margin-bottom: 15px;
}

.footer-column ul {
  list-style: none;
}

.footer-column ul li {
  margin-bottom: 10px;
}

.footer-column ul li a {
  color: var(--text-light);
  text-decoration: none;
  font-size: 14px;
  transition: color 0.3s ease;
}

.footer-column ul li a:hover {
  color: var(--primary-color);
}

.footer-social h4 {
  font-size: 16px;
  font-weight: 600;
  margin-bottom: 15px;
}

.social-icons {
  display: flex;
  gap: 15px;
}

.social-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.5);
  color: var(--text-color);
  transition: background 0.3s ease, color 0.3s ease;
}

.social-icon:hover {
  background: var(--primary-color);
  color: white;
}

.footer-bottom {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-top: 20px;
  border-top: 1px solid rgba(0, 0, 0, 0.05);
}

.footer-bottom p {
  color: var(--text-light);
  font-size: 14px;
}

.app-links {
  display: flex;
  gap: 15px;
}

.app-link {
  display: flex;
  align-items: center;
  gap: 5px;
  padding: 8px 12px;
  border-radius: 8px;
  background: rgba(255, 255, 255, 0.5);
  color: var(--text-color);
  font-size: 12px;
  font-weight: 500;
  text-decoration: none;
  transition: background 0.3s ease;
}

.app-link:hover {
  background: rgba(255, 255, 255, 0.8);
}

/* Responsive Design */
@media (max-width: 1200px) {
  .stats-section {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .current-booking {
    grid-column: span 12;
  }
  
  .quick-links {
    grid-column: span 12;
  }
  
  .link-grid {
    grid-template-columns: repeat(4, 1fr);
  }
  
  .footer-content {
    grid-template-columns: 1fr;
    gap: 30px;
  }
}

@media (max-width: 768px) {
  .stats-section {
    grid-template-columns: 1fr;
  }
  
  .booking-card {
    flex-direction: column;
  }
  
  .car-image {
    width: 100%;
    height: 180px;
  }
  
  .link-grid {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .booking-table thead {
    display: none;
  }
  
  .booking-table, 
  .booking-table tbody, 
  .booking-table tr, 
  .booking-table td {
    display: block;
    width: 100%;
  }
  
  .booking-table tr {
    margin-bottom: 15px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    overflow: hidden;
  }
  
  .booking-table td {
    display: flex;
    justify-content: space-between;
    text-align: right;
    padding: 10px 15px;
  }
  
  .booking-table td::before {
    content: attr(data-label);
    font-weight: 600;
    text-align: left;
  }
  
  .footer-links {
    grid-template-columns: 1fr;
  }
  
  .footer-bottom {
    flex-direction: column;
    gap: 15px;
  }
}

@media (max-width: 480px) {
  .welcome-section {
    flex-direction: column;
    align-items: flex-start;
    gap: 15px;
  }
  
  .link-grid {
    grid-template-columns: 1fr;
  }
  
  .app-links {
    flex-direction: column;
  }
}
    </style>
    
    <div class="dashboard-grid">
      <!-- Welcome Section -->
      <section class="welcome-section glass-card">
        <div class="welcome-text">
          <h1>Welcome back, <?php echo htmlspecialchars(explode(" ", $user['name'])[0]); ?>!</h1>
          <p>Your next adventure awaits. Check your booking status below.</p>
        </div>
        <div class="date-display">
          <?php echo $current_date; ?>
        </div>
      </section>
      
      <!-- Stats Section -->
      <section class="stats-section">
        <div class="stat-card glass-card card-3d">
          <div class="card-content">
            <div class="icon blue-gradient">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.5-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"></path>
                <circle cx="7" cy="17" r="2"></circle>
                <circle cx="17" cy="17" r="2"></circle>
              </svg>
            </div>
            <h3><?php echo $total_bookings; ?></h3>
            <p>Total Bookings</p>
          </div>
        </div>
        
        <div class="stat-card glass-card card-3d">
          <div class="card-content">
            <div class="icon purple-gradient">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
              </svg>
            </div>
            <h3><?php echo $active_rentals; ?></h3>
            <p>Active Rentals</p>
          </div>
        </div>
        
        <div class="stat-card glass-card card-3d">
          <div class="card-content">
            <div class="icon green-gradient">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
              </svg>
            </div>
            <h3><?php echo $days_driven; ?></h3>
            <p>Days Driven</p>
          </div>
        </div>
        
        <div class="stat-card glass-card card-3d">
          <div class="card-content">
            <div class="icon orange-gradient">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
              </svg>
            </div>
            <h3><?php echo $loyalty_points; ?></h3>
            <p>Loyalty Points</p>
          </div>
        </div>
      </section>
      
      <!-- Current Booking -->
      <section class="current-booking glass-card">
        <div class="section-header">
          <h2>Current Booking</h2>
          <button class="btn">View Details</button>
        </div>
        
        <?php if (count($current_bookings) > 0): ?>
          <?php foreach ($current_bookings as $booking): ?>
            <div class="booking-card">
              <div class="car-image">
                <?php if (!empty($booking['image'])): ?>
                  <img src="<?php echo htmlspecialchars($booking['image']); ?>" alt="<?php echo htmlspecialchars($booking['brand'] . ' ' . $booking['model']); ?>">
                <?php else: ?>
                  <img src="/api/placeholder/400/320" alt="<?php echo htmlspecialchars($booking['brand'] . ' ' . $booking['model']); ?>">
                <?php endif; ?>
              </div>
              <div class="booking-details">
                <h3><?php echo htmlspecialchars($booking['brand'] . ' ' . $booking['model']); ?></h3>
                <p>Booking ID: DRFT-<?php echo str_pad($booking['id'], 8, '0', STR_PAD_LEFT); ?></p>
                
                <div class="booking-info">
                  <div class="booking-info-item">
                    <div class="icon">
                      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                      </svg>
                    </div>
                    <div>
                      <p><?php echo formatDateRange($booking['start_date'], $booking['end_date']); ?></p>
                    </div>
                  </div>
                  
                  <div class="booking-info-item">
                    <div class="icon">
                      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                      </svg>
                    </div>
                    <div>
                      <?php
                        $today = date('Y-m-d');
                        if ($today < $booking['start_date']) {
                          echo '<p>Upcoming</p>';
                        } elseif ($today <= $booking['end_date']) {
                          $days_remaining = floor((strtotime($booking['end_date']) - time()) / (60 * 60 * 24));
                          echo '<p>' . $days_remaining . ' days remaining</p>';
                        } else {
                          echo '<p>Completed</p>';
                        }
                      ?>
                    </div>
                  </div>
                  
                  <div class="booking-info-item">
                    <div class="icon">
                      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                        <path d="M2 17l10 5 10-5"></path>
                        <path d="M2 12l10 5 10-5"></path>
                      </svg>
                    </div>
                    <div>
                      <p>Full insurance</p>
                    </div>
                  </div>
                </div>
                
                <?php if ($today >= $booking['start_date'] && $today <= $booking['end_date']): ?>
                <div class="booking-progress">
                  <span>Picked up</span>
                  <div class="progress-bar">
                    <div class="progress-fill" style="width: <?php echo calculateProgress($booking['start_date'], $booking['end_date']); ?>%"></div>
                  </div>
                  <span>Return</span>
                </div>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="no-bookings">
            <p>You have no current bookings. Browse our cars to start your next adventure!</p>
            <a href="cars.php" class="btn">Browse Cars</a>
          </div>
        <?php endif; ?>
      </section>
      
      <!-- Quick Links -->
      <section class="quick-links glass-card">
        <div class="section-header">
          <h2>Quick Links</h2>
        </div>
        
        <div class="link-grid">
          <a href="cars.php" class="quick-link">
            <div class="icon blue-gradient">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.5-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"></path>
                <circle cx="7" cy="17" r="2"></circle>
                <circle cx="17" cy="17" r="2"></circle>
              </svg>
            </div>
            <p>Browse Cars</p>
          </a>
          
          <a href="offers.php" class="quick-link">
            <div class="icon purple-gradient">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="20 21 12 13.44 4 21 4 3 20 3 20 21"></polygon>
              </svg>
            </div>
            <p>Offers</p>
          </a>
          
          <a href="locations.php" class="quick-link">
            <div class="icon green-gradient">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                <circle cx="12" cy="10" r="3"></circle>
              </svg>
            </div>
            <p>Locations</p>
          </a>
          
          <a href="profile.php" class="quick-link">
            <div class="icon orange-gradient">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
              </svg>
            </div>
            <p>Profile</p>
          </a>
        </div>
      </section>
      
      <!-- Booking History -->
      <section class="booking-history glass-card">
        <div class="section-header">
          <h2>Booking History</h2>
          <a href="bookings.php" class="btn">View All</a>
        </div>
        
        <table class="booking-table">
          <thead>
            <tr>
              <th>Booking ID</th>
              <th>Car</th>
              <th>Date</th>
              <th>Amount</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php if (count($booking_history) > 0): ?>
              <?php foreach ($booking_history as $booking): ?>
                <tr>
                  <td data-label="Booking ID">DRFT-<?php echo str_pad($booking['id'], 8, '0', STR_PAD_LEFT); ?></td>
                  <td data-label="Car"><?php echo htmlspecialchars($booking['brand'] . ' ' . $booking['model']); ?></td>
                  <td data-label="Date"><?php echo formatDateRange($booking['start_date'], $booking['end_date']); ?></td>
                  <td data-label="Amount">$<?php echo number_format($booking['total_price'], 2); ?></td>
                  <td data-label="Status"><?php echo getStatusBadge($booking['status'], $booking['start_date'], $booking['end_date']); ?></td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="5">No booking history found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </section>
    </div>
  </div>
  
  <!-- Footer -->
  <footer class="glass-card">
    <div class="container">
      <div class="footer-content">
        <div class="footer-logo">
          <div class="logo">DriveFuture</div>
          <p>Premium car rental experiences for the modern traveler.</p>
        </div>
        
        <div class="footer-links">
          <div class="footer-column">
            <h4>Company</h4>
            <ul>
              <li><a href="#">About Us</a></li>
              <li><a href="#">Careers</a></li>
              <li><a href="#">Press</a></li>
              <li><a href="#">Blog</a></li>
            </ul>
          </div>
          
          <div class="footer-column">
            <h4>Support</h4>
            <ul>
              <li><a href="#">Contact Us</a></li>
              <li><a href="#">FAQs</a></li>
              <li><a href="#">Roadside Assistance</a></li>
              <li><a href="#">Customer Service</a></li>
            </ul>
          </div>
          
          <div class="footer-column">
            <h4>Legal</h4>
            <ul>
              <li><a href="#">Terms & Conditions</a></li>
              <li><a href="#">Privacy Policy</a></li>
              <li><a href="#">Cookie Policy</a></li>
              <li><a href="#">Rental Agreement</a></li>
            </ul>
          </div>
        </div>
        
        <div class="footer-social">
          <h4>Connect With Us</h4>
          <div class="social-icons">
            <a href="#" class="social-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
              </svg>
            </a>
            <a href="#" class="social-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"></path>
              </svg>
            </a>
            <a href="#" class="social-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
              </svg>
            </a>
            <a href="#" class="social-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path>
                <rect x="2" y="9" width="4" height="12"></rect>
                <circle cx="4" cy="4" r="2"></circle>
              </svg>
            </a>
          </div>
        </div>
      </div>
      
      <div class="footer-bottom">
        <p>&copy; <?php echo date('Y'); ?> DriveFuture. All rights reserved.</p>
        <div class="app-links">
          <a href="#" class="app-link">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"></path>
            </svg>
            <span>App Store</span>
          </a>
          <a href="#" class="app-link">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <polygon points="5 3 19 12 5 21 5 3"></polygon>
            </svg>
            <span>Google Play</span>
          </a>
        </div>
      </div>
    </div>
  </footer>