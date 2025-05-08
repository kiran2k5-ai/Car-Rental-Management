<?php
require_once("../db/config.php");
include("includes/header.php");

$message = '';

// Handle deletion
if (isset($_GET['delete'])) {
    $offer_id = (int) $_GET['delete'];
    $conn->query("DELETE FROM offers WHERE id = $offer_id");
    $message = "✅ Offer deleted.";
}

// Fetch offers
$offers = $conn->query("SELECT offers.*, cars.model FROM offers JOIN cars ON offers.car_id = cars.id");
?>

<style>
    body {
        background: #f8f9fa;
        font-family: 'Segoe UI', sans-serif;
    }

    .offers-container {
        max-width: 1100px;
        margin: 50px auto;
        padding: 30px;
        background: #ffffff;
        border-radius: 10px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.1);
    }

    h2 {
        text-align: center;
        margin-bottom: 30px;
        font-weight: normal;
        color: #333;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead {
        background-color: #007bff;
        color: white;
    }

    th, td {
        padding: 15px;
        text-align: center;
        font-weight: normal;
        border-bottom: 1px solid #eee;
    }

    tr:hover {
        background-color: #f1f1f1;
    }

    .btn {
        padding: 6px 12px;
        border: none;
        border-radius: 5px;
        color: white;
        text-decoration: none;
        font-size: 14px;
        transition: 0.3s;
    }

    .btn-delete {
        background: #dc3545;
    }

    .btn-add {
        background: #28a745;
        margin-bottom: 20px;
        display: inline-block;
    }

    .alert {
        background: #d1e7dd;
        color: #0f5132;
        padding: 10px 15px;
        border-radius: 6px;
        margin-bottom: 15px;
        text-align: center;
    }
</style>

<div class="offers-container">
    <h2>🎁 Manage Offers</h2>

    <?php if ($message): ?>
        <div class="alert"><?= $message; ?></div>
    <?php endif; ?>

    <a href="add_offers.php" class="btn btn-add">➕ Add New Offer</a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Car Model</th>
                <th>Offer Title</th>
                <th>Discount (%)</th>
                <th>Valid Till</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($offers->num_rows > 0): ?>
                <?php while ($offer = $offers->fetch_assoc()): ?>
                    <tr>
                        <td><?= $offer['id']; ?></td>
                        <td><?= htmlspecialchars($offer['model']); ?></td>
                        <td><?= htmlspecialchars($offer['title']); ?></td>
                        <td><?= $offer['discount_percent']; ?>%</td>
                        <td><?= $offer['valid_till']; ?></td>
                        <td>
                            <a href="?delete=<?= $offer['id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this offer?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6">No offers available.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include("includes/footer.php"); ?>
