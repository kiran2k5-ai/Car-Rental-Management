<?php
session_start();

require_once("../db/config.php");
include("../includes/header.php");
include("../includes/navbar.php");

// Handle remove request
if (isset($_GET['remove'])) {
    $remove_id = intval($_GET['remove']);
    if (isset($_SESSION['cart'])) {
        // Remove the car id from cart session
        $_SESSION['cart'] = array_filter($_SESSION['cart'], function($id) use ($remove_id) {
            return $id !== $remove_id;
        });
        // Re-index array
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
    // Redirect to avoid resubmission on refresh
    header("Location: cart.php");
    exit();
}
?>

<h2>Your Cart</h2>

<?php
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty. <a href='../available_cars.php'>Browse cars</a></p>";
} else {
    $ids = implode(',', array_map('intval', $_SESSION['cart']));
    $query = "SELECT * FROM cars WHERE id IN ($ids)";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        echo "<p>Error loading cart items.</p>";
    } else {
        echo '<div style="display:flex; flex-wrap: wrap; gap: 1.5rem; justify-content: center;">';

        while ($car = mysqli_fetch_assoc($result)) {
            echo '<div style="
                border: 1px solid #ddd; 
                border-radius: 10px; 
                padding: 1rem; 
                width: 250px; 
                text-align: center;
                box-shadow: 0 4px 10px rgba(0,0,0,0.1);
                position: relative;
                ">
                <img src="../assets/img/' . htmlspecialchars($car['image']) . '" alt="' . htmlspecialchars($car['model']) . '" style="width:100%; border-radius: 8px; margin-bottom: 0.5rem;">
                <h3>' . htmlspecialchars($car['model']) . '</h3>
                <p>₹' . htmlspecialchars($car['rent_per_day']) . ' per day</p>
                <a href="cart.php?remove=' . urlencode($car['id']) . '" style="
                    display:inline-block;
                    background:#dc3545;
                    color:#fff;
                    padding: 0.3rem 0.7rem;
                    border-radius: 5px;
                    text-decoration:none;
                    position: absolute;
                    top: 10px;
                    right: 10px;
                    font-weight: 600;
                    ">Remove</a>
                </div>';
        }
        echo '</div>';

        echo '<p style="text-align:center; margin-top: 2rem;"><a href="../available_cars.php" style="text-decoration:none; color:#007bff;">Add more cars</a></p>';
    }
}
?>

<?php include("../includes/footer.php"); ?>
