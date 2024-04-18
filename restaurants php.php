<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Customer - Restaurant List</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    body {
      display: flex;
      flex-direction: column;
      min-height: 100vh; /* Ensures full height of the viewport */
    }
	header
	{background-color: #488a82;}
	
    header, main, footer {
      width: 100%;
	  
    }
    main {
      flex-grow: 1; /* Takes up remaining vertical space */
    }
    footer {
      margin-top: auto; /* Pushes footer to the bottom */
      background-color: #488a82;
      color: #fff;
      padding: 20px 0;
      text-align: center;
    }
    .restaurant-container {
      display: flex;
      flex-wrap: wrap;
      center-content: space-around; /* Aligns items horizontally with equal space around them */
      padding: 5px 0; /* Add some padding for spacing */
    }
    .restaurant {
      width: 30%; /* Adjust width as needed */
      margin-bottom: 10px; /* Add some bottom margin for spacing */
      padding: 3px;
      border: 1px solid #ccc;
      border-radius: 5px;
      background-color: #f9f9f9;
      text-align: center;
    }
    .restaurant h3 {
      margin-top: 0;
    }
    .restaurant p {
      margin: 5px 0;
    }
    .restaurant button {
      margin-top: 10px;
      padding: 8px 16px;
      background-color: #488a82;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    .restaurant button:hover {
      background-color: #45a049;
    }
  </style>

</head>

<body>
  <header>
    <h1>Get Delivered</h1>
    <nav>
      <ul>
        <li><a href="Home Page.html">Home</a></li>
        <li><a href="TrackOrder.html" target="_blank">Track Order</a></li>
      </ul>
    </nav>
  </header>
  <main>
    <h2>Browse Restaurants</h2>
    <form id="search-form" method="POST">
      <label for="category">Choose a category:</label>
      <select name="category" id="category">
        <option value="">All</option>
        <option value="Cafe">Cafe</option>
        <option value="Italian">Italian</option>
        <option value="Asian">Asian</option>
        <option value="Pacific">Pacific</option>
      </select>
      <label for="keyword">Keyword:</label>
      <input type="text" id="keyword" name="keyword">
      <label for="distance">Distance (miles):</label>
      <input type="number" id="distance" name="distance" min="1" max="4">
      <button type="submit">Search</button>
    </form>
	<br />
    <div class="restaurant-container">
	
      <?php
	if ((!file_exists('restaurant_list.txt')) || (filesize('restaurant_list.txt')==0) ){
		echo "<p>No Restaurant Listing File Found</p>\n";
	}
	else {
		$restaurant_array=file("restaurant_list.txt");
		// Display Restaurant List
		//echo "<table  width =\"50%\">\n";
		
		$count= count($restaurant_array);
		// apply filter
		$category= isset($_POST['category']) ? $_POST['category'] : '';
		$distance = isset ($_POST['distance']) ? $_POST['distance'] : '';
		$keyword = isset($_POST['keyword']) ? $_POST['keyword'] : '';
		
		
		for ($i=0; $i<$count; ++$i){
			$currRestaurant = explode("~",$restaurant_array[$i]);
			
			$keyArray[]=$currRestaurant[0]; // add id to each restaurant
			
			//restaurant_id (0)~restaurant_name (1)~category(2)~Dish1(3)~Dish1Price(4)~Dish2(5)~Dish2Price(6)~Dish3(7)~Dish3Price(8)~Dish4(9)~Dish4Price(10)~rating(11)~distance(12)
			
			$valueArray[]=$currRestaurant[1]."~".$currRestaurant[2]."~".$currRestaurant[3]."~".$currRestaurant[4]."~".$currRestaurant[5]."~".$currRestaurant[6]."~".$currRestaurant[7]."~".$currRestaurant[8]."~".$currRestaurant[9]."~".$currRestaurant[10]."~".$currRestaurant[11]."~".$currRestaurant[12];
			}
		
		$keyRestArray = array_combine($keyArray,$valueArray);
			
	
		foreach ($keyRestArray as $restaurant){
			$currRestaurant = explode("~",$restaurant);
			echo "<tr>\n";
			if (($currRestaurant[1]==$category || $category == "") && ($currRestaurant[11]==$distance || $distance == "")
			&& ((strpos(strtolower($currRestaurant[0]),$keyword)!==false) || $keyword=="" )	
			){
			echo "<div class='restaurant'>";
			echo "<h3>".htmlentities($currRestaurant[0])."</h3>";
			echo "<p><small>".htmlentities($currRestaurant[1])."</small></p>";
			echo "<p>Rating: ".htmlentities($currRestaurant[10])."</p>";
            echo "<p>Distance (km): ".htmlentities($currRestaurant[11])."</p>";
		
			//add dish names using check box
			// Modify the form to include hidden inputs for dish prices
echo "
<form action=\"order.php\" method=\"POST\">
    <input type=\"hidden\" name=\"restaurant_name\" value =\"".htmlentities($currRestaurant[0])."\"/>
    <label for=\"Dish1\">
        <input type=\"checkbox\" id=\"Dish1\" name=\"option[]\" value=\"".htmlentities($currRestaurant[2])."\">
        ".htmlentities($currRestaurant[2])." ($".htmlentities($currRestaurant[3]).")
        <input type=\"hidden\" name=\"dish_price[".htmlentities($currRestaurant[2])."]\" value=\"".htmlentities($currRestaurant[3])."\"/>
    </label>
    <label for=\"Dish2\">
        <input type=\"checkbox\" id=\"Dish2\" name=\"option[]\" value=\"".htmlentities($currRestaurant[4])."\">
        ".htmlentities($currRestaurant[4])." ($".htmlentities($currRestaurant[5]).")
        <input type=\"hidden\" name=\"dish_price[".htmlentities($currRestaurant[4])."]\" value=\"".htmlentities($currRestaurant[5])."\"/>
    </label>
    <label for=\"Dish3\">
        <input type=\"checkbox\" id=\"Dish3\" name=\"option[]\" value=\"".htmlentities($currRestaurant[6])."\">
        ".htmlentities($currRestaurant[6])." ($".htmlentities($currRestaurant[7]).")
        <input type=\"hidden\" name=\"dish_price[".htmlentities($currRestaurant[6])."]\" value=\"".htmlentities($currRestaurant[7])."\"/>
    </label>
    <label for=\"Dish4\">
        <input type=\"checkbox\" id=\"Dish4\" name=\"option[]\" value=\"".htmlentities($currRestaurant[8])."\">
        ".htmlentities($currRestaurant[8])." ($".htmlentities($currRestaurant[9]).")
        <input type=\"hidden\" name=\"dish_price[".htmlentities($currRestaurant[8])."]\" value=\"".htmlentities($currRestaurant[9])."\"/>
    </label>
    <br>
    <button type=\"submit\" name=\"submit\">Order</button>
</form>
</div>";

		//	echo "Dish names <br />";
			
			
			//echo "</td>";
			//echo "</tr>\n";
			
			next($keyRestArray);}
			
		}
		
		//Display order button against each restaurant
		
		// Select restaurant
		
		//Select Dish
		
		//Place Order 
		
	}
	
?>

    </div>
  </main>
  <footer>
    <p>&copy; 2024 Get Delivered. All rights reserved.</p>
  </footer>
</body>
</html>
