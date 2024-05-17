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
            </ul>
        </nav>
    </header>
    <main>
        <div class="cards-container">
            <?php
            $fileInputs = fopen("rest.txt", "r");
            if ($fileInputs !== false) {
                while (!feof($fileInputs)) {
                    $restName = trim(fgets($fileInputs));
                    if ($restName == "") continue;
                    $restEmail = trim(fgets($fileInputs));
                    $restPhone = trim(fgets($fileInputs));
                    $restCode = trim(fgets($fileInputs));
                    $imageName = $restCode . ".jpg";

                    echo "<div class='card'>";
                    echo "<img src='$imageName'>";
                    echo "<h3>$restName</h3>";
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
                                echo "<td>" . htmlspecialchars($itemName) . "</td>";
                                echo "<td>" . htmlspecialchars($itemPrice) . "</td>";
                                echo "</tr>";
                            }
                        }
                        fclose($menuInputs);
                    }
                    echo "</table>";
                    $orderNumber = rand(10000, 99999);
                    echo "<input type='hidden' name='restCode' value='$restCode'>";
                    echo "<input type='hidden' name='orderNumber' value='$orderNumber'>";
                    echo "<input type='submit' value='Order'>";
                    echo "</form>";
                    echo "</div>";
                }
                fclose($fileInputs);
            }
            ?>
        </div>
    </main>
</body>
</html>
