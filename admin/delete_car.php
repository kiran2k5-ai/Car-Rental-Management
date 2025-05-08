<?php
require_once("../db/config.php");
include("includes/header.php");

// Handle deletion
if (isset($_GET['id'])) {
    $car_id = intval($_GET['id']);
    $query = "DELETE FROM cars WHERE id = $car_id";

    if (mysqli_query($conn, $query)) {
        $message = "✅ Car deleted successfully!";
    } else {
        $message = "❌ Failed to delete car.";
    }
}
?>

<style>
  html, body {
    height: 100%;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
  }

  main {
    flex: 1;
    padding: 2rem;
    background: #f9f9f9;
  }

  h2 {
    text-align: center;
    margin-bottom: 2rem;
    font-weight: 700;
    color: #222;
  }

  .cars-container {
    display: flex;
    flex-wrap: wrap;
    gap: 2rem;
    justify-content: center;
  }

  .car-card {
    background: #fff;
    border-radius: 12px;
    width: 280px;
    padding: 1.5rem;
    box-shadow:
      0 10px 20px rgba(0,0,0,0.12),
      0 6px 6px rgba(0,0,0,0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    cursor: pointer;
    perspective: 1000px;
    text-align: center;
  }

  .car-card:hover {
    transform: translateY(-10px) rotateX(8deg) rotateY(8deg);
    box-shadow:
      0 20px 30px rgba(0,0,0,0.25),
      0 12px 12px rgba(0,0,0,0.18);
  }

  .car-card img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-radius: 8px;
    margin-bottom: 1rem;
    box-shadow: 0 8px 15px rgba(0,0,0,0.1);
  }

  .car-card h3 {
    margin: 0 0 0.5rem 0;
    font-weight: 600;
    color: #222;
  }

  .car-card p {
    font-size: 1rem;
    color: #555;
    margin-bottom: 1rem;
  }

  .delete-btn {
    display: inline-block;
    background: #dc3545;
    color: white;
    text-decoration: none;
    padding: 0.6rem 1.2rem;
    border-radius: 6px;
    font-weight: 600;
    box-shadow: 0 6px 12px rgba(220, 53, 69, 0.3);
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
  }

  .delete-btn:hover {
    background: #b52a37;
    box-shadow: 0 8px 16px rgba(181, 42, 55, 0.5);
  }

  .alert {
    text-align: center;
    font-weight: 600;
    padding: 10px;
    margin-bottom: 20px;
  }

  .alert-success {
    background-color: #d4edda;
    color: #155724;
    border-radius: 8px;
  }

  .alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    border-radius: 8px;
  }

  footer {
    background: #222;
    color: #ccc;
    text-align: center;
    padding: 1rem 0;
    font-size: 0.9rem;
    margin-top: auto;
  }

  footer p {
    margin: 0;
  }

  @media (max-width: 900px) {
    .cars-container {
      flex-direction: column;
      align-items: center;
    }
  }
</style>

<main>
  <h2>🗑️ Delete Cars</h2>

  <?php if (!empty($message)): ?>
    <div class="alert <?= strpos($message, '✅') !== false ? 'alert-success' : 'alert-danger'; ?>">
      <?= $message; ?>
    </div>
  <?php endif; ?>

  <div class="cars-container">
    <?php
    $query = "SELECT * FROM cars";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0):
      while ($row = mysqli_fetch_assoc($result)):
    ?>
      <div class="car-card">
        <img src="../assets/img/<?= htmlspecialchars($row['image']); ?>" alt="<?= htmlspecialchars($row['model']); ?>">
        <h3><?= htmlspecialchars($row['model']); ?></h3>
        <p>Price: ₹<?= htmlspecialchars($row['rent_per_day']); ?> per day</p>
        <a class="delete-btn" href="delete_car.php?id=<?= urlencode($row['id']); ?>" onclick="return confirm('Are you sure you want to delete this car?');">Delete</a>
      </div>
    <?php
      endwhile;
    else:
    ?>
      <p>No cars available for deletion!</p>
    <?php
    endif;
    ?>
  </div>
</main>



<?php include("includes/footer.php"); ?>
