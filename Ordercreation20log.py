

from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support.ui import Select
from selenium.webdriver.support import expected_conditions as EC
import os
import pytest
import time
import logging

# Configure logging
logging.basicConfig(filename='test_results.log', level=logging.INFO, format='%(asctime)s - %(levelname)s - %(message)s')
console_handler = logging.StreamHandler()
console_handler.setLevel(logging.INFO)
console_formatter = logging.Formatter('%(asctime)s - %(levelname)s - %(message)s')
console_handler.setFormatter(console_formatter)
logging.getLogger().addHandler(console_handler)



logging.info("Test session started")
#logging.basicConfig(filename='test_results.log', level=logging.INFO, format='%(asctime)s - %(levelname)s - %(message)s')

@pytest.fixture(scope="module")
def driver():
    service = Service(executable_path="chromedriver.exe")
    #service = Service(executable_path=os.environ.get('CHROMEDRIVER_PATH'))
    driver = webdriver.Chrome(service=service)
    driver.base_url = "http://localhost/food%20delivery%20v5/"
    driver.implicitly_wait(30)

    # Configure logging
    #logging.basicConfig(filename='test_results.log', level=logging.INFO, format='%(asctime)s - %(levelname)s - %(message)s')
    

    yield driver
    
    driver.quit()
    logging.info("Test session ended")

def test_search_restaurant(driver):
    try:
        driver.get(driver.base_url + "customer.php")
        logging.info("Navigated to Customer page")

        # Enter search keyword
        keyword_input = driver.find_element(By.ID, "keyword")
        keyword_input.send_keys("pizza")
        logging.info("Entered 'pizza'")

        # Enter distance
        distance_input = driver.find_element(By.ID, "distance")
        distance_input.send_keys("5")
        logging.info("Selected '5 as distance'")

         # Select category
        category_select = Select(driver.find_element(By.ID, "category"))
        category_select.select_by_visible_text("Cafe")
        logging.info("Selected 'Cafe' from the dropdown")

        # Submit the search form
        search_button = driver.find_element(By.TAG_NAME, "button")
        search_button.click()
        logging.info("Search button clicked")

        # Verify that search results are displayed
        cards = driver.find_elements(By.CLASS_NAME, "card")
        assert len(cards) > 0

        # ... (other test cases remain the same) ...
    except Exception as e:
        logging.error(f"An error occurred during the test case: {e}")

@pytest.mark.parametrize("run_count", range(1))
def test_order_flow_without_discount(driver, run_count):
    # Configure logging
    #logging.basicConfig(filename='test_results.log', level=logging.INFO)
    try:
        driver.get(driver.base_url + "customer.php")

        # Get all restaurant cards
        restaurant_cards = WebDriverWait(driver, 10).until(EC.presence_of_all_elements_located((By.CSS_SELECTOR, ".card")))
        logging.info("Test for Order flow without discount started")

        for card in restaurant_cards:
            # Select all menu items for the restaurant
            item_checkboxes = card.find_elements(By.CSS_SELECTOR, "input[type='checkbox']")
            for checkbox in item_checkboxes:
                checkbox.click()
                logging.info("Selected items from the menu")

            # Click the "Order" button
            order_button = card.find_element(By.CSS_SELECTOR, 'input[type="submit"]')
            order_button.click()
            logging.info("Clicked order button")

            # Verify order summary and select "No Discount"
            WebDriverWait(driver, 10).until(EC.title_contains("Order Summary"))
            no_discount_radio = driver.find_element(By.CSS_SELECTOR, 'input[name="discount"][value="0"]')
            no_discount_radio.click()
            logging.info("Selected 'No discount'")

            # Click "Apply Discount" button
            apply_discount_button = driver.find_element(By.CSS_SELECTOR, 'button[name="applyDiscount"]')
            apply_discount_button.click()
            logging.info("Clicked 'Apply discount")

            # Click "Proceed to Pay" button
            proceed_button = driver.find_element(By.CSS_SELECTOR, 'button[name="proceed"]')
            proceed_button.click()
            logging.info("Clicked Proceed to Pay")

            # Verify track order page and enter order number
            WebDriverWait(driver, 120).until(EC.title_contains("Track Order"))
            order_number_input = driver.find_element(By.CSS_SELECTOR, 'input[name="orderNumber"]')
            order_number = order_number_input.get_attribute("value")
            track_order_button = driver.find_element(By.CSS_SELECTOR, 'button[type="submit"]')
            track_order_button.click()
            logging.info("Entered order number to track the order")

            # Verify order status
            status_text = WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.TAG_NAME, "body"))).text
            assert "Order delivered." in status_text
            logging.info("Test run - Order flow without discount completed successfully.")

    except Exception as e:
        logging.error(f"An error occurred during the test case: {e}")

@pytest.mark.parametrize("run_count", range(1))
def test_order_flow_with_discount(driver, run_count):
    try:
        # Configure logging
        #logging.basicConfig(filename='test_results.log', level=logging.INFO)

        driver.get(driver.base_url + "customer.php")

        # Get all restaurant cards
        restaurant_cards = WebDriverWait(driver, 10).until(EC.presence_of_all_elements_located((By.CSS_SELECTOR, ".card")))
        logging.info("Test for Order flow without discount started")

        for card in restaurant_cards:
            # Select all menu items for the restaurant
            item_checkboxes = card.find_elements(By.CSS_SELECTOR, "input[type='checkbox']")
            for checkbox in item_checkboxes:
                checkbox.click()
                logging.info("Selected items from the menu")


            # Click the "Order" button
            order_button = card.find_element(By.CSS_SELECTOR, 'input[type="submit"]')
            order_button.click()
            logging.info("Clicked order button")

            # Verify order summary and select "15% Discount"
            WebDriverWait(driver, 10).until(EC.title_contains("Order Summary"))
            discount_radio = driver.find_element(By.CSS_SELECTOR, 'input[name="discount"][value="15"]')
            discount_radio.click()
            logging.info("Selected 'membership discount'")

            # Click "Apply Discount" button
            apply_discount_button = driver.find_element(By.CSS_SELECTOR, 'button[name="applyDiscount"]')
            apply_discount_button.click()
            logging.info("Clicked 'Apply discount")

            # Click "Proceed to Pay" button
            proceed_button = driver.find_element(By.CSS_SELECTOR, 'button[name="proceed"]')
            proceed_button.click()
            logging.info("Clicked Proceed to Pay")

            # Verify track order page and enter order number
            WebDriverWait(driver, 120).until(EC.title_contains("Track Order"))
            order_number_input = driver.find_element(By.CSS_SELECTOR, 'input[name="orderNumber"]')
            order_number = order_number_input.get_attribute("value")
            track_order_button = driver.find_element(By.CSS_SELECTOR, 'button[type="submit"]')
            track_order_button.click()
            logging.info("Entered order number to track the order")

            # Verify order status
            status_text = WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.TAG_NAME, "body"))).text
            assert "Order delivered." in status_text
            logging.info("Test run - Order flow with discount completed successfully.")
    except Exception as e:
        logging.error(f"An error occurred during the test case: {e}")

