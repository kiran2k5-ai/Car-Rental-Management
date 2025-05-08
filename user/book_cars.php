<?php
session_start();
require_once("../db/config.php");

if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit;
}

$user = $_SESSION['user'];
$message = '';
$car = null;

// Fetch selected car details
if (isset($_GET['car_id'])) {
    $car_id = (int) $_GET['car_id'];
    $stmt = $conn->prepare("SELECT * FROM cars WHERE id = ?");
    $stmt->bind_param("i", $car_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $car = $result->fetch_assoc();
    $stmt->close();

    if (!$car) {
        $message = "❌ Car not found.";
    }
} else {
    $message = "❌ No car selected for booking.";
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $car_id = (int) $_POST['car_id'];
    $pickup_date = $_POST['pickup_date'] ?? '';
    $return_date = $_POST['return_date'] ?? '';
    $user_id = $user['id'];

    if ($pickup_date && $return_date) {
        $stmt = $conn->prepare("INSERT INTO bookings (user_id, car_id, start_date, end_date, status) VALUES (?, ?, ?, ?, 'pending')");
        $stmt->bind_param("iiss", $user_id, $car_id, $pickup_date, $return_date);
        $message = $stmt->execute() ? "✅ Booking successful!" : "❌ Booking failed: " . $stmt->error;
        $stmt->close();
    } else {
        $message = "❌ Please fill in all fields.";
    }
}
?>

<?php include("../includes/header.php"); ?>

<style>
    body {
        background: #f2f2f2;
        font-family: 'Segoe UI', sans-serif;
    }

    .booking-container {
        max-width: 900px;
        margin: 60px auto;
        background: white;
        padding: 60px;
        border-radius: 16px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.08);
    }

    h2 {
        text-align: center;
        font-weight: normal;
        color: #333;
        margin-bottom: 40px;
    }

    .form-label {
        display: block;
        margin-bottom: 6px;
        color: #555;
        font-weight: normal;
    }

    .form-control {
        width: 100%;
        padding: 14px;
        font-size: 16px;
        border: 1px solid #ccc;
        border-radius: 8px;
        margin-bottom: 24px;
        background: #fafafa;
    }

    .btn-book {
        display: inline-block;
        width: 100%;
        padding: 16px;
        font-size: 18px;
        background-color: #0066cc;
        color: white;
        border: none;
        border-radius: 10px;
        transition: background 0.3s ease;
        cursor: pointer;
    }

    .btn-book:hover {
        background-color: #004a99;
    }

    .alert {
        padding: 14px;
        border-radius: 8px;
        background-color: #e7f5ff;
        color: #0077cc;
        margin-bottom: 30px;
        text-align: center;
    }
</style>

<div class="booking-container">
    <h2>Book a Car</h2>

    <?php if ($message): ?>
        <div class="alert"><?= htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <?php if ($car): ?>
        <form method="POST">
            <input type="hidden" name="car_id" value="<?= $car['id']; ?>">

            <label class="form-label">Car Model</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($car['model']); ?>" readonly>

            <label class="form-label">Your Name</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($user['name']); ?>" readonly>

            <label class="form-label">Your Email</label>
            <input type="email" class="form-control" value="<?= htmlspecialchars($user['email']); ?>" readonly>

            <label class="form-label">Pickup Date</label>
            <input type="date" name="pickup_date" class="form-control" required>

            <label class="form-label">Return Date</label>
            <input type="date" name="return_date" class="form-control" required>

            <button type="submit" class="btn-book">Confirm Booking</button>
        </form>
    <?php endif; ?>
</div>

<?php include("../includes/footer.php"); ?>
