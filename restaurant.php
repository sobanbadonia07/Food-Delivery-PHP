<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Get Delivered</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #fffdf5;
	  color:#2b8a3e;
      margin: 0;
      padding-top: 10px;
	  padding-bottom: 30px;
	  font-weight:bold;
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
    
    
	div img {
      width: 100px;
      height: 100px;
	  padding:10px;
	  border-radius:20px;
    }
    
	table { 
	  width: 50%; border-collapse: collapse; 
	}
	
	th, td { 
	  padding: 8px; 
	  text-align: left; 
	  border: 1px solid #2b8a3e; 
	}
	
	input[type="text"]{
	  background-color: #fffdf5;
	}
	
	input[type="submit"]{
	  background-color: #fffdf5;
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
      </ul>
    </nav>
  </header>
  <main>
    
    <img src="rest.jpg" alt="Delivery" width="500" align="left" style="padding-right: 100px;">
		<form action="restaurant.php" method="post">
		<p><label>Enter the Restaurant Number: <input type="text" name="restNo"/></label></p>
		<p><input type="submit" value="Search"/></p>
	</form>
	<h3>Restaurant details</h3>
	<table border="1">
		<tr>
			<td>Restaurant Name</td>
			<td>Seller Email</td>
			<td>Seller Phone</td>
			<td>Restaurant code</td>
		</tr>
		
		<?php
			if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["restNo"])) {
				$restNo=$_POST["restNo"];
				$fileInputs = fopen("rest.txt", "r");
				while (!feof($fileInputs)){
					$restName = rtrim(fgets($fileInputs), "\r\n");
					if ($restName == "") continue; 
					$restEmail = rtrim(fgets($fileInputs), "\r\n");
					$restPhone = rtrim(fgets($fileInputs), "\r\n");
					$restCode = rtrim(fgets($fileInputs), "\r\n");

					if($restNo==$restCode){
						echo "<tr>";
						echo "<td> {$restName} </td>";
						echo "<td> {$restEmail} </td>";
						echo "<td> {$restPhone} </td>";
						echo "<td> {$restCode} </td>";
						echo "</tr>";
					}
				}
				fclose($fileInputs);
			}
		?>
	</table>
	
	<form method="post" action="restaurant.php">
		<input type="hidden" name="restNo" value="<?php echo isset($_POST['restNo']) ? $_POST['restNo'] : ''; ?>" />
        <h2>Add menu item</h2>
		<table>
		<tr>
		<td>Item Number</td>
		<td><input type="text" name="newNO" required></td>
		</tr>
		<tr>
		<td>Item Name</td>
		<td><input type="text" name="newName" required></td>
		</tr>
        <tr>
		<td>Item Price</td>
		<td><input type="text" name="newPrice" required></td>
		</tr>
        <tr>
		<td>Restaurant Code</td>
		<td><input type="text" name="newRest" required></td>
		</tr>
        <tr>
		<td><input type="submit" value="Add Item"></td>
		</tr>
		</table>
    </form>
	
	<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["newNO"], $_POST["newName"], $_POST["newPrice"], $_POST["newRest"])) {
        $itemNO = $_POST["newNO"];
        $itemName = $_POST["newName"];
        $itemPrice = $_POST["newPrice"];
        $itemCode = $_POST["newRest"];

        $newFile = fopen("menu.txt", "a");
		fwrite($newFile, "\n");
		fwrite($newFile, $itemNO."\n");
		fwrite($newFile, $itemName."\n");
		fwrite($newFile, $itemPrice."\n");
		fwrite($newFile, $itemCode."\n");
		fclose($newFile);
	}
	?>
	
	<h3>Restaurant Menu</h3>
	<table border="1">
		<tr>
				<td>Item Number</td>
				<td>Item Name</td>
				<td>Item Price</td>
				<td>Restaurant code</td>
		</tr>
		
		<?php
			if (isset($_POST["restNo"])) {
				$restNo = $_POST["restNo"];
				$menuInputs = fopen("menu.txt", "r");
				while (!feof($menuInputs)){
					$itemNO = rtrim(fgets($menuInputs), "\r\n");
					if ($itemNO == "") continue;
					$itemName = rtrim(fgets($menuInputs), "\r\n");
					$itemPrice = rtrim(fgets($menuInputs), "\r\n");
					$itemCode = rtrim(fgets($menuInputs), "\r\n");

					if($restNo == $itemCode){
						echo "<tr>";
						echo "<td> {$itemNO} </td>";
						echo "<td> {$itemName} </td>";
						echo "<td> {$itemPrice} </td>";
						echo "<td> {$itemCode} </td>";
						echo "</tr>";
					}
				}
				fclose($menuInputs);
			}
		?>
	</table>
  </main>
</body>
</html>
