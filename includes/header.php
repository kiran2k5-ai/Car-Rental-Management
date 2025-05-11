<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Luxury Car Rental</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Header Styles */
        header {
            background-color: #000000;
            padding: 15px 0;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }

        body {
            padding-top: 80px; /* Prevents content from being hidden behind the fixed header */
            margin: 0;
        }

        nav {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding: 0 20px;
            position: relative;
        }

        .logo {
            font-family: 'Poppins', sans-serif;
            font-size: 18px;
            color: #FFD700;
            font-weight: bold;
            position: absolute;
            left: 20px;
        }

        .logo span {
            color: #FFFFFF;
        }

        .nav-links {
            display: flex;
            gap: 15px;
        }

        .nav-links a {
            color: #FFFFFF;
            text-decoration: none;
            font-size: 14px;
            text-transform: uppercase;
            padding: 6px 12px;
            transition: background-color 0.3s ease-in-out;
        }

        .nav-links a:hover {
            background-color: #FFD700;
            color: #000000;
            border-radius: 5px;
        }

        .cta-button {
            background-color: #FFD700;
            color: #000000;
            padding: 8px 16px;
            border-radius: 30px;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.3s ease-in-out;
            margin-left: 15px;
        }

        .cta-button:hover {
            background-color: #000000;
            color: #FFD700;
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
                <a href="#">Profile</a> 
            </div>

            <!-- Call to Action Button -->
            <a href="#" class="cta-button">Book Now</a>
        </nav>
    </header>
</body>
</html>
