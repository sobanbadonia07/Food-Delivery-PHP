<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Get Delivered</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fffdf5;
            color: #495057;
            margin: 0;
            padding-top: 10px;
            padding-bottom: 30px;
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
        .cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
        }
        .card {
            background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }
        .card img {
            width: 100%; 
            height: 150px; 
            object-fit: cover; 
            border-radius: 8px;
        }
        form {
            margin-top: 10px;
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
                <li><a href="trackorder.php">Track Orders</a></li>
				<li><a href="delivery.php" target="_self">Delivery</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <form method="GET">
            <label for="keyword">Keyword:</label>
            <input type="text" id="keyword" name="keyword">
            <label for="distance">Distance (km):</label>
            <input type="number" id="distance" name="distance" step="0.1" min="0">
            <label for="category">Category:</label>
            <select id="category" name="category">
                <option value="">All</option>
                <?php
                $categories = [];
                $fileInputs = fopen("rest.txt", "r");
                if ($fileInputs !== false) {
                    while (!feof($fileInputs)) {
                        $restName = trim(fgets($fileInputs));
                        if ($restName == "") continue;
                        $restCategory = trim(fgets($fileInputs));
                        $restDistance = trim(fgets($fileInputs));
                        $restEmail = trim(fgets($fileInputs));
                        $restPhone = trim(fgets($fileInputs));
                        $restCode = trim(fgets($fileInputs));
                        if (!in_array($restCategory, $categories)) {
                            $categories[] = $restCategory;
                        }
                    }
                    fclose($fileInputs);
                }
                foreach ($categories as $category) {
                    echo "<option value=\"$category\">" . htmlspecialchars($category) . "</option>";
                }
                ?>
            </select>
            <button type="submit">Search</button>
        </form>
        <div class="cards-container">
            <?php
            function searchMatch($keyword, $category, $distance, $name, $restCategory, $restDistance) {
                $match = true;
                if ($keyword && stripos($name, $keyword) === false) {
                    $match = false;
                }
                if ($category && $category != $restCategory) {
                    $match = false;
                }
                if ($distance && floatval($restDistance) > floatval($distance)) {
                    $match = false;
                }
                return $match;
            }

            $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
            $distance = isset($_GET['distance']) ? $_GET['distance'] : '';
            $category = isset($_GET['category']) ? $_GET['category'] : '';

            $fileInputs = fopen("rest.txt", "r");
            if ($fileInputs !== false) {
                while (!feof($fileInputs)) {
                    $restName = trim(fgets($fileInputs));
                    if ($restName == "") continue;
                    $restCategory = trim(fgets($fileInputs));
                    $restDistance = trim(fgets($fileInputs));
                    $restEmail = trim(fgets($fileInputs));
                    $restPhone = trim(fgets($fileInputs));
                    $restCode = trim(fgets($fileInputs));
                    $imageName = $restCode . ".jpg";

                    if (searchMatch($keyword, $category, $distance, $restName, $restCategory, $restDistance)) {
                        echo "<div class='card'>";
                        echo "<img src='$imageName'>";
                        echo "<h3>$restName</h3>";
                        echo "<p><small>Category: $restCategory
                         Distance: $restDistance km</small></p>";
                        echo "<form method='post' action='order.php'>";
                        echo "<table>";
                        $menuInputs = fopen("menu.txt", "r");
                        if ($menuInputs !== false) {
                            while (!feof($menuInputs)) {
                                $itemCode = trim(fgets($menuInputs));
                              if ($itemCode == "") continue;
                                $itemName = trim(fgets($menuInputs));
                                $itemPrice = trim(fgets($menuInputs));
                                $itemRest = trim(fgets($menuInputs));

                                if ($restCode == $itemRest) {
                                    echo "<tr>";
                                    echo "<td><input type='checkbox' name='selectedItems[]' value='$itemName|$itemPrice'></td>";
                                    echo "<td>" . ucfirst(htmlspecialchars($itemName)) . "</td>";
                                    echo "<td>$ " . htmlspecialchars($itemPrice) . "</td>";
                                    echo "</tr>";
                                }
                            }
                            fclose($menuInputs);
                        }
                        echo "</table>";
                        $orderNumber = rand(10000, 99999);
                        echo "<input type='hidden' name='restCode' value='$restCode'>";
                        echo "<input type='hidden' name='orderNumber' value='$orderNumber'>";
                        echo "<input type='submit' name='order' value='Order'>";
                        echo "</form>";
                        echo "</div>";
                    }
                }
                fclose($fileInputs);
            }
             
			
			?>
        </div>
    </main>
</body>
</html>
