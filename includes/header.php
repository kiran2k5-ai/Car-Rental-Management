<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Car Rental System</title>
  <link href="https://fonts.googleapis.com/css2?family=Segoe+UI&display=swap" rel="stylesheet" />
  <style>
    /* Basic global styles */
    body, html {
    height: 100%;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
}

.container {
    flex: 1;
}

footer {
    background-color: #212529;
    color: white;
    padding: 20px 0;
    text-align: center;
    margin-top: auto;
}

    body {
      margin: 0;
      background: #f5f7fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: #333;
    }

    a {
      color: #007bff;
      text-decoration: none;
      transition: color 0.3s ease;
    }
    a:hover {
      color: #0056b3;
    }

    /* Navbar styling */
    nav {
      background-color: #007bff;
      padding: 1rem 2rem;
      box-shadow: 0 4px 10px rgba(0, 123, 255, 0.3);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    nav .logo {
      color: white;
      font-weight: 700;
      font-size: 1.5rem;
      letter-spacing: 1px;
    }

    nav ul {
      list-style: none;
      display: flex;
      gap: 1.5rem;
      margin: 0;
      padding: 0;
    }

    nav ul li a {
      color: white;
      font-weight: 600;
      font-size: 1rem;
    }

    nav ul li a:hover,
    nav ul li a.active {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<nav>
  <div class="logo">Car Rental System</div>
  <ul>
    <li><a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">Home</a></li>
    <li><a href="login.php" class="<?= basename($_SERVER['PHP_SELF']) == 'login.php' ? 'active' : '' ?>">Login</a></li>
    <li><a href="register.php" class="<?= basename($_SERVER['PHP_SELF']) == 'register.php' ? 'active' : '' ?>">Register</a></li>
    <li><a href="available_cars.php" class="<?= basename($_SERVER['PHP_SELF']) == 'available_cars.php' ? 'active' : '' ?>">Available Cars</a></li>
    <li><a href="../logout.php" class="<?= basename($_SERVER['PHP_SELF']) == 'logout.php' ? 'active' : '' ?>">Logout</a></li>
  </ul>
</nav>
