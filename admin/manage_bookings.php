<?php
require_once("../db/config.php");
include("includes/header.php");


// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $pickup = $_POST['pickup_date'];
    $return = $_POST['return_date'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE bookings SET start_date = ?, end_date = ?, status = ? WHERE id = ?");
    $stmt->bind_param("sssi", $pickup, $return, $status, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Handle delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $id = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM bookings WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<style>
    body {
        background: #f0f2f5;
    }

    .container {
        max-width: 1000px;
        margin: auto;
    }

    h2 {
        text-align: center;
        margin: 30px 0;
        font-weight: bold;
        color: #222;
    }

    .table-container {
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        animation: fadeIn 0.7s ease;
    }

    @keyframes fadeIn {
        from {
            transform: translateY(40px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin: auto;
    }

    th {
        background: #007bff;
        color: white;
        padding: 12px;
        text-align: center;
    }

    td {
        padding: 10px;
        vertical-align: middle;
        text-align: center;
    }

    input[type="date"], select {
        padding: 4px;
        width: 130px;
        font-size: 0.9rem;
    }

    .btn {
        font-size: 0.8rem;
        padding: 5px 10px;
        margin: 2px;
        border: none;
        border-radius: 5px;
        color: white;
        cursor: pointer;
    }

    .btn-update {
        background-color: #28a745;
    }

    .btn-delete {
        background-color: #dc3545;
    }

    .badge {
        padding: 4px 8px;
        font-size: 0.85rem;
        border-radius: 4px;
    }
</style>

<div class="container my-5">
    <h2>📅 Manage Bookings</h2>

    <div class="table-container">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Car</th>
                    <th>Pickup Date</th>
                    <th>Return Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $query = "SELECT bookings.*, users.name AS username, cars.model 
                      FROM bookings 
                      LEFT JOIN users ON bookings.user_id = users.id 
                      JOIN cars ON bookings.car_id = cars.id 
                      ORDER BY bookings.id DESC";
            $result = mysqli_query($conn, $query);

            if ($result && mysqli_num_rows($result) > 0):
                while ($row = mysqli_fetch_assoc($result)):
            ?>
                <tr>
                    <form method="POST">
                        <td><?= $row['id']; ?><input type="hidden" name="id" value="<?= $row['id']; ?>"></td>
                        <td><?= htmlspecialchars($row['username'] ?? 'N/A'); ?></td>
                        <td><?= htmlspecialchars($row['model']); ?></td>
                        <td><input type="date" name="pickup_date" value="<?= $row['start_date']; ?>"></td>
                        <td><input type="date" name="return_date" value="<?= $row['end_date']; ?>"></td>
                        <td>
                            <select name="status">
                                <option value="pending" <?= $row['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="confirmed" <?= $row['status'] == 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                <option value="cancelled" <?= $row['status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                        </td>
                        <td>
                            <button type="submit" name="update" class="btn btn-update">💾 Update</button>
                            <button type="submit" name="delete" class="btn btn-delete" onclick="return confirm('Delete this booking?')">🗑️</button>
                        </td>
                    </form>
                </tr>
            <?php endwhile; else: ?>
                <tr><td colspan="7">No bookings found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include("includes/footer.php"); ?>
