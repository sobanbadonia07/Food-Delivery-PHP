from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support.ui import Select
from selenium.webdriver.support import expected_conditions as EC
import unittest
import time
import os

class TestWebsiteIntegration(unittest.TestCase):
    def setUp(self):
        #service = Service(executable_path="chromedriver.exe")
        service = Service(executable_path=os.environ.get('CHROMEDRIVER_PATH'))
        self.driver = webdriver.Chrome(service=service)
        self.base_url = "http://localhost/food%20delivery%20v5/"
        self.driver.implicitly_wait(10)

    def tearDown(self):
        time.sleep(1)
        self.driver.quit()

    def test_end_to_end_flow(self):
        # Home Page
        self.driver.get(self.base_url + "Home%20Page.html")
        self.assertIn("Get Delivered", self.driver.title)

        # Navigate to Customer Page
        self.find_header_element()
        WebDriverWait(self.driver, 5).until(
            EC.presence_of_element_located((By.PARTIAL_LINK_TEXT, "Customer"))
        )
        link = self.driver.find_element(By.PARTIAL_LINK_TEXT, "Customer")
        link.click()
        time.sleep(5)

        # Search for restaurants by category
        WebDriverWait(self.driver, 5).until(
            EC.presence_of_element_located((By.ID, "category"))
        )
        self.search_by_category("Cafe")

        # Select menu items and place order
        first_card = self.driver.find_element(By.CLASS_NAME, "card")
        first_card.click()
        menu_items = self.driver.find_elements(By.CSS_SELECTOR, "input[type='checkbox']")
        for item in menu_items[:2]:
            item.click()
        order_button = self.driver.find_element(By.NAME, "order")
        order_button.click()

        # Verify Order Summary page
        order_summary_heading = self.driver.find_element(By.TAG_NAME, "h2")
        self.assertEqual(order_summary_heading.text, "Order Summary")

        # Apply discount and proceed to payment
        discount_radio = self.driver.find_element(By.CSS_SELECTOR, "input[name='discount'][value='15']")
        discount_radio.click()
        apply_discount_button = self.driver.find_element(By.NAME, "applyDiscount")
        apply_discount_button.click()
        proceed_button = self.driver.find_element(By.NAME, "proceed")
        proceed_button.click()

        # Verify Order Confirmation page
        confirmation_heading = self.driver.find_element(By.TAG_NAME, "h2")
        self.assertEqual(confirmation_heading.text, "Order Confirmation")
        order_number_element = self.driver.find_element(By.TAG_NAME, "p")
        self.assertRegex(order_number_element.text, r"Order Number: \d+")
        delivery_time_element = self.driver.find_element(By.XPATH, "//p[contains(text(), 'Estimated Delivery Time')]")
        self.assertIsNotNone(delivery_time_element)

        # Navigate to Track Orders page
        self.find_header_element()
        WebDriverWait(self.driver, 5).until(
            EC.presence_of_element_located((By.PARTIAL_LINK_TEXT, "Track Orders"))
        )
        link = self.driver.find_element(By.PARTIAL_LINK_TEXT, "Track Orders")
        link.click()
        time.sleep(5)

        # Track the order and submit a review
        order_number_input = self.driver.find_element(By.ID, "orderNumber")
        order_number_input.send_keys(order_number_element.text.split(": ")[1])
        track_order_button = self.driver.find_element(By.TAG_NAME, "button")
        track_order_button.click()
        status_message = self.driver.find_element(By.TAG_NAME, "p")
        self.assertRegex(status_message.text, r"Current status: .+")

        if "Delivered" in status_message.text:
            review_textarea = self.driver.find_element(By.ID, "review")
            review_textarea.send_keys("Great service!")
            submit_review_button = self.driver.find_element(By.NAME, "submitReview")
            submit_review_button.click()
            review_message = self.driver.find_element(By.TAG_NAME, "p")
            self.assertEqual(review_message.text, "Thank you for your review!")

    def find_header_element(self):
        self.driver.get("http://localhost/food%20delivery%20v5/customer.php")
        WebDriverWait(self.driver, 5).until(
            EC.presence_of_element_located((By.XPATH, "/html/body/header/nav/ul/li[2]/a"))
        )
        self.driver.find_element(By.XPATH, "/html/body/header/nav/ul/li[2]/a")

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

if __name__ == '__main__':
    unittest.main()
