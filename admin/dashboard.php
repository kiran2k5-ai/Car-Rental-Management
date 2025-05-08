<?php
session_start();

// if (!isset($_SESSION['admin'])) {
//     header("Location: ../login.php");
//     exit;
// }

require_once("../db/config.php");
include("includes/header.php");

// Fetch stats
$total_cars = $conn->query("SELECT COUNT(*) AS total FROM cars")->fetch_assoc()['total'] ?? 0;
$total_bookings = $conn->query("SELECT COUNT(*) AS total FROM bookings")->fetch_assoc()['total'] ?? 0;
$total_users = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'] ?? 0;
$total_payments = $conn->query("SELECT SUM(amount) AS total FROM payments")->fetch_assoc()['total'] ?? 0;
?>

<style>
footer {
    background-color: #f8f9fa !important;
    color: #6c757d !important;
    padding: 15px 0;
}

body {
    background-color: #f8f9fa;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}
.wrapper {
    flex: 1;
    display: flex;
    flex-direction: column;
}
.main-content {
    flex: 1;
}
.card-hover {
    transition: transform 0.3s, box-shadow 0.3s;
    border-radius: 1rem;
    height: 180px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1.2rem;
    background: linear-gradient(135deg, #1f4068, #2a5298);
    color: #fff;
}
.card-hover:hover {
    transform: translateY(-8px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.25);
}
.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 2rem;
}
.management-link {
    text-decoration: none;
    color: #fff;
    font-weight: 600;
    background: linear-gradient(135deg,rgb(5, 232, 249),rgb(5, 232, 249));
    padding: 30px;
    border-radius: 1rem;
    text-align: center;
    transition: transform 0.3s, box-shadow 0.3s;
}
.management-link:hover {
    transform: translateY(-8px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.25);
}
.btn-logout {
    background: #dc3545;
    border: none;
    padding: 12px 30px;
    font-size: 18px;
    border-radius: 0.75rem;
    transition: background 0.3s;
}
.btn-logout:hover {
    background: #c82333;
}
footer {
    background-color: #343a40;
    color: #ccc;
    text-align: center;
    padding: 15px;
}
</style>

<div class="wrapper">
    <div class="container my-5 main-content">
        <h2 class="mb-5 text-center">🚀 Admin Dashboard</h2>

        <div class="dashboard-grid mb-5">
            <div class="card card-hover">
                Total Cars<br><span class="fs-2"><?= htmlspecialchars($total_cars); ?></span>
            </div>

            <div class="card card-hover">
                Total Bookings<br><span class="fs-2"><?= htmlspecialchars($total_bookings); ?></span>
            </div>

            <div class="card card-hover">
                Total Users<br><span class="fs-2"><?= htmlspecialchars($total_users); ?></span>
            </div>

            <div class="card card-hover">
                Total Revenue<br><span class="fs-3">₹<?= number_format($total_payments ?? 0, 2); ?></span>
            </div>
        </div>

        <h4 class="mb-4">🔧 Management Sections</h4>
        <div class="dashboard-grid mb-5">
            <a href="manage_cars.php" class="management-link">🚗 Manage Cars</a>
            <a href="add_car.php" class="management-link">➕ Add New Car</a>
            <a href="edit_car.php" class="management-link">✏️ Edit Car</a>
            <a href="delete_car.php" class="management-link">❌ Delete Car</a>
            <a href="manage_bookings.php" class="management-link">📅 Manage Bookings</a>
            <a href="manage_users.php" class="management-link">👤 Manage Users</a>
            <a href="manage_payments.php" class="management-link">💳 Manage Payments</a>
            <a href="manage_messages.php" class="management-link">✉️ Manage Messages</a>
            <a href="manage_reports.php" class="management-link">📊 View Reports</a>
            <a href="manage_offers.php" class="management-link">🎁 Manage Offers</a>
            <a href="admin_settings.php" class="management-link">⚙️ Admin Settings</a>
            <a href="backup_restore.php" class="management-link">🛠️ Backup & Restore</a>
        </div>
        <br>
        <br>
        
    </div>

    <?php include("includes/footer.php"); ?>
</div>
