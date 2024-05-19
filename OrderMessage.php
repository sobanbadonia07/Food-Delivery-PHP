<?php
session_start();

// Assume order number and estimated delivery time are passed via GET request
$orderNumber = isset($_GET['orderNumber']) ? htmlspecialchars($_GET['orderNumber']) : 'Unknown';
$estimatedDeliveryTime = date('Y-m-d H:i:s', strtotime('+45 minutes'));

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="styles.css"> <!-- Ensure this link is correct -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fffdf5;
            color: #000;
            margin: 0;
            padding-top: 10px;
            padding-bottom: 30px;
            font-weight: bold;
        }
        header {
            background-color: #2b8a3e;
            color: #fffdf5;
            padding: 20px;
            text-align: center;
        }
        nav ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
        nav ul li {
            display: inline;
            margin-right: 20px;
        }
        nav ul li a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            font-size: 18px;
        }
        main {
            text-align: center;
            padding: 20px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Get Delivered</h1>
        <nav>
            <ul>
                <li><a href="HomePage.html">Home Page</a></li>
                <li><a href="customer.php">Customer</a></li>
                <li><a href="restaurant.php">Restaurant Edit</a></li>
                
                <li><a href="trackorder.php">Track Orders</a></li>
				<li><a href="delivery.php" target="_self">Delivery</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h2>Order Confirmation</h2>
        <p>Your order has been placed successfully!</p>
        <p>Order Number: <strong><?php echo $orderNumber; ?></strong></p>
        <p>Estimated Delivery Time: <strong><?php echo $estimatedDeliveryTime; ?></strong></p>
        <p>Thank you for choosing Get Delivered!</p>
    </main>
</body>
</html>
