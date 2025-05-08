<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit();
}

// Connect to database
$conn = new mysqli("localhost", "root", "", "car_rental");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userId = $_SESSION['user']['id'];
$userName = $_SESSION['user']['name'];

// Fetch booking stats
$totalBookings = $conn->query("SELECT COUNT(*) as total FROM bookings WHERE user_id = $userId")->fetch_assoc()['total'];
$activeBookings = $conn->query("SELECT COUNT(*) as active FROM bookings WHERE user_id = $userId AND status='active'")->fetch_assoc()['active'];
$completedBookings = $conn->query("SELECT COUNT(*) as complete FROM bookings WHERE user_id = $userId AND status='completed'")->fetch_assoc()['complete'];

// Fetch current bookings
$currentBookings = $conn->query("SELECT b.*, c.name AS car_name, c.image FROM bookings b JOIN cars c ON b.car_id = c.id WHERE b.user_id = $userId ORDER BY b.start_date DESC LIMIT 3");

// Fetch booking history
$historyBookings = $conn->query("SELECT b.*, c.name AS car_name FROM bookings b JOIN cars c ON b.car_id = c.id WHERE b.user_id = $userId ORDER BY b.start_date DESC");

function formatDate($date) {
    return date("M d, Y", strtotime($date));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <style>
        <?php include("dashboard_style.css"); ?>
    </style>
</head>
<body>
<div class="container">
    <header>
        <div class="logo">CarRentalPro</div>
        <div class="user-profile">
            <div class="user-avatar"><?= strtoupper($userName[0]) ?></div>
            <div><?= htmlspecialchars($userName) ?></div>
        </div>
    </header>

    <div class="dashboard-grid">
        <section class="welcome-section glass-card">
            <div class="welcome-text">
                <h1>Welcome, <?= htmlspecialchars($userName) ?>!</h1>
                <p>Your personalized dashboard is ready.</p>
            </div>
            <div class="date-display"><?= date("l, F j, Y") ?></div>
        </section>

        <section class="stats-section">
            <div class="stat-card glass-card blue-gradient">
                <div class="icon">📅</div>
                <h3><?= $totalBookings ?></h3>
                <p>Total Bookings</p>
            </div>
            <div class="stat-card glass-card purple-gradient">
                <div class="icon">🚗</div>
                <h3><?= $activeBookings ?></h3>
                <p>Active Bookings</p>
            </div>
            <div class="stat-card glass-card green-gradient">
                <div class="icon">✅</div>
                <h3><?= $completedBookings ?></h3>
                <p>Completed</p>
            </div>
            <div class="stat-card glass-card orange-gradient">
                <div class="icon">⚠️</div>
                <h3>3</h3>
                <p>Pending Reviews</p>
            </div>
        </section>

        <section class="current-booking glass-card">
            <div class="section-header">
                <h2>Current Bookings</h2>
                <a href="bookings.php" class="btn">View All</a>
            </div>
            <?php while ($row = $currentBookings->fetch_assoc()): ?>
                <div class="booking-card">
                    <div class="car-image">
                        <img src="../images/<?= $row['image'] ?>" alt="<?= $row['car_name'] ?>">
                    </div>
                    <div class="booking-details">
                        <h3><?= htmlspecialchars($row['car_name']) ?></h3>
                        <p>From: <?= formatDate($row['start_date']) ?> — To: <?= formatDate($row['end_date']) ?></p>
                        <p>Status: <span class="status-badge status-<?= strtolower($row['status']) ?>"><?= ucfirst($row['status']) ?></span></p>
                        <div class="booking-progress">
                            <span>Progress</span>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 60%;"></div>
                            </div>
                            <span>60%</span>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </section>

        <section class="quick-links glass-card">
            <div class="section-header"><h2>Quick Links</h2></div>
            <div class="link-grid">
                <div class="quick-link"><div class="icon">📖</div><p>View Bookings</p></div>
                <div class="quick-link"><div class="icon">🛒</div><p>Available Cars</p></div>
                <div class="quick-link"><div class="icon">👤</div><p>Profile</p></div>
                <div class="quick-link"><div class="icon">⚙️</div><p>Settings</p></div>
            </div>
        </section>

        <section class="booking-history glass-card">
            <div class="section-header"><h2>Booking History</h2></div>
            <table class="booking-table">
                <thead>
                    <tr>
                        <th>Car</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $historyBookings->fetch_assoc()): ?>
                        <tr>
                            <td data-label="Car"><?= htmlspecialchars($row['car_name']) ?></td>
                            <td data-label="Start"><?= formatDate($row['start_date']) ?></td>
                            <td data-label="End"><?= formatDate($row['end_date']) ?></td>
                            <td data-label="Status"><span class="status-badge status-<?= strtolower($row['status']) ?>"><?= ucfirst($row['status']) ?></span></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>
    </div>

    <footer>
        <div class="footer-content">
            <div class="footer-logo">
                <h3>CarRentalPro</h3>
                <p>Premium car rental platform providing high quality services to customers across the globe.</p>
            </div>
            <div class="footer-links">
                <div class="footer-column">
                    <h4>Company</h4>
                    <ul>
                        <li><a href="#">About</a></li>
                        <li><a href="#">Careers</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h4>Support</h4>
                    <ul>
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">Cancellation Policy</a></li>
                        <li><a href="#">Accessibility</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h4>Legal</h4>
                    <ul>
                        <li><a href="#">Terms</a></li>
                        <li><a href="#">Privacy</a></li>
                        <li><a href="#">Security</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-social">
                <h4>Connect</h4>
                <div class="social-icons">
                    <div class="social-icon">🔵</div>
                    <div class="social-icon">🐦</div>
                    <div class="social-icon">📸</div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> CarRentalPro. All rights reserved.</p>
            <div class="app-links">
                <a href="#" class="app-link">📱 App Store</a>
                <a href="#" class="app-link">🤖 Google Play</a>
            </div>
        </div>
    </footer>
</div>
</body>
</html>
