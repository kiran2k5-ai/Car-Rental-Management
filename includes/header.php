<?php
// This is your header.php file
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Luxury Car Rental</title>
    <link rel="stylesheet" href="styles.css"> <!-- Assuming you have a styles.css file -->
    <style>
        /* Header Styles */
        header {
            background-color: #000000; /* Black background */
            padding: 20px 0;
        }

        nav {
            display: flex;
            justify-content: flex-end; /* Align the navigation to the right */
            align-items: center;
            padding: 0 20px;
        }

        .logo {
            font-family: 'Poppins', sans-serif;
            font-size: 24px;
            color: #FFD700; /* Gold */
            font-weight: bold;
            position: absolute;
            left: 20px; /* Position logo on the left */
        }

        .logo span {
            color: #FFFFFF; /* White */
        }

        .nav-links {
            display: flex;
            gap: 20px;
        }

        .nav-links a {
            color: #FFFFFF; /* White text */
            text-decoration: none;
            font-size: 18px;
            text-transform: uppercase;
            padding: 8px 16px;
            transition: background-color 0.3s ease-in-out;
        }

        .nav-links a:hover {
            background-color: #FFD700; /* Gold background on hover */
            color: #000000; /* Black text on hover */
            border-radius: 5px;
        }

        .cta-button {
            background-color: #FFD700; /* Gold */
            color: #000000; /* Black text */
            padding: 10px 20px;
            border-radius: 30px;
            font-size: 18px;
            text-decoration: none;
            transition: all 0.3s ease-in-out;
        }

        .cta-button:hover {
            background-color: #000000; /* Black on hover */
            color: #FFD700; /* Gold text on hover */
            box-shadow: 0 0 10px rgba(255, 215, 0, 0.7);
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header>
        <nav>
            <!-- Logo -->
            <div class="logo">
                Luxury <span>Cars</span>
            </div>

            <!-- Navigation Links -->
            <div class="nav-links">
                <a href="#">Home</a>
                <a href="#">Fleet</a>
                <a href="#">About Us</a>
                <a href="#">Contact</a>
            </div>

            <!-- Call to Action Button -->
            <a href="#" class="cta-button">Book Now</a>
        </nav>
    </header>

</body>
</html>
