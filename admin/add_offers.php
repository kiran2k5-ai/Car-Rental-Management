<?php
require_once("../db/config.php");
include("includes/header.php");

$message = '';

// Fetch all cars to show in dropdown
$cars = $conn->query("SELECT id, model FROM cars");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $car_id = (int)$_POST['car_id'];
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $discount = (float)$_POST['discount'];
    $valid_till = $_POST['valid_till'];

    if ($car_id && $title && $discount && $valid_till) {
        $stmt = $conn->prepare("INSERT INTO offers (title, description, discount_percentage, valid_until, car_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdsi", $title, $description, $discount, $valid_till, $car_id);

        if ($stmt->execute()) {
            $message = "✅ Offer added successfully!";
        } else {
            $message = "❌ Failed to add offer: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "❌ Please fill in all fields.";
    }
}
?>

<div class="container mt-5 mb-5">
    <div class="card shadow-lg p-4">
        <h2 class="text-center mb-4">🎁 Add Car Offer</h2>

        <?php if ($message): ?>
            <div class="alert alert-info text-center"><?= htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="car_id" class="form-label">Select Car</label>
                <select name="car_id" id="car_id" class="form-select" required>
                    <option value="">-- Choose a Car --</option>
                    <?php while ($car = $cars->fetch_assoc()): ?>
                        <option value="<?= $car['id']; ?>"><?= htmlspecialchars($car['model']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="title" class="form-label">Offer Title</label>
                <input type="text" name="title" id="title" class="form-control" placeholder="e.g., Summer Discount" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <input type="text" name="description" id="description" class="form-control" placeholder="e.g., Save more this summer" required>
            </div>

            <div class="mb-3">
                <label for="discount" class="form-label">Discount (%)</label>
                <input type="number" step="0.01" name="discount" id="discount" class="form-control" placeholder="e.g., 15" required>
            </div>

            <div class="mb-4">
                <label for="valid_till" class="form-label">Valid Till</label>
                <input type="date" name="valid_till" id="valid_till" class="form-control" required>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-success px-5">Add Offer</button>
            </div>
        </form>
    </div>
</div>

<?php include("includes/footer.php"); ?>
