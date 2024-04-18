<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order Summary</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <header>
    <h1>Order Summary</h1>
  </header>

  
  <main>
    <div id="order-details">
      <h2>Order Details</h2>
      
	
  <?php
// Initialize total price, discount percentage, discount amount, and final price

if (isset($_POST['submit'])) {
    $dish_names = $_POST['option'];
    $restaurant_name = $_POST['restaurant_name'];
    $dish_prices = $_POST['dish_price'];
	$total_price = 0;
    echo "<p>Restaurant Name: <strong>{$restaurant_name}</strong></p>";
    echo "<p>Menu Items Selected:</p>";
    echo "<ul>";

    foreach ($dish_names as $dish) {
        // Get the price corresponding to the dish using its identifier
        $price = isset($dish_prices[$dish]) ? $dish_prices[$dish] : 0;
        echo "<li>{$dish} - $ {$price}</li>"; // Display dish name and price
        $total_price += floatval($price); // Add each dish price to total
    }

    echo "</ul>";
    echo "<p><strong>Total Price:</strong> $ {$total_price}</p>	  </ul></div>";
	
} 

    
?>
	
	
	


    
<?php
$discount_percentage = 0;
$discount_amount = 0;
$final_price = 0;
$Display_forms = True;

	if (isset($_POST['apply_discount']) && isset($_POST['membership'])) {
    
	

    $membership_type = $_POST['membership'];
		
        // Convert membership type to lowercase for comparison
        $membership_type = strtolower($membership_type);
		$total_price = isset($_POST['total_price']) ? $_POST['total_price'] : 0;
        if ($membership_type === 'premium') {
            $discount_percentage = 10;
        }

        $discount_amount = ($total_price * $discount_percentage) / 100;
        $final_price = $total_price - $discount_amount;
		//display menus and restuarant name
		
		
        // Display discount information and revised total price
        echo "<div id='discount-info'>";
        echo "<h2>Discount Information</h2>";
        echo "<p><strong>Membership: </strong>" .ucfirst($membership_type)."</p>";
		echo "<p><strong>Total Price: </strong> {$total_price} </p>";
        echo "<p><strong>Discount Applied: </strong> {$discount_percentage}%</p>";
        echo "<p><strong>Discount Amount: </strong> $ {$discount_amount}</p>";
        echo "<p><strong>Final Price: </strong> $ {$final_price}</p>";
        echo "</div>";
		$Display_forms= false;
    }
if ($Display_forms) {
?>

    <form id="membership-form" method="POST">
	<p><b>Do you have membership ?</b></p>
	<label for="membership">Select membership type:</label>
	<select name="membership" id="membership">
		<option value="basic">Basic</option>
		<option value="premium">Premium</option>
	</select>
	<input type="hidden" name="total_price" value="<?php echo $total_price; ?>">
	<button type="submit" name="apply_discount">Apply</button>
	</form>


<?php
}
?>
	<!--
    <div id="discount-info" style="display: none;">
      <h2>Discount Information</h2>
      <p><strong>Membership:</strong> <span id="membership-type"></span></p>
      <p><strong>Discount Applied:</strong> <span id="discount-amount"></span></p>
      <p><strong>Final Price:</strong> $<span id="final-price"></span></p>
    </div> 
	-->
    <button id="pay-button">Pay</button>
  </main>
  <footer>
    <p>&copy; 2024 Restaurant Finder. All rights reserved.</p>
  </footer>
  	
	
</body>
</html>
