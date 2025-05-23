<?php include('../includes/header.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Luxury Car Rental</title>

  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Poppins', sans-serif;
      background-color: #f5f5f5;
      color: #333;
    }

    .car-gallery {
      padding: 40px 20px;
      background-color: #fff;
      text-align: center;
    }

    .car-gallery h2 {
      font-size: 2.5rem;
      margin-bottom: 30px;
      color: #222;
    }

    .grid-box {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 30px;
      background-color: #000;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.4);
      color: white;
      align-items: center;
      margin-bottom: 40px;
    }

    .form-box h2 {
      margin-bottom: 20px;
      font-size: 22px;
      color: #FFD700;
    }

    .booking-form {
      display: grid;
      gap: 12px;
    }

    .booking-form input {
      padding: 10px;
      font-size: 16px;
      border-radius: 5px;
      border: none;
      background-color: #333;
      color: white;
    }

    .booking-form input[type="submit"] {
      background-color: #FFD700;
      color: black;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .booking-form input[type="submit"]:hover {
      background-color: #e6c200;
    }

    /* Spline Iframe Container */
    .spline-container {
      width: 100%;
      height: 350px;
      border-radius: 12px;
      overflow: hidden;
      background: #000;
    }

    .spline-container iframe {
      width: 100%;
      height: 100%;
      border: none;
      border-radius: 12px;
    }

    .car-cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 25px;
      justify-items: center;
    }

    .car-card {
      background-color: #fff;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      max-width: 350px;
    }

    .car-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 8px 30px rgba(0,0,0,0.2);
    }

    .car-image-wrapper {
      width: 100%;
      height: 200px;
    }

    .car-image {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: 10px 10px 0 0;
    }

    .car-info {
      padding: 20px;
      text-align: left;
    }

    .car-info h3 {
      margin: 0 0 10px;
      font-size: 1.5rem;
      color: #000;
    }

    .car-info p {
      margin: 5px 0;
      color: #555;
    }

    .book-now {
      display: inline-block;
      margin-top: 15px;
      padding: 10px 20px;
      background-color: #FFD700;
      color: #000;
      text-decoration: none;
      border-radius: 5px;
      transition: background-color 0.3s ease;
    }

    .book-now:hover {
      background-color: #e6c200;
    }
  </style>
</head>
<body>

<?php
$cars = array(
  array(
    'image' => 'https://images.unsplash.com/photo-1606813909023-5e1b2b6f5a3b?auto=format&fit=crop&w=800&q=80',
    'model' => 'Lamborghini Aventador',
    'price' => 500,
    'description' => 'Experience the thrill of driving a Lamborghini Aventador.'
  ),
  array(
    'image' => 'https://images.unsplash.com/photo-1615390933845-0c3c1c9e3f1d?auto=format&fit=crop&w=800&q=80',
    'model' => 'Ferrari 488 GTB',
    'price' => 450,
    'description' => 'Enjoy the sleek design and speed of Ferrari 488 GTB.'
  ),
  array(
    'image' => '../image/car3.jpeg',
    'model' => 'Ford',
    'price' => 450,
    'description' => 'Enjoy the sleek design and speed of Ferrari 488 GTB.'
  )
);
?>

<section class="car-gallery">
  <h2>Our Luxury Fleet</h2>

  <!-- Booking Form + 3D Viewer -->
  <section class="grid-box">
    <!-- Booking Form -->
    <div class="form-box">
      <h2>Book Your Luxury Ride</h2>
      <form action="#" method="POST" class="booking-form">
        <input type="text" name="location" placeholder="Pick-up Location" required>
        <input type="date" name="start-trip" required>
        <input type="date" name="end-trip" required>
        <input type="text" name="search" placeholder="Search Cars" required>
        <input type="submit" value="Search">
      </form>
    </div>

    <!-- 3D Spline Animation -->
    <div class="spline-container">
      <iframe src="https://my.spline.design/carastonmartinanimation-VgXtcpYr8Wz8NIhudSQKHyy2/" allowfullscreen></iframe>
    </div>
  </section>

  <!-- Car Cards -->
  <div class="car-cards">
    <?php foreach ($cars as $car) { ?>
      <div class="car-card">
        <div class="car-image-wrapper">
          <img class="car-image" src="<?php echo $car['image']; ?>" alt="<?php echo $car['model']; ?>">
        </div>
        <div class="car-info">
          <h3><?php echo $car['model']; ?></h3>
          <p><strong>$<?php echo $car['price']; ?>/day</strong></p>
          <p><?php echo $car['description']; ?></p>
          <a href="#" class="book-now">Book Now</a>
        </div>
      </div>
    <?php } ?>
  </div>
</section>

</body>
</html>
