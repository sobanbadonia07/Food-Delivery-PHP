// Function to calculate total price based on selected menu items
function calculateTotalPrice(menuItems) {
  // Define price for each menu item
  const menuPrices = {
    "Coffee": 2.5,
    "Pastries": 3,
    "Sandwiches": 5,
    "Bagels": 2,
    "Muffins": 2.5,
    "Cocktails": 6,
    "Bar Snacks": 4,
    "Dance Floor": 10,
    "Light Bites": 8,
    "Sushi": 12,
    "Noodles": 10,
    "Stir-Fry": 11,
    "Dim Sum": 9,
    "Ramen": 10,
    "General Tso's Chicken": 11
  };

  // Calculate total price by summing up the prices of selected menu items
  let totalPrice = 0;
  menuItems.forEach(item => {
    totalPrice += menuPrices[item.name] || 0; // Add price of each selected menu item
  });

  return totalPrice;
}

// Function to extract order details from the restaurant page
function getOrderDetailsFromRestaurantPage() {
  // Get the selected restaurant name
	const selectedRestaurantName = document.getElementById("selected-restaurant-name").textContent;
	

  // Get the selected menu items
  const selectedMenuItems = Array.from(document.querySelectorAll("input[name='menu-item']:checked"))
    .map(item => ({
      name: item.value
    }));
 localStorage.setItem("selectedRestaurantName", selectedRestaurantName);
  localStorage.setItem("selectedMenuItems", JSON.stringify(selectedMenuItems));

  return {
    restaurantName: selectedRestaurantName,
    menuItems: selectedMenuItems
  };
   
}
// Function to display order details on the order summary page
function displayOrderDetails() {
  // Retrieve order details from localStorage
  const selectedRestaurantName = localStorage.getItem("selectedRestaurantName");
  const selectedMenuItems = JSON.parse(localStorage.getItem("selectedMenuItems"));

  // Display restaurant name
  document.getElementById("display_restaurant").textContent = selectedRestaurantName;

  // Display menu items selected along with their prices
  const menuItemsList = document.getElementById("display_selected_items");
  let totalPrice = 0;
  menuItemsList.innerHTML = ""; // Clear previous content
  selectedMenuItems.forEach(item => {
    const li = document.createElement("li");
    li.textContent = `${item.name} - $${item.price.toFixed(2)}`; // Display item name and price
    menuItemsList.appendChild(li);
    totalPrice += item.price; // Add item price to total price
  });

  // Display total price
  document.getElementById("total-price").textContent = `$${totalPrice.toFixed(2)}`;
}

// Function to initialize the order summary page
function initializeOrderSummaryPage() {
  displayOrderDetails();
}

// Call initializeOrderSummaryPage function when the page loads
window.onload = initializeOrderSummaryPage;