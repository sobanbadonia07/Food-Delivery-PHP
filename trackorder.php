<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Track Order</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #fffdf5;
      color: #2b8a3e;
      margin: 0;
      padding: 0;
      display: flex;
      flex-direction: column;
      height: 100vh;
    }

    header {
      background-color: #2b8a3e;
      color: #fffdf5;
      padding: 20px;
      text-align: center;
      width: 100%;
    }

    nav ul {
      list-style-type: none;
      padding: 0;
      margin: 0;
      display: flex;
      justify-content: center;
    }

    nav ul li {
      margin-right: 20px;
    }

    nav ul li a {
      color: #fff;
      text-decoration: none;
      font-weight: bold;
      font-size: 18px;
    }

    .main-content {
      display: flex;
      flex: 1;
      justify-content: space-between;
      align-items: center;
      padding: 20px;
      background-color: #fffdf5;
    }

    .container {
      text-align: center;
      padding: 20px;
      border: 1px solid #ccc;
      border-radius: 10px;
      background-color: #fff;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .image-container {
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .image-container img {
      width: 900px;
      height: 800px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
  </style>
</head>
<body>
  <header>
    <h1>Get Delivered</h1>
    <nav>
      <ul>
        <li><a href="Home Page.html" target="_self">Home Page</a></li>
        <li><a href="customer.php" target="_self">Customer</a></li>
        <li><a href="restaurant.php" target="_self">Restaurant Edit</a></li>
        <li><a href="trackorder.php" target="_self">Track Orders</a></li>
        <li><a href="delivery.php" target="_self">Delivery</a></li>
      </ul>
    </nav>
  </header>
  <div class="main-content">
    <div class="container">
      <h1>Track Your Order</h1>
      <img src="tracking.jpg" style="width:300px;height:250px"/>
      <br/>

      <form method="post">
        <label for="orderNumber">Enter your order number:</label>
        <input type="number" id="orderNumber" name="orderNumber" required>
        <button type="submit">Track Order</button>
      </form>
      <?php
      function getRandomStatus($minutesElapsed) {
        if ($minutesElapsed < 1) {
          return "Order is being confirmed.";
        } elseif ($minutesElapsed < 2) {
          return "Order is being prepared.";
        } elseif ($minutesElapsed < 5) {
          return "Order is ready for delivery.";
        } elseif ($minutesElapsed < 10) {
          return "Order is on the way.";
        } else {
          return "Order delivered";
        }
      }

      $reviewSubmitted = false;

      if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submitReview']) && isset($_POST['orderNumber'])) {
        $orderNumber = $_POST['orderNumber'];
        $review = trim($_POST['review']);
        if (!empty($review)) {
          $file = fopen("reviews.txt", "a");
          fwrite($file, "$orderNumber|$review\n");
          fclose($file);
          $reviewSubmitted = true;
          echo "<p>Thank you for your review!</p>";
        } else {
          echo "<p>Review cannot be empty.</p>";
        }
      } elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['orderNumber'])) {
        $orderNumber = $_POST['orderNumber'];
        $found = false;
        $currentTime = time();

        if (($file = fopen("track.txt", "r")) !== FALSE) {
          while (($line = fgets($file)) !== false) {
        //list($storedOrderNumber, $orderTime, $status) = explode("|", trim($line));
		$newfile= explode("|", trim($line));
		$OrdNo=$newfile[0];
		$orderTime=$newfile[1];
		$status=$newfile[2];
		
        if ($orderNumber == $OrdNo) {
              $orderTimestamp = strtotime($orderTime);
              $minutesElapsed = floor(($currentTime - $orderTimestamp) / 60);
              
              echo "<p>Order #$OrdNo was placed at: $orderTime.</p>";
			  
			  if ($status=='Delivered' || $reviewSubmitted){
              echo "<p>Current status: $status</p>";
			  echo '<form method="post">';
                echo '<label for="review">Write a review:</label><br>';
                echo '<textarea id="review" name="review" rows="4" cols="50" required></textarea><br>';
                echo '<input type="hidden" name="orderNumber" value="'.htmlspecialchars($orderNumber).'">';
                echo '<button type="submit" name="submitReview">Submit Review</button>';
                echo '</form>';
				}
			  else {
			  $status = getRandomStatus($minutesElapsed);
			  if ($status=='Order delivered'){echo '<form method="post">';
			  echo "<p>Current status: $status</p>";
                echo '<label for="review">Write a review:</label><br>';
                echo '<textarea id="review" name="review" rows="4" cols="50" required></textarea><br>';
                echo '<input type="hidden" name="orderNumber" value="'.htmlspecialchars($orderNumber).'">';
                echo '<button type="submit" name="submitReview">Submit Review</button>';
                echo '</form>';}else 
			  echo "<p>Current status: $status</p>";
			  }
              $found = true;
              
              
              break;
            }
          }
          fclose($file);
        }

        if (!$found) {
          echo "<p>Invalid order number.</p>";
        }
      }
      ?>
    </div>
    <div class="image-container">
      <img src="track.jpeg">
    </div>
  </div>
</body>
</html>
