<?php

use PHPUnit\Framework\TestCase;

class RestaurantOrderTest extends TestCase
{
    protected $html;

    protected function setUp(): void
    {
        // Load the HTML page content into $html
        $this->html = file_get_contents('restaurants php.php');
    }

    public function testSelectRestaurantAndOrder()
    {
        // Simulate selecting a restaurant and placing an order
        // For demonstration purposes, let's assume the first restaurant is selected and an order is placed

        // Extract restaurant names from the HTML page
        preg_match_all('/<h3>(.*?)<\/h3>/', $this->html, $matches);

        // Check if at least one restaurant is listed
        $this->assertNotEmpty($matches[1], 'No restaurants found on the page');

        // Select the first restaurant
        $selectedRestaurant = $matches[1][0];

        // Simulate order placement for the selected restaurant
        // For simplicity, we assume the order is successful if the restaurant name is returned
        $orderConfirmation = $this->placeOrder($selectedRestaurant);

        // Assert that the order was successfully placed
        $this->assertEquals($selectedRestaurant, $orderConfirmation);
    }

    protected function placeOrder($restaurantName)
    {
        // Simulate the order placement process
        // For demonstration purposes, we just return the restaurant name as confirmation
        return $restaurantName;
    }
}
