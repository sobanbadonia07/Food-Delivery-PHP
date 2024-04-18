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
    header, main, footer {
      width: 100%;
    }
    main {
      flex-grow: 1; /* Takes up remaining vertical space */
    }
    footer {
      margin-top: auto; /* Pushes footer to the bottom */
    }
    .restaurant-container {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-around; /* Aligns items horizontally with equal space around them */
      padding: 20px 0; /* Add some padding for spacing */
    }
    .restaurant {
      width: 30%; /* Adjust width as needed */
      margin-bottom: 20px; /* Add some bottom margin for spacing */
      padding: 20px;
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
      background-color: #4CAF50;
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
    <form id="search-form" method="GET">
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
    <div class="restaurant-container">
      <?php
        if ((!file_exists('restaurant_list.txt')) || (filesize('restaurant_list.txt')==0) ){
          echo "<p>No Restaurant Listing File Found</p>\n";
        }
        else {
          $restaurant_array=file("restaurant_list.txt");
          // Display Restaurant List
          $count= count($restaurant_array);
          // apply filter
          $category= isset($_GET['category']) ? $_GET['category'] : '';
          $distance = isset ($_GET['distance']) ? $_GET['distance'] : '';
          $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
          for ($i=0; $i<$count; ++$i){
            $currRestaurant = explode("~",$restaurant_array[$i]);
            /*
            restaurant_id~restaurant_name~category~Dish1~Dish2~Dish3~Dish4~rating~distance
            */
            if (($currRestaurant[2]==$category || $category == "") && ($currRestaurant[8]==$distance || $distance == "") && ((strpos(strtolower($currRestaurant[0]),$keyword)!==false) || $keyword=="" )  ){
              echo "<div class='restaurant'>";
              echo "<h3>".htmlentities($currRestaurant[1])."</h3>";
              echo "<p>".htmlentities($currRestaurant[2])."</p>";
              echo "<p>Rating: ".htmlentities($currRestaurant[7])."</p>";
              echo "<p>Distance: ".htmlentities($currRestaurant[8])."</p>";
              echo "<form action=\"OrderDetail.html\" method=\"POST\">";
              echo "<label for=\"Dish1\">";
              echo "<input type=\"checkbox\" id=\"Dish1\" name=\"option1\" value=\"Option 1\">".htmlentities($currRestaurant[3]);
              echo "</label>";
              echo "<label for=\"Dish2\">";
              echo "<input type=\"checkbox\" id=\"Dish2\" name=\"option2\" value=\"Option 2\">".htmlentities($currRestaurant[4]);
              echo "</label>";
              echo "<label for=\"Dish3\">";
              echo "<input type=\"checkbox\" id=\"Dish3\" name=\"option3\" value=\"Option 3\">".htmlentities($currRestaurant[5]);
              echo "</label>";
              echo "<label for=\"Dish4\">";
              echo "<input type=\"checkbox\" id=\"Dish4\" name=\"option4\" value=\"Option 4\">".htmlentities($currRestaurant[6]);
              echo "</label>";
              echo "<br>";
              echo "<button type=\"submit\">Order</button>";
              echo "</form>";
              echo "</div>";
            }
          }
        }
      ?>
    </div>
  </main>
</body>
</html>
