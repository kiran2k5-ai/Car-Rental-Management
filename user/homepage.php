<?php include('../includes/header.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Luxury Car Rental</title>
    <style>
        /* Reset and Base Styles */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #f5f5f5;
            color: #333;
        }

        /* Car Gallery */
        .car-gallery {
            padding: 50px 20px;
            background-color: #fff;
            text-align: center;
        }

        .car-gallery h2 {
            font-size: 2.5rem;
            margin-bottom: 40px;
            color: #222;
        }

        .car-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            justify-items: center;
        }

        .car-card {
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            max-width: 350px;
            position: relative;
            perspective: 1000px;
        }

        .car-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.2);
        }

        .car-image-wrapper {
            width: 100%;
            height: 200px;
            position: relative;
            transform-style: preserve-3d;
            transition: transform 0.6s;
        }

        .car-image-wrapper:hover {
            transform: rotateY(20deg); /* 3D effect when hovering */
        }

        .car-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 10px;
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

        /* Booking Form Section (Grid Box) */
        .booking-form {
            background-color: #000;
            padding: 30px;
            margin: 20px 0;
            color: #fff;
            border-radius: 10px;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3); /* Added shadow effect */
        }

        .booking-form input, .booking-form select {
            padding: 10px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
        }

        .booking-form input[type="submit"] {
            background-color: #FFD700;
            color: #000;
            cursor: pointer;
            font-size: 18px;
        }

        .booking-form input[type="submit"]:hover {
            background-color: #e6c200;
        }

        /* Car Rotating Animation */
        @keyframes rotateCar {
            0% {
                transform: rotateY(0deg);
            }
            50% {
                transform: rotateY(180deg);
            }
            100% {
                transform: rotateY(360deg);
            }
        }

        .car-rotate {
            animation: rotateCar 10s infinite linear;
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
        'description' => 'Experience the thrill of driving a Lamborghini Aventador, a true supercar with unparalleled performance and style.'
    ),
    array(
        'image' => 'https://images.unsplash.com/photo-1615390933845-0c3c1c9e3f1d?auto=format&fit=crop&w=800&q=80',
        'model' => 'Ferrari 488 GTB',
        'price' => 450,
        'description' => 'Indulge in the exhilarating power and sleek design of the Ferrari 488 GTB, a true Italian masterpiece.'
    ),
    array(
        'image' => 'https://images.unsplash.com/photo-1589394818370-7c7f4e3f3b3d?auto=format&fit=crop&w=800&q=80',
        'model' => 'Rolls-Royce Phantom',
        'price' => 600,
        'description' => 'Ride in the epitome of luxury with the Rolls-Royce Phantom, a car that combines unparalleled craftsmanship and comfort.'
    ),
    array(
        'image' => 'https://images.unsplash.com/photo-1589394818370-7c7f4e3f3b3d?auto=format&fit=crop&w=800&q=80',
        'model' => 'Bugatti Chiron',
        'price' => 700,
        'description' => 'Experience unmatched speed and luxury with the Bugatti Chiron, a marvel of engineering and design.'
    ),
    array(
        'image' => 'https://images.unsplash.com/photo-1606813909023-5e1b2b6f5a3b?auto=format&fit=crop&w=800&q=80',
        'model' => 'Aston Martin DB11',
        'price' => 550,
        'description' => 'Drive the Aston Martin DB11, a blend of elegance and performance that defines British automotive excellence.'
    ),
    array(
        'image' => 'https://images.unsplash.com/photo-1615390933845-0c3c1c9e3f1d?auto=format&fit=crop&w=800&q=80',
        'model' => 'Bentley Continental GT',
        'price' => 580,
        'description' => 'Enjoy the perfect combination of power and luxury with the Bentley Continental GT, a grand tourer like no other.'
    )
);
?>

<section class="car-gallery">
    <h2>Our Luxury Fleet</h2>
    <section class="booking-form">
            <h2>Book Your Luxury Ride</h2>
            <form action="#" method="POST">
                <input type="text" name="location" placeholder="Pick-up Location" required>
                <input type="date" name="start-trip" placeholder="Start Trip" required>
                <input type="date" name="end-trip" placeholder="End Trip" required>
                <input type="text" name="search" placeholder="Search Cars" required>
                <input type="submit" value="Search">
            </form>
        </section><br>
    <div class="car-cards">
        

        <!-- Car Image Rotation -->
        <div class="car-rotate">
            <img src="https://images.unsplash.com/photo-1606813909023-5e1b2b6f5a3b?auto=format&fit=crop&w=800&q=80" alt="Car Image">
        </div>

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
