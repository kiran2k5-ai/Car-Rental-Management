<?php
require_once("../db/config.php");
include("includes/header.php");
?>

<style>
    body {
        margin: 0;
        padding: 0;
        background: #f5f7fa;
        font-family: 'Poppins', sans-serif;
    }

    h2 {
        text-align: center;
        margin: 40px 0 20px;
        font-weight: 700;
        color: #2c3e50;
    }

    .page-wrapper {
        display: flex;
        justify-content: center;
        align-items: flex-start;
        min-height: calc(100vh - 120px); /* leave space for header/footer */
        padding: 20px;
    }

    .table-container {
        width: 100%;
        max-width: 1200px;
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        animation: slideFadeIn 0.6s ease;
    }

    @keyframes slideFadeIn {
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
    }

    thead th {
        background-color: #007bff;
        color: white;
        padding: 15px;
        font-size: 16px;
    }

    tbody td {
        padding: 15px;
        vertical-align: middle;
        border-bottom: 1px solid #eaeaea;
    }

    tbody tr:hover {
        background-color: #f0f8ff;
        transition: background 0.3s ease;
    }

    .car-image {
        width: 100px;
        height: auto;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .action-btns a {
        margin: 0 4px;
        font-size: 14px;
        font-weight: 600;
        padding: 6px 12px;
        border-radius: 5px;
        display: inline-block;
        transition: transform 0.2s ease;
    }

    .action-btns a:hover {
        transform: scale(1.08);
    }

    .btn-warning { background-color: #ffc107; color: white; }
    .btn-danger { background-color: #dc3545; color: white; }
    .btn-info { background-color: #17a2b8; color: white; }

    .footer {
        background: #222;
        color: #eee;
        padding: 15px 0;
        text-align: center;
        font-size: 14px;
    }
</style>

<div class="page-wrapper">
    <div class="table-container">
        <h2>🚗 Manage Cars</h2>

        <table class="table table-bordered text-center align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Car Model</th>
                    <th>Rent Per Day</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $cars = $conn->query("SELECT * FROM cars");
                if ($cars->num_rows > 0):
                    while ($car = $cars->fetch_assoc()):
                ?>
                <tr>
                    <td><?= $car['id']; ?></td>
                    <td><?= htmlspecialchars($car['model']); ?></td>
                    <td>₹<?= htmlspecialchars($car['rent_per_day']); ?></td>
                    <td><img src="../assets/img/<?= htmlspecialchars($car['image']); ?>" alt="Car" class="car-image" onerror="this.onerror=null; this.src='/assets/img/default_car.jpg';"></td>
                    <td class="action-btns">
                        <a href="edit_car.php?id=<?= $car['id']; ?>" class="btn btn-warning btn-sm">✏️ Edit</a>
                        <a href="delete_car.php?id=<?= $car['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this car?');">❌ Delete</a>
                        <a href="view_car.php?id=<?= $car['id']; ?>" class="btn btn-info btn-sm">ℹ️ View</a>
                    </td>
                </tr>
                <?php endwhile; else: ?>
                <tr>
                    <td colspan="5">No cars found.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>



<?php include("includes/footer.php"); ?>