<?php
session_start();

// Function to get order details from track.txt
function getOrderDetails($orderNumber) {
    $file = fopen("track.txt", "r");
    $orderDetails = null;

    while (($line = fgets($file)) !== false) {
        //list($storedOrderNumber, $orderTime, $status) = explode("|", trim($line));
		$newfile= explode("|", trim($line));
		$storedOrderNumber=$newfile[0];
		$orderTime=$newfile[1];
		
		$status=$newfile[2];
		
        if ($storedOrderNumber == $orderNumber) {
            $orderDetails = [
                'orderNumber' => $storedOrderNumber,
                'orderTime' => $orderTime,
                'status' => $status
            ];
            break;
        }
    }
    fclose($file);
    return $orderDetails;
}

// Function to update order status in track.txt
function updateOrderStatus($orderNumber, $newStatus) {
    $lines = file("track.txt");
    $file = fopen("track.txt", "w");

    foreach ($lines as $line) {
        //list($storedOrderNumber, $orderTime, $status) = explode("|", trim($line));
		$newfile= explode("|", trim($line));
		$storedOrderNumber=$newfile[0];
		$orderTime=$newfile[1];
		
		$status=$newfile[2];
		
        if ($storedOrderNumber == $orderNumber) {
            $status = $newStatus;
        }
        fwrite($file, $storedOrderNumber . "|" . $orderTime . "|" . $status . "\n");
    }
    fclose($file);
}

// Check if order number is entered and Deliver button is clicked
$orderNumber = isset($_POST['orderNumber']) ? htmlspecialchars($_POST['orderNumber']) : '';
$orderDetails = $orderNumber ? getOrderDetails($orderNumber) : null;

if (isset($_POST['deliver']) && $orderDetails) {
    updateOrderStatus($orderNumber, 'Delivered');
    $orderDetails['status'] = 'Delivered';
	
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Driver</title>
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
        table {
            margin: 0 auto;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Delivery Driver</h1>
        <nav>
            <ul>
                <li><a href="Home Page.html">Home Page</a></li>
                <li><a href="customer.php">Customer</a></li>
                <li><a href="restaurant.php">Restaurant Edit</a></li>
                
                <li><a href="trackorder.php">Track Orders</a></li>
				<li><a href="delivery.php" target="_self">Delivery</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h2>Order Delivery</h2>
        <form method="post">
            <label for="orderNumber">Enter Order Number:</label>
            <input type="text" id="orderNumber" name="orderNumber" value="<?php echo htmlspecialchars($orderNumber); ?>">
            <button type="submit">Check Order</button>
        </form>

        <?php if ($orderDetails): ?>
            <h3>Order Details</h3>
            <table>
                <tr>
                    <th>Order Number</th>
                    <th>Order Time</th>
                    <th>Status</th>
                </tr>
                <tr>
                    <td><?php echo htmlspecialchars($orderDetails['orderNumber']); ?></td>
                    <td><?php echo htmlspecialchars($orderDetails['orderTime']); ?></td>
                    <td><?php echo htmlspecialchars($orderDetails['status']); ?></td>
                </tr>
            </table>
            <?php if ($orderDetails['status'] !== 'Delivered'): ?>
                <form method="post">
                    <input type="hidden" name="orderNumber" value="<?php echo htmlspecialchars($orderNumber); ?>"><br/>
                    <button type="submit" name="deliver">Deliver</button>
                </form>
            <?php else: ?>
                <p>This order has been delivered.</p>
            <?php endif; ?>
        <?php elseif ($orderNumber): ?>
            <p>No order found with the given order number.</p>
        <?php endif; ?>
    </main>
</body>
</html>
