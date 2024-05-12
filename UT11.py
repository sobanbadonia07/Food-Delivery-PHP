from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
import unittest
import time


class TestWebsite(unittest.TestCase):
    def setUp(self):
        service = Service(executable_path="chromedriver.exe")
        self.driver = webdriver.Chrome(service=service)
        self.driver.get("http://localhost/Food-Delivery-PHP-main/Home%20Page.html")

    def test_home_page_url(self):
        # UT1 - Home Page loading
        current_url = self.driver.current_url
        expected_url = "http://localhost/Food-Delivery-PHP-main/Home%20Page.html"
        self.assertEqual(current_url, expected_url)

    def test_navigate_to_customer_page(self):
    # UT2 - Navigate to customer page
        WebDriverWait(self.driver, 5).until(
        EC.presence_of_element_located((By.XPATH, "/html/body/header/h1"))
        )
        input_element = self.driver.find_element(By.XPATH, "/html/body/header/h1")
    # input_element.clear()
    # input_element.send_keys("tech with tim" + Keys.ENTER)
        WebDriverWait(self.driver, 5).until(
        EC.presence_of_element_located((By.PARTIAL_LINK_TEXT, "Customer"))
        )
        link = self.driver.find_element(By.PARTIAL_LINK_TEXT, "Customer")
        link.click()

    # Wait for the customer page to load
        WebDriverWait(self.driver, 10).until(
        EC.url_contains("customer.php")
        )

    # Check if the customer page URL is correct
        customer_page_url = self.driver.current_url
        expected_customer_page_url = "http://localhost/Food-Delivery-PHP-main/customer.php"
        self.assertEqual(customer_page_url, expected_customer_page_url)

    # Additional assertions to verify the customer page loaded correctly
        customer_page_title = self.driver.title
        expected_customer_page_title = "Customer Page"
        self.assertEqual(customer_page_title, expected_customer_page_title)

    # Check if a specific element is present on the customer page
        customer_page_element = WebDriverWait(self.driver, 10).until(
        EC.presence_of_element_located((By.ID, "customer-page-element"))
        )
        self.assertIsNotNone(customer_page_element)


    def tearDown(self):
        self.driver.quit()

if __name__ == '__main__':
    unittest.main()