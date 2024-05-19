<?php
session_start();

$finalPrice = $totalPrice = 0.0;
$orders = [];
$orderNumber = $_POST['orderNumber'] ?? 'Unknown';
//$restNo=$_POST['restCode'];
if (!empty($_POST['selectedItems'])) {
    foreach ($_POST['selectedItems'] as $item) {
        list($itemName, $itemPrice) = explode('|', $item);
        $itemPrice = floatval(preg_replace('/[^\d.]/', '', $itemPrice));
        $orders[] = ['itemName' => $itemName, 'itemPrice' => $itemPrice];
        $totalPrice += $itemPrice;
    }

    $finalPrice = $totalPrice;

    if (isset($_POST['applyDiscount']) && $_POST['discount'] === '15') {
        $finalPrice *= 0.85;
    }
}

if (isset($_POST['proceed'])) {
    // Capture the time when the user clicks 'Proceed to Pay'
    $currentTime = date('Y-m-d H:i:s');
    // Save to track.txt
    $file = fopen("track.txt", "a");
    fwrite($file, $orderNumber . "|" . $currentTime . "|Under Process\n");
    fclose($file);

    // Save order details to order.txt
    $orderDetails = "$orderNumber\n";
    $orderDetails .= "$currentTime\n";
	$orderDetails .= "$restNo\n";
    foreach ($orders as $order) {
        $orderDetails .= "Item: " . $order['itemName'] . ", Price: $" . number_format($order['itemPrice'], 2) . "\n";
    }
    $orderDetails .= "Total Price: $" . number_format($finalPrice, 2) . "\n\n";
    $orderDetails .= "";
    file_put_contents("order.txt", $orderDetails, FILE_APPEND);

    // Redirect to Order.Message.php
    header('Location: OrderMessage.php?orderNumber=' . urlencode($orderNumber));
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Summary</title>
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
		
		main li {
			list-style-type: none;
		}
    </style>
</head>
<body>
    <header>
        <h1>Get Delivered</h1>
        <nav>
            <ul>
                <li><a href="Home Page.html">Home Page</a></li>
                <li><a href="customer.php">Customer</a></li>
                <li><a href="restaurant.php">Restaurant Edit</a></li>
                <li><a href="delivery.php">Delivery</a></li>
                <li><a href="trackorder.php">Track Orders</a></li>
				<li><a href="delivery.php" target="_self">Delivery</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h2>Order Summary</h2>
        <h3>Your Order Number is <?php echo htmlspecialchars($orderNumber); ?></h3>
        <ul>
            <?php foreach ($orders as $order) {
                echo '<li>' . htmlspecialchars($order['itemName']) . ' - $' . number_format($order['itemPrice'], 2) . '</li>';
            } ?>
        </ul>
        <p>Subtotal: $<?php echo number_format($totalPrice, 2); ?></p>
        <form method="post">
            <input type="hidden" name="orderNumber" value="<?php echo htmlspecialchars($orderNumber); ?>">
            <?php foreach ($orders as $order) {
                echo '<input type="hidden" name="selectedItems[]" value="' . htmlspecialchars($order['itemName'] . '|' . $order['itemPrice']) . '">';
            } ?>
            <label><input type="radio" name="discount" value="0" checked>No Discount</label>
            <label><input type="radio" name="discount" value="15">15% Discount</label>
            <button type="submit" name="applyDiscount">Apply Discount</button>
        </form>
        <p>Final Total: $<?php echo number_format($finalPrice, 2); ?></p>
        <form method="post" >
            <input type="hidden" name="orderNumber" value="<?php echo htmlspecialchars($orderNumber); ?>">
            <input type="hidden" name="finalPrice" value="<?php echo number_format($finalPrice, 2); ?>">
			<input type="hidden" name="restCode" value="<?php echo isset($_POST['restCode']); ?>" >
            <?php foreach ($orders as $order) {
                echo "<input type='hidden' name='selectedItems[]' value='" . htmlspecialchars($order['itemName'] . "|" . $order['itemPrice']) . "'>";
				
            } ?>
            <button type="submit" name="proceed">Proceed to Pay</button>
        </form>
    </main>
</body>
</html>
