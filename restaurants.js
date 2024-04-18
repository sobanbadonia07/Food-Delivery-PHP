/*// Sample restaurant data
const restaurants = [
  {
    name: "Café de Paris",
    category: "Cafes",
    distance: 2,
    ratings: 4.5,
    address: "123 Main Street, Cityville",
    menu: ["Coffee", "Pastries", "Sandwiches"]
  },
  {
    name: "Morning Brew",
    category: "Cafes",
    distance: 1.5,
    ratings: 4.3,
    address: "456 Elm Avenue, Townsville",
    menu: ["Coffee", "Bagels", "Muffins"]
  },
  {
    name: "Night Owl Lounge",
    category: "Clubs",
    distance: 3,
    ratings: 4.0,
    address: "456 Nightlife Avenue, Metropolis",
    menu: ["Cocktails", "Bar Snacks", "Dance Floor"]
  },
  {
    name: "Moonlight Dance Club",
    category: "Clubs",
    distance: 2.5,
    ratings: 4.2,
    address: "789 Dancing Road, Groovetown",
    menu: ["Cocktails", "Dance Floor", "Light Bites"]
  },
  {
    name: "Asian Fusion Palace",
    category: "Asian Food",
    distance: 1,
    ratings: 4.8,
    address: "789 Chinatown Road, Fusiontown",
    menu: ["Sushi", "Noodles", "Stir-Fry"]
  },
  {
    name: "Dragon Wok",
    category: "Asian Food",
    distance: 2,
    ratings: 4.6,
    address: "321 Dragon Avenue, Woksville",
    menu: ["Dim Sum", "Ramen", "General Tso's Chicken"]
  }
];

// Function to display restaurant data
function displayRestaurants() {
  const restaurantList = document.getElementById("restaurant-list");
  restaurantList.innerHTML = "";
  restaurants.forEach(restaurant => {
    const item = document.createElement("div");
    item.classList.add("restaurant-item");
    item.innerHTML = `
      <h3>${restaurant.name}</h3>
      <p><strong>Category:</strong> ${restaurant.category}</p>
      <p><strong>Distance:</strong> ${restaurant.distance} miles</p>
      <p><strong>Ratings:</strong> ${restaurant.ratings}</p>
      <p><strong>Address:</strong> ${restaurant.address}</p>
      <button class="order-button" data-restaurant-name="${restaurant.name}" data-menu="${restaurant.menu.join(',')}">Order</button>
    `;
    restaurantList.appendChild(item);
  });
}

// Function to filter restaurants based on category and keyword
function filterRestaurants(category, keyword, distance) {
  const filteredRestaurants = restaurants.filter(restaurant => {
    return (
      (category === "" || restaurant.category === category) &&
      (keyword === "" || restaurant.name.toLowerCase().includes(keyword.toLowerCase())) &&
      (distance === "" || restaurant.distance <= distance)
    );
  });
  displayRestaurants(filteredRestaurants);
}


// Function to place order
function placeOrder() {
  const selectedRestaurantName = document.getElementById("selected-restaurant-name").textContent;
  const selectedMenuItems = Array.from(document.querySelectorAll("input[name='menu-item']:checked"))
    .map(item => item.value);
  // Perform order processing here (e.g., send order to server)
  alert(`Order placed at ${selectedRestaurantName} with items: ${selectedMenuItems.join(", ")}`);
  // Redirect to order page
  window.location.href = "order.html";
}

// Call displayRestaurants function when the page loads
window.onload = function() {
  displayRestaurants();
}

// Event listener for search form submission
document.getElementById("search-form").addEventListener("submit", function(event) {
  event.preventDefault();
  const category = document.getElementById("category").value;
  const keyword = document.getElementById("keyword").value.toLowerCase();
  const distance = document.getElementById("distance").value;
  filterRestaurants(category, keyword, distance);
});

// Event listener for order button click
document.addEventListener("click", function(event) {
  if (event.target && event.target.classList.contains("order-button")) {
    const menu = event.target.dataset.menu.split(',');
    showMenu(event.target.dataset.restaurantName, menu);
  }
});

// Function to show menu for selected restaurant
function showMenu(restaurantName, menu) {
  document.getElementById("selected-restaurant-name").textContent = restaurantName;
  const menuItems = menu.map(item => `
    <label><input type="checkbox" name="menu-item" value="${item}">${item}</label>
  `).join("");
  document.getElementById("menu-items").innerHTML = menuItems;
  document.getElementById("menu").style.display = "block";
  document.getElementById("order-button").style.display = "block";
}

// Event listener for placing order
document.getElementById("order-form").addEventListener("submit", function(event) {
  event.preventDefault();
  placeOrder();
});




// Event listener for search form submission
document.getElementById("search-form").addEventListener("submit", function(event) {
  event.preventDefault();
  const category = document.getElementById("category").value;
  const keyword = document.getElementById("keyword").value.toLowerCase();
  const distance = document.getElementById("distance").value;
  filterRestaurants(category, keyword, distance);
});

//////////////////////////////////// */
// Sample restaurant data
const restaurants = [
  {
    name: "Café de Paris",
    category: "Cafes",
    distance: 2,
    ratings: 4.5,
    address: "123 Main Street, Cityville",
    menu: ["Coffee", "Pastries", "Sandwiches"]
  },
  {
    name: "Morning Brew",
    category: "Cafes",
    distance: 1.5,
    ratings: 4.3,
    address: "456 Elm Avenue, Townsville",
    menu: ["Coffee", "Bagels", "Muffins"]
  },
  {
    name: "Night Owl Lounge",
    category: "Clubs",
    distance: 3,
    ratings: 4.0,
    address: "456 Nightlife Avenue, Metropolis",
    menu: ["Cocktails", "Bar Snacks", "Dance Floor"]
  },
  {
    name: "Moonlight Dance Club",
    category: "Clubs",
    distance: 2.5,
    ratings: 4.2,
    address: "789 Dancing Road, Groovetown",
    menu: ["Cocktails", "Dance Floor", "Light Bites"]
  },
  {
    name: "Asian Fusion Palace",
    category: "Asian Food",
    distance: 1,
    ratings: 4.8,
    address: "789 Chinatown Road, Fusiontown",
    menu: ["Sushi", "Noodles", "Stir-Fry"]
  },
  {
    name: "Dragon Wok",
    category: "Asian Food",
    distance: 2,
    ratings: 4.6,
    address: "321 Dragon Avenue, Woksville",
    menu: ["Dim Sum", "Ramen", "General Tso's Chicken"]
  }
];

// Function to display restaurant data
function displayRestaurants(restaurantsToShow) {
  const restaurantList = document.getElementById("restaurant-list");
  restaurantList.innerHTML = "";
  restaurantsToShow.forEach(restaurant => {
    const item = document.createElement("div");
    item.classList.add("restaurant-item");
    item.innerHTML = `
      <h3>${restaurant.name}</h3>
      <p><strong>Category:</strong> ${restaurant.category}</p>
      <p><strong>Distance:</strong> ${restaurant.distance} miles</p>
      <p><strong>Ratings:</strong> ${restaurant.ratings}</p>
      <p><strong>Address:</strong> ${restaurant.address}</p>
      <button class="order-button" data-restaurant-name="${restaurant.name}" data-menu="${restaurant.menu.join(',')}">Order</button>
    `;
    restaurantList.appendChild(item);
  });
}

// Function to filter restaurants based on category and keyword
function filterRestaurants(category, keyword, distance) {
  const filteredRestaurants = restaurants.filter(restaurant => {
    return (
      (category === "" || restaurant.category === category) &&
      (keyword === "" || restaurant.name.toLowerCase().includes(keyword.toLowerCase())) &&
      (distance === "" || restaurant.distance <= distance)
    );
  });
  displayRestaurants(filteredRestaurants);
}

// Call displayRestaurants function when the page loads
window.onload = function() {
  displayRestaurants(restaurants);
}

// Event listener for search form submission
document.getElementById("search-form").addEventListener("submit", function(event) {
  event.preventDefault();
  const category = document.getElementById("category").value;
  const keyword = document.getElementById("keyword").value.toLowerCase();
  const distance = document.getElementById("distance").value;
  filterRestaurants(category, keyword, distance);
});

// Event listener for order button click
document.addEventListener("click", function(event) {
  if (event.target && event.target.classList.contains("order-button")) {
    const menu = event.target.dataset.menu.split(',');
    showMenu(event.target.dataset.restaurantName, menu);
  }
});

// Function to show menu for selected restaurant
function showMenu(restaurantName, menu) {
  document.getElementById("selected-restaurant-name").textContent = restaurantName;
  const menuItems = menu.map(item => `
    <label><input type="checkbox" name="menu-item" value="${item}">${item}</label>
  `).join("");
  document.getElementById("menu-items").innerHTML = menuItems;
  document.getElementById("menu").style.display = "block";
  document.getElementById("order-button").style.display = "block";
}

// Function to place order
function placeOrder() {
  const selectedRestaurantName = document.getElementById("selected-restaurant-name").textContent;
  const selectedMenuItems = Array.from(document.querySelectorAll("input[name='menu-item']:checked"))
    .map(item => item.value);
  // Perform order processing here (e.g., send order to server)
  alert(`Order placed at ${selectedRestaurantName} with items: ${selectedMenuItems.join(", ")}`);
  // Redirect to order page
  window.location.href = "order.html";
  return selectedMenuItems;
  return selectedRestaurantName;
}

// Event listener for placing order
document.getElementById("order-form").addEventListener("submit", function(event) {
  event.preventDefault();
  placeOrder();
});


// Event listener for search form submission
document.getElementById("search-form").addEventListener("submit", function(event) {
  event.preventDefault();
  const category = document.getElementById("category").value;
  const keyword = document.getElementById("keyword").value.toLowerCase();
  const distance = document.getElementById("distance").value;
  filterRestaurants(category, keyword, distance);
});
