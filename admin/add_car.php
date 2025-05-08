<?php
require_once("../db/config.php");
include("includes/header.php");

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $car_name = trim($_POST['car_name']);
    $price = (float) $_POST['price'];

    $image_name = $_FILES['car_image']['name'];
    $image_tmp = $_FILES['car_image']['tmp_name'];
    $upload_folder = "../assets/img/" . basename($image_name);

    if (!file_exists("../images")) {
        mkdir("../images", 0777, true);
    }

    if (move_uploaded_file($image_tmp, $upload_folder)) {
        $stmt = $conn->prepare("INSERT INTO cars (model, rent_per_day, image) VALUES (?, ?, ?)");
        $stmt->bind_param("sds", $car_name, $price, $image_name);
        if ($stmt->execute()) {
            $message = "✅ Car added successfully!";
        } else {
            $message = "❌ Error adding car.";
        }
        $stmt->close();
    } else {
        $message = "❌ Failed to upload image.";
    }
}
?>

<style>
    .full-page-center {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background: linear-gradient(135deg, #e0f7fa, #ffffff);
        perspective: 1000px;
        overflow: hidden;
    }

    .form-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 40px;
        width: 100%;
        max-width: 500px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        animation: floatCard 6s ease-in-out infinite, zoomIn 0.8s ease;
        transform-style: preserve-3d;
        transition: transform 0.5s ease;
    }

    .form-card:hover {
        transform: perspective(1000px) rotateX(5deg) rotateY(5deg) scale(1.02);
    }

    @keyframes floatCard {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-8px); }
    }

    @keyframes zoomIn {
        from { transform: scale(0.5); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    .form-title {
        font-weight: bold;
        margin-bottom: 30px;
        text-align: center;
    }

    .form-btn {
        width: 100%;
        background: linear-gradient(135deg, #007bff, #0056b3);
        color: white;
        border: none;
        padding: 12px;
        font-size: 18px;
        border-radius: 8px;
        transition: background 0.3s ease, transform 0.3s ease;
    }

    .form-btn:hover {
        background: linear-gradient(135deg, #0056b3, #004095);
        transform: scale(1.05);
    }
</style>

<div class="full-page-center">
    <div class="form-card">
        <h2 class="form-title">➕ Add New Car</h2>

        <?php if ($message): ?>
            <div class="alert alert-info text-center"><?= $message; ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Car Name</label>
                <input type="text" name="car_name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Price per Day (₹)</label>
                <input type="number" name="price" class="form-control" required>
            </div>

            <div class="mb-4">
                <label class="form-label">Upload Car Image</label>
                <input type="file" name="car_image" class="form-control" required>
            </div>

            <div class="d-grid">
                <button type="submit" class="form-btn">Add Car</button>
            </div>
        </form>
    </div>
</div>

<?php include("includes/footer.php"); ?>
