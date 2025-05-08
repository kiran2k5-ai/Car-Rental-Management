<?php
session_start();
require_once("../db/config.php");
include("../includes/header.php");

// Fetch valid offers
$sql = "SELECT offers.*, cars.model, cars.rent_per_day 
        FROM offers 
        JOIN cars ON offers.car_id = cars.id 
        WHERE offers.valid_till >= CURDATE()
        ORDER BY offers.valid_till ASC";

$result = $conn->query($sql);
?>

<div class="container mt-5 mb-5">
    <h2 class="text-center mb-4">🎉 Current Car Rental Offers</h2>

    <?php if ($result->num_rows > 0): ?>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php while ($offer = $result->fetch_assoc()): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($offer['title']) ?></h5>
                            <p class="card-text">
                                <strong>Car:</strong> <?= htmlspecialchars($offer['model']) ?><br>
                                <strong>Rent/Day:</strong> ₹<?= $offer['rent_per_day'] ?><br>
                                <strong>Discount:</strong> <?= $offer['discount_percent'] ?>%<br>
                                <strong>Valid Till:</strong> <?= $offer['valid_till'] ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="text-center text-muted">No current offers available.</p>
    <?php endif; ?>
</div>

<?php include("../includes/footer.php"); ?>
