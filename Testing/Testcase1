import unittest
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

class TestRestaurantPage(unittest.TestCase):

    def setUp(self):
        self.driver = webdriver.Chrome()
        self.driver.get("path_to_your_restaurant_page.php")

    def test_select_restaurant_and_order(self):
        # Selecting a restaurant (you may need to adjust the locator based on your HTML structure)
        restaurant_name = "Example Restaurant"  # Replace with the name of the restaurant you want to select
        restaurant_element = self.driver.find_element(By.XPATH, f"//h3[text()='{restaurant_name}']")
        restaurant_element.click()

        # Checking if the order form is displayed
        order_form_present = WebDriverWait(self.driver, 10).until(
            EC.visibility_of_element_located((By.XPATH, "//form[@action='order.php']"))
        )
        self.assertTrue(order_form_present)

        # Selecting dishes (assuming the dish checkboxes have specific IDs)
        dish1_checkbox = self.driver.find_element(By.ID, "Dish1")
        dish1_checkbox.click()

        # Submitting the order
        submit_button = self.driver.find_element(By.XPATH, "//button[@name='submit']")
        submit_button.click()

        # Checking if the order was successful (you may need to adjust this based on your application)
        order_confirmation_present = WebDriverWait(self.driver, 10).until(
            EC.visibility_of_element_located((By.XPATH, "//div[@id='order-confirmation']"))
        )
        self.assertTrue(order_confirmation_present)

    def tearDown(self):
        self.driver.quit()

if __name__ == "__main__":
    unittest.main()
