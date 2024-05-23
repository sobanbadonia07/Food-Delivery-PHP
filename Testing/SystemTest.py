from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support.ui import Select
from selenium.webdriver.support import expected_conditions as EC
import unittest
import time

class TestWebsite(unittest.TestCase):
    def setUp(self):
        service = Service(executable_path="chromedriver.exe")
        self.driver = webdriver.Chrome(service=service)
        self.driver.get("http://localhost/food%20delivery%20v5/Home%20Page.html")
        self.base_url = "http://localhost/food%20delivery%20v5/"
        self.driver.implicitly_wait(10)

    def test_home_page_url(self):
        # UT1 - Home Page loading
        current_url = self.driver.current_url
        expected_url = "http://localhost/food%20delivery%20v5/Home%20Page.html"
        self.assertEqual(current_url, expected_url)
        time.sleep(5)

    def test_navigate_to_customer_page(self):
        # UT2 - Navigate to customer page
        self.driver.get("http://localhost/food%20delivery%20v5/Home%20Page.html")
        self.find_header_element()
        WebDriverWait(self.driver, 5).until(
            EC.presence_of_element_located((By.PARTIAL_LINK_TEXT, "Customer"))
        )
        link = self.driver.find_element(By.PARTIAL_LINK_TEXT, "Customer")
        link.click()
        time.sleep(5)

        # Check if the customer page URL is correct
        customer_page_url = self.driver.current_url
        expected_customer_page_url = "http://localhost/food%20delivery%20v5/customer.php"
        self.assertEqual(customer_page_url, expected_customer_page_url)

    def test_restaurants_category_search(self):
        # UT3 - Search for restaurants by category
        self.driver.get("http://localhost/food%20delivery%20v5/customer.php")
        WebDriverWait(self.driver, 5).until(
            EC.presence_of_element_located((By.ID, "category"))
        )

        self.search_by_category("Cafe")
        self.search_by_category("International")
        self.search_by_category("Asian")

    def search_by_category(self, category_name):
        self.driver.get("http://localhost/food%20delivery%20v5/customer.php")
        dropdown_element = self.driver.find_elements(By.TAG_NAME, "option")
        for option in dropdown_element:
            if option.text == category_name:
                option.click()
                break

        search_button = self.driver.find_element(By.XPATH, "/html/body/main/form/button")
        search_button.click()
        time.sleep(3)  # Wait for the search results to load

    def find_header_element(self):
        self.driver.get("http://localhost/food%20delivery%20v5/customer.php")
        WebDriverWait(self.driver, 5).until(
            EC.presence_of_element_located((By.XPATH, "/html/body/header/nav/ul/li[2]/a"))
            
        )
        self.driver.find_element(By.XPATH, "/html/body/header/nav/ul/li[2]/a")

    def test_search_restaurants_by_category(self):
        # UT4 - Search restaurants based on category
        self.driver.get("http://localhost/food%20delivery%20v5/customer.php")
        WebDriverWait(self.driver, 5).until(
            EC.presence_of_element_located((By.ID, "category"))
        )
        

        # Locate the dropdown element by its ID or XPath
        dropdown_element = self.driver.find_elements(By.TAG_NAME, "option")
        self.select_option_from_dropdown(dropdown_element, "Cafe")
        self.perform_search()

    def test_search_restaurants_by_keyword(self):
        # UT5 - Search restaurants by keyword
        self.driver.get("http://localhost/food%20delivery%20v5/customer.php")
        WebDriverWait(self.driver, 5).until(
            EC.presence_of_element_located((By.ID, "category"))
        )
        

        # Locate the dropdown element by its ID or XPath
        dropdown_element = self.driver.find_elements(By.TAG_NAME, "option")
        self.select_option_from_dropdown(dropdown_element, "Cafe")

        keyword_field = self.driver.find_element(By.ID, "keyword")
        keyword_text = "moon"
        keyword_field.send_keys(keyword_text)
        self.perform_search()

    def test_search_restaurants_by_distance(self):
        # UT6 - Search restaurants based on distance
        self.driver.get("http://localhost/food%20delivery%20v5/customer.php")
        WebDriverWait(self.driver, 5).until(
            EC.presence_of_element_located((By.ID, "category"))
        )
        # Locate the dropdown element by its ID or XPath
        dropdown_element = self.driver.find_elements(By.TAG_NAME, "option")
        self.select_option_from_dropdown(dropdown_element, "Asian")

        distance_element = self.driver.find_element(By.ID, "distance")
        distance_value = "2"
        distance_element.send_keys(distance_value)
        self.perform_search()

    def select_option_from_dropdown(self, dropdown_element, option_text):
        for option in dropdown_element:
            if option.text == option_text:
                option.click()
                break

    def perform_search(self):
        search_button = self.driver.find_element(By.XPATH, "/html/body/main/form/button")
        search_button.click()
        time.sleep(5)  # Wait for the search results to load

    def test_delivery_flow(self):
        driver = self.driver
        driver.get(self.base_url + "delivery.php")

        # Enter an order number
        order_number_input = driver.find_element(By.ID, "orderNumber")
        order_number_input.send_keys("12345")

        # Check the order
        check_order_button = driver.find_element(By.TAG_NAME, "button")
        check_order_button.click()

        # Verify that the order details are displayed
        order_details_table = driver.find_element(By.TAG_NAME, "table")
        self.assertIsNotNone(order_details_table)

        # Deliver the order
        deliver_button = driver.find_element(By.NAME, "deliver")
        deliver_button.click()

        # Verify that the order status is updated
        status_cell = driver.find_element(By.XPATH, "//td[text()='Delivered']")
        self.assertIsNotNone(status_cell)

    def test_track_order(self):
        driver = self.driver
        driver.get(self.base_url + "trackorder.php")

        # Enter an order number
        order_number_input = driver.find_element(By.ID, "orderNumber")
        order_number_input.send_keys("12345")

        # Submit the order number
        track_order_button = driver.find_element(By.TAG_NAME, "button")
        track_order_button.click()

        # Verify that the order status is displayed
        status_message = driver.find_element(By.TAG_NAME, "p")
        self.assertRegex(status_message.text, r"Current status: .+")

        # Submit a review (if the order is delivered)
        if "Delivered" in status_message.text:
            review_textarea = driver.find_element(By.ID, "review")
            review_textarea.send_keys("Great service!")
            submit_review_button = driver.find_element(By.NAME, "submitReview")
            submit_review_button.click()

            # Verify that the review submission is acknowledged
            review_message = driver.find_element(By.TAG_NAME, "p")
            self.assertEqual(review_message.text, "Thank you for your review!")

    def test_order_summary(self):
        driver = self.driver
        driver.get(self.base_url + "customer.php")

        # Click on the first restaurant card
        first_card = driver.find_element(By.CLASS_NAME, "card")
        first_card.click()

        # Select some menu items
        menu_items = driver.find_elements(By.CSS_SELECTOR, "input[type='checkbox']")
        for item in menu_items[:2]:
            item.click()

        # Submit the order form
        order_button = driver.find_element(By.NAME, "order")
        order_button.click()

        # Verify that the order summary page is displayed
        order_summary_heading = driver.find_element(By.TAG_NAME, "h2")
        self.assertEqual(order_summary_heading.text, "Order Summary")

    def test_order_confirmation(self):
        driver = self.driver
        driver.get(self.base_url + "OrderMessage.php?orderNumber=12345")

        # Verify that the order confirmation page is displayed
        confirmation_heading = driver.find_element(By.TAG_NAME, "h2")
        self.assertEqual(confirmation_heading.text, "Order Confirmation")

        # Verify the order number and estimated delivery time
        order_number_element = driver.find_element(By.TAG_NAME, "p")
        self.assertIn("Order Number: 12345", order_number_element.text)

        delivery_time_element = driver.find_element(By.XPATH, "//p[contains(text(), 'Estimated Delivery Time')]")
        self.assertIsNotNone(delivery_time_element)
    def tearDown(self):
        time.sleep(1)
        self.driver.quit()

if __name__ == '__main__':
    unittest.main()