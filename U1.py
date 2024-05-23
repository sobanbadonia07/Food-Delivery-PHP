from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support.ui import Select
from selenium.webdriver.support import expected_conditions as EC
import unittest
import os
import time

class TestPages(unittest.TestCase):
    def setUp(self):
        #service = Service(executable_path="chromedriver.exe")
        service = Service(executable_path=os.environ.get('CHROMEDRIVER_PATH'))
        self.driver = webdriver.Chrome(service=service)
        self.base_url = "http://localhost/food%20delivery%20v5/"
        self.driver.implicitly_wait(10)  # seconds

    def tearDown(self):
        time.sleep(2)
        self.driver.quit()

    def test_home_page(self):
        driver = self.driver
        driver.get(self.base_url + "Home%20Page.html")
        self.assertIn("Get Delivered", driver.title)
        body = WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.TAG_NAME, "body")))
        expected_content = [
            "Enjoy ordering your favorites from home",
            "Choose from a wide range of cuisines",
            "Monitor your order with real-time tracking",
            "Receive discounts as a loyal member",
        ]
        page_text = body.text
        for content in expected_content:
            self.assertIn(content, page_text)

    

    def test_customer_page(self):
        driver = self.driver
        driver.get(self.base_url + "customer.php")
        self.assertIn("Get Delivered", driver.title)
        card = WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.CSS_SELECTOR, ".card")))
        self.assertTrue(card)
    

    def test_order_page(self):
        driver = self.driver
        driver.get(self.base_url + "order.php")
        self.assertIn("Order Summary", driver.title)
        WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.CSS_SELECTOR, "input[name='orderNumber']")))
        WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.CSS_SELECTOR, "input[name='discount']")))
        WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.CSS_SELECTOR, "button[name='applyDiscount']")))
        WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.CSS_SELECTOR, "button[name='proceed']")))
    

    def test_restaurant_page(self):
        driver = self.driver
        driver.get(self.base_url + "restaurant.php")
        self.assertIn("Get Delivered", driver.title)
        WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.CSS_SELECTOR, "input[name='restNo']")))
        WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.CSS_SELECTOR, "input[name='newNO']")))
        WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.CSS_SELECTOR, "input[name='newName']")))
        WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.CSS_SELECTOR, "input[name='newPrice']")))
        WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.CSS_SELECTOR, "input[name='newRest']")))
    

    def test_track_order_page(self):
        driver = self.driver
        driver.get(self.base_url + "trackorder.php")
        self.assertIn("Track Order", driver.title)
        WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.CSS_SELECTOR, "input[name='orderNumber']")))
        WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.CSS_SELECTOR, "button[type='submit']")))
    


if __name__ == "__main__":
    unittest.main()
