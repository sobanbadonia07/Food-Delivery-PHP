from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
import pytest
import logging
import random

# Configure the logger
logger = logging.getLogger(__name__)
logger.setLevel(logging.INFO)
formatter = logging.Formatter('%(asctime)s - %(levelname)s - %(message)s')

# Create a file handler
file_handler = logging.FileHandler('test_log.txt')
file_handler.setFormatter(formatter)
logger.addHandler(file_handler)

@pytest.fixture(scope="module")
def driver():
    service = Service(executable_path="chromedriver.exe")
    driver = webdriver.Chrome(service=service)
    driver.implicitly_wait(10)
    yield driver
    driver.quit()

@pytest.mark.usefixtures("driver")
class TestWebsite:
    base_url = "http://localhost/food%20delivery%20v5/"

    def test_all_restaurants(self, driver):
        driver.get(self.base_url + "customer.php")
        restaurant_links = driver.find_elements(By.CSS_SELECTOR, "a.restaurant-link")

        for restaurant_link in restaurant_links:
            restaurant_name = restaurant_link.text
            logger.info(f"Testing restaurant: {restaurant_name}")

            restaurant_link.click()
            self.test_restaurant_dishes(driver)
            self.place_order_with_discount(driver, restaurant_name)
            self.place_order_without_discount(driver, restaurant_name)

            driver.back()

    def test_restaurant_dishes(self, driver):
        dish_links = driver.find_elements(By.CSS_SELECTOR, "a.dish-link")

        for dish_link in dish_links:
            dish_name = dish_link.text
            logger.info(f"Checking dish: {dish_name}")

            # Add additional assertions or checks for each dish as needed

    def place_order_with_discount(self, driver, restaurant_name):
        # Apply discount code
        discount_input = driver.find_element(By.ID, "discount")
        discount_code = "DISCOUNT10"
        discount_input.send_keys(discount_code)

        # Select random dishes
        dish_checkboxes = driver.find_elements(By.CSS_SELECTOR, "input[type='checkbox']")
        random.shuffle(dish_checkboxes)
        selected_dishes = dish_checkboxes[:random.randint(1, len(dish_checkboxes))]
        for dish in selected_dishes:
            dish.click()

        # Place the order
        place_order_button = driver.find_element(By.ID, "placeOrder")
        place_order_button.click()

        # Get the order number
        order_number = driver.find_element(By.ID, "orderNumber").text
        logger.info(f"Order placed for {restaurant_name} with discount. Order number: {order_number}")

    def place_order_without_discount(self, driver, restaurant_name):
        # Select random dishes
        dish_checkboxes = driver.find_elements(By.CSS_SELECTOR, "input[type='checkbox']")
        random.shuffle(dish_checkboxes)
        selected_dishes = dish_checkboxes[:random.randint(1, len(dish_checkboxes))]
        for dish in selected_dishes:
            dish.click()

        # Place the order without discount
        place_order_button = driver.find_element(By.ID, "placeOrder")
        place_order_button.click()

        # Get the order number
        order_number = driver.find_element(By.ID, "orderNumber").text
        logger.info(f"Order placed for {restaurant_name} without discount. Order number: {order_number}")

if __name__ == "__main__":
    pytest.main()