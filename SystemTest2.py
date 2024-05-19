from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
import pytest

@pytest.fixture(scope="module")
def driver():
    service = Service(executable_path="chromedriver.exe")
    driver = webdriver.Chrome(service=service)
    driver.implicitly_wait(10)
    yield driver
    driver.quit()

@pytest.mark.usefixtures("driver")
class TestWebsite:
    def test_home_page_url(self, driver):
        # UT1 - Home Page loading
        driver.get("http://localhost/food%20delivery%20v5/Home%20Page.html")
        current_url = driver.current_url
        expected_url = "http://localhost/food%20delivery%20v5/Home%20Page.html"
        assert current_url == expected_url

    def test_navigate_to_customer_page(self, driver):
        # UT2 - Navigate to customer page
        driver.get("http://localhost/food%20delivery%20v5/Home%20Page.html")
        self.find_header_element(driver)
        WebDriverWait(driver, 5).until(
            EC.presence_of_element_located((By.PARTIAL_LINK_TEXT, "Customer"))
        )
        link = driver.find_element(By.PARTIAL_LINK_TEXT, "Customer")
        link.click()

        # Check if the customer page URL is correct
        customer_page_url = driver.current_url
        expected_customer_page_url = "http://localhost/food%20delivery%20v5/customer.php"
        assert customer_page_url == expected_customer_page_url

    def test_restaurants_category_search(self, driver):
        # UT3 - Search for restaurants by category
        driver.get("http://localhost/food%20delivery%20v5/customer.php")
        WebDriverWait(driver, 5).until(
            EC.presence_of_element_located((By.ID, "category"))
        )

        self.search_by_category(driver, "Cafe")
        self.search_by_category(driver, "International")
        self.search_by_category(driver, "Asian")

    def search_by_category(self, driver, category_name):
        driver.get("http://localhost/food%20delivery%20v5/customer.php")
        dropdown_element = driver.find_elements(By.TAG_NAME, "option")
        for option in dropdown_element:
            if option.text == category_name:
                option.click()
                break

        search_button = driver.find_element(By.XPATH, "/html/body/main/form/button")
        search_button.click()

    def find_header_element(self, driver):
        driver.get("http://localhost/food%20delivery%20v5/customer.php")
        WebDriverWait(driver, 5).until(
            EC.presence_of_element_located((By.XPATH, "/html/body/header/nav/ul/li[2]/a"))
        )
        driver.find_element(By.XPATH, "/html/body/header/nav/ul/li[2]/a")

    def test_search_restaurants_by_category(self, driver):
        # UT4 - Search restaurants based on category
        driver.get("http://localhost/food%20delivery%20v5/customer.php")
        WebDriverWait(driver, 5).until(
            EC.presence_of_element_located((By.ID, "category"))
        )

        # Locate the dropdown element by its ID or XPath
        dropdown_element = driver.find_elements(By.TAG_NAME, "option")
        self.select_option_from_dropdown(dropdown_element, driver, "Cafe")
        self.perform_search(driver)

    def test_search_restaurants_by_keyword(self, driver):
        # UT5 - Search restaurants by keyword
        driver.get("http://localhost/food%20delivery%20v5/customer.php")
        WebDriverWait(driver, 5).until(
            EC.presence_of_element_located((By.ID, "category"))
        )

        # Locate the dropdown element by its ID or XPath
        dropdown_element = driver.find_elements(By.TAG_NAME, "option")
        self.select_option_from_dropdown(dropdown_element, driver, "Cafe")

        keyword_field = driver.find_element(By.ID, "keyword")
        keyword_text = "moon"
        keyword_field.send_keys(keyword_text)
        self.perform_search(driver)

    def test_search_restaurants_by_distance(self, driver):
        # UT6 - Search restaurants based on distance
        driver.get("http://localhost/food%20delivery%20v5/customer.php")
        WebDriverWait(driver, 5).until(
            EC.presence_of_element_located((By.ID, "category"))
        )
        # Locate the dropdown element by its ID or XPath
        dropdown_element = driver.find_elements(By.TAG_NAME, "option")
        self.select_option_from_dropdown(dropdown_element, driver, "Asian")

        distance_element = driver.find_element(By.ID, "distance")
        distance_value = "2"
        distance_element.send_keys(distance_value)
        self.perform_search(driver)

    def select_option_from_dropdown(self, dropdown_element, driver, option_text):
        for option in dropdown_element:
            if option.text == option_text:
                option.click()
                break

    def perform_search(self, driver):
        search_button = driver.find_element(By.XPATH, "/html/body/main/form/button")
        search_button.click()

    def test_delivery_flow(self, driver):
        driver.get("http://localhost/food%20delivery%20v5/delivery.php")

        # Enter an order number
        order_number_input = driver.find_element(By.ID, "orderNumber")
        order_number_input.send_keys("12345")

        # Check the order
        check_order_button = driver.find_element(By.TAG_NAME, "button")
        check_order_button.click()

        # Verify that the order details are displayed
        order_details_table = driver.find_element(By.TAG_NAME, "table")
        assert order_details_table is not None

        # Deliver the order
        deliver_button = driver.find_element(By.NAME, "deliver")
        deliver_button.click()

        # Verify that the order status is updated
        status_cell = driver.find_element(By.XPATH, "//td[text()='Delivered']")
        assert status_cell is not None

    def test_track_order(self, driver):
        driver.get("http://localhost/food%20delivery%20v5/trackorder.php")

        # Enter an order number
        order_number_input = driver.find_element(By.ID, "orderNumber")
        order_number_input.send_keys("12345")

        # Submit the order number
        track_order_button = driver.find_element(By.TAG_NAME, "button")
        track_order_button.click()

        # Verify that the order status is displayed
        status_message = driver.find_element(By.TAG_NAME, "p")
        assert "Current status:" in status_message.text

        # Submit a review (if the order is delivered)
        if "Delivered" in status_message.text:
            review_textarea = driver.find_element(By.ID, "review")
            review_textarea.send_keys("Great service!")
            submit_review_button = driver.find_element(By.NAME, "submitReview")
            submit_review_button.click()

            # Verify that the review submission is acknowledged
            review_message = driver.find_element(By.TAG_NAME, "p")
            assert review_message.text == "Thank you for your review!"