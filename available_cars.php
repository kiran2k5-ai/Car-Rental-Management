<?php
session_start();
require_once("db/config.php");
include("includes/header.php");
include("includes/navbar.php");

// Redirect if user not logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// Optional: access user details
$user = $_SESSION['user']; // ['id'], ['name'], ['email'] are all available
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
    box-shadow: 0 10px 20px rgba(0,0,0,0.12), 0 6px 6px rgba(0,0,0,0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    cursor: pointer;
    perspective: 1000px;
    text-align: center;
  }

  .car-card:hover {
    transform: translateY(-10px) rotateX(8deg) rotateY(8deg);
    box-shadow: 0 20px 30px rgba(0,0,0,0.25), 0 12px 12px rgba(0,0,0,0.18);
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

  .book-btn {
    display: inline-block;
    background: #007bff;
    color: white;
    text-decoration: none;
    padding: 0.6rem 1.2rem;
    border-radius: 6px;
    font-weight: 600;
    box-shadow: 0 6px 12px rgba(0, 123, 255, 0.3);
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
  }

  .book-btn:hover {
    background: #0056b3;
    box-shadow: 0 8px 16px rgba(0, 86, 179, 0.5);
  }

  @media (max-width: 900px) {
    .cars-container {
      flex-direction: column;
      align-items: center;
    }
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
</style>

<main>
  <h2>Available Cars</h2>

  <div class="cars-container">
  <?php
  $query = "SELECT * FROM cars";
  $result = mysqli_query($conn, $query);

  if (!$result) {
      die("Query Failed: " . mysqli_error($conn));
  }

  if (mysqli_num_rows($result) > 0):
      while ($row = mysqli_fetch_assoc($result)):
  ?>
    <div class="car-card" tabindex="0" role="button" aria-label="Book <?= htmlspecialchars($row['model']); ?>">
      <img src="assets/img/<?= htmlspecialchars($row['image']); ?>" alt="<?= htmlspecialchars($row['model']); ?>">
      <h3><?= htmlspecialchars($row['model']); ?></h3>
      <p>Price: ₹<?= htmlspecialchars($row['rent_per_day']); ?> per day</p>
      <a class="book-btn" href="user/book_cars.php?car_id=<?= urlencode($row['id']); ?>">Book Now</a>
    </div>
  <?php
      endwhile;
  else:
  ?>
    <p class="text-center">No cars available right now. Please check back later!</p>
  <?php
  endif;
  ?>
  </div>
</main>

<?php include("includes/footer.php"); ?>
