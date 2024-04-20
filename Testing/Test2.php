<?php

use PHPUnit\Framework\TestCase;

class HtmlPageTest extends TestCase
{
    protected $html;

    protected function setUp(): void
    {
        // Load the HTML page content into $html
        $this->html = file_get_contents('restaurants php.php');
    }

    public function testPageLoadsSuccessfully()
    {
        // Assert that the HTML page content is not empty
        $this->assertNotEmpty($this->html);
    }

    public function testPageContainsHeader()
    {
        // Assert that the page contains a header with the text 'Get Delivered'
        $this->assertStringContainsString('<h1>Get Delivered</h1>', $this->html);
    }

    public function testPageContainsFooter()
    {
        // Assert that the page contains a footer with the text '© 2024 Get Delivered. All rights reserved.'
        $this->assertStringContainsString('<footer>', $this->html);
        $this->assertStringContainsString('<p>&copy; 2024 Get Delivered. All rights reserved.</p>', $this->html);
    }

    public function testPageContainsMainContent()
    {
        // Assert that the page contains main content like restaurant listings and search form
        $this->assertStringContainsString('<main>', $this->html);
        $this->assertStringContainsString('<form id="search-form"', $this->html);
        $this->assertStringContainsString('<div class="restaurant-container">', $this->html);
    }

    // You can add more test methods to validate specific aspects of the HTML page
}
