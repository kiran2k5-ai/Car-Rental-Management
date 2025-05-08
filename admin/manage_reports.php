<?php
require_once("../db/config.php");
include("includes/header.php");

// Fetch bookings with car and user info
$sql = "SELECT b.*, c.model AS car_model, u.name AS user_name, u.email AS user_email
        FROM bookings b
        JOIN cars c ON b.car_id = c.id
        JOIN users u ON b.user_id = u.id
        ORDER BY b.start_date DESC";

$result = $conn->query($sql);
?>

<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: #f8f9fa;
        margin: 0;
    }

    .container {
        max-width: 1200px;
        margin: 40px auto;
        padding: 20px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        margin-bottom: 30px;
        font-weight: 600;
        color: #2c3e50;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 15px;
    }

    thead {
        background-color: #007bff;
        color: #fff;
    }

    thead th {
        padding: 16px;
        text-align: center;
        font-weight: normal;
    }

    tbody td {
        padding: 14px;
        text-align: center;
        color: #333;
        border-bottom: 1px solid #eee;
        font-weight: normal;
    }

    tbody tr:hover {
        background-color: #f1f1f1;
        transition: 0.2s ease;
    }

    .no-data {
        text-align: center;
        padding: 50px;
        color: #888;
    }
</style>

<div class="container">
    <h2>📊 Booking Report</h2>

    <?php if ($result && $result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Email</th>
                    <th>Car</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $count = 1;
                while ($row = $result->fetch_assoc()):
                ?>
                <tr>
                    <td><?= $count++; ?></td>
                    <td><?= htmlspecialchars($row['user_name']); ?></td>
                    <td><?= htmlspecialchars($row['user_email']); ?></td>
                    <td><?= htmlspecialchars($row['car_model']); ?></td>
                    <td><?= htmlspecialchars($row['start_date']); ?></td>
                    <td><?= htmlspecialchars($row['end_date']); ?></td>
                    <td><?= ucfirst(htmlspecialchars($row['status'])); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="no-data">No booking data available.</div>
    <?php endif; ?>
</div>

<?php include("includes/footer.php"); ?>
