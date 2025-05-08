<?php
include("includes/header.php");
?>

<style>
  .hero {
    background: linear-gradient(135deg, #4b79a1, #283e51);
    color: white;
    padding: 100px 20px;
    text-align: center;
    border-radius: 12px;
    box-shadow: 0 8px 15px rgba(0,0,0,0.3);
    margin-bottom: 50px;
  }
  .hero h1 {
    font-size: 3rem;
    margin-bottom: 20px;
  }
  .hero p {
    font-size: 1.2rem;
    max-width: 700px;
    margin: 0 auto 40px;
    line-height: 1.5;
  }
  .btn-primary {
    background-color: #007bff;
    color: white;
    padding: 15px 30px;
    border-radius: 8px;
    font-size: 1.1rem;
    text-decoration: none;
    transition: background-color 0.3s ease;
    box-shadow: 0 4px 8px rgba(0, 123, 255, 0.4);
  }
  .btn-primary:hover {
    background-color: #0056b3;
  }

  .features {
    display: flex;
    justify-content: center;
    gap: 3rem;
    flex-wrap: wrap;
    max-width: 900px;
    margin: 0 auto 60px;
  }
  .feature-card {
    background: #f9f9f9;
    border-radius: 10px;
    padding: 30px 20px;
    width: 280px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    text-align: center;
    transition: transform 0.3s ease;
    position: relative;
  }
  .feature-card:hover {
    transform: translateY(-8px);
  }
  .feature-card h3 {
    margin-bottom: 15px;
    color: #333;
  }
  .feature-card p {
    font-size: 1rem;
    color: #555;
    line-height: 1.4;
  }
  .feature-icon {
    font-size: 3.5rem;
    color: #007bff;
    margin-bottom: 20px;
    transition: color 0.3s ease;
  }
  .feature-card:hover .feature-icon {
    color: #0056b3;
  }

  footer {
    text-align: center;
    color: #777;
    margin-top: 100px;
    padding-bottom: 20px;
  }
</style>

<div class="hero">
  <h1>Welcome to Car Rental Management System</h1>
  <p>Effortlessly rent cars with our easy-to-use platform. Browse from a wide range of vehicles, book instantly, and enjoy competitive prices — all from the comfort of your home.</p>
  <a href="available_cars.php" class="btn-primary">Browse Available Cars</a>
</div>

<div class="features">
  <div class="feature-card">
    <i class="fas fa-car feature-icon" aria-hidden="true"></i>
    <h3>Wide Vehicle Selection</h3>
    <p>Choose from sedans, SUVs, sports cars, and more — all inspected and ready to drive.</p>
  </div>
  <div class="feature-card">
    <i class="fas fa-mouse-pointer feature-icon" aria-hidden="true"></i>
    <h3>Simple Booking Process</h3>
    <p>Book your preferred car in just a few clicks with our user-friendly interface.</p>
  </div>
  <div class="feature-card">
    <i class="fas fa-dollar-sign feature-icon" aria-hidden="true"></i>
    <h3>Affordable Pricing</h3>
    <p>Enjoy competitive daily rental rates and transparent pricing with no hidden fees.</p>
  </div>
</div>

<?php include("includes/footer.php"); ?>
