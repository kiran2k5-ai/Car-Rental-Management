<?php
require_once("../db/config.php");
include("includes/header.php");

$message = '';

// Fetch existing cars for dropdown
$cars_result = $conn->query("SELECT id, model FROM cars");

// Handle form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $car_id = (int) $_POST['car_id'];
    $car_name = trim($_POST['car_name']);
    $price = (float) $_POST['price'];

    $update_query = "UPDATE cars SET model=?, rent_per_day=?";

    // Image upload handling
    if (!empty($_FILES['car_image']['name'])) {
        $image_name = $_FILES['car_image']['name'];
        $image_tmp = $_FILES['car_image']['tmp_name'];
        $upload_folder = "../images/" . basename($image_name);

        if (!file_exists("../images")) {
            mkdir("../images", 0777, true);
        }

        if (move_uploaded_file($image_tmp, $upload_folder)) {
            $update_query .= ", image=?";
        } else {
            $message = "❌ Failed to upload image.";
        }
    }

    $update_query .= " WHERE id=?";
    $stmt = $conn->prepare($update_query);

    if (!empty($image_name)) {
        $stmt->bind_param("sdsi", $car_name, $price, $image_name, $car_id);
    } else {
        $stmt->bind_param("sdi", $car_name, $price, $car_id);
    }

    if ($stmt->execute()) {
        $message = "✅ Car updated successfully!";
    } else {
        $message = "❌ Error updating car.";
    }

    $stmt->close();
}
?>

<style>
    /* 3D Animations and Center */
    .full-page-center {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background: linear-gradient(135deg, #f0f0f0, #e0f7fa);
        perspective: 1000px;
        overflow: hidden;
    }

    .form-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 40px;
        width: 100%;
        max-width: 550px;
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
        background: linear-gradient(135deg, #28a745, #218838);
        color: white;
        border: none;
        padding: 12px;
        font-size: 18px;
        border-radius: 8px;
        transition: background 0.3s ease, transform 0.3s ease;
    }

    .form-btn:hover {
        background: linear-gradient(135deg, #218838, #1e7e34);
        transform: scale(1.05);
    }
</style>

<div class="full-page-center">
    <div class="form-card">
        <h2 class="form-title">✏️ Edit Car</h2>

        <?php if ($message): ?>
            <div class="alert alert-info text-center"><?= $message; ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Select Car</label>
                <select name="car_id" class="form-control" required>
                    <option value="">-- Select a Car --</option>
                    <?php while ($row = $cars_result->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($row['id']) ?>">
                            <?= htmlspecialchars($row['model']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">New Car Name</label>
                <input type="text" name="car_name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">New Price per Day (₹)</label>
                <input type="number" name="price" class="form-control" required>
            </div>

            <div class="mb-4">
                <label class="form-label">Upload New Image (optional)</label>
                <input type="file" name="car_image" class="form-control">
            </div>

            <div class="d-grid">
                <button type="submit" class="form-btn">Update Car</button>
            </div>
        </form>
    </div>
</div>

<?php include("includes/footer.php"); ?>
