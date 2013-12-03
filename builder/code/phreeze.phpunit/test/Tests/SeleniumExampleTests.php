<?php
/**
 * @package test::Tests
 */

/**
 * This is an example of how to implement Selenium tests from
 * within the Phreeze unit test harness.  If you are not using
 * Selenium then you can delete this class.  Otherwise you can
 * refer to the Selenium Setup section in README for setup
 * instructions
 * @package test::Tests
 * @author Phreeze Builder
 * @version 0.0
 */
class tests_SeleniumExampleTests extends PHPUnit_Framework_TestCase // TODO: CHANGE THIS TO PHPUnit_Extensions_SeleniumTestCase
{
	protected function setUp()
	{
		// setup selenium
 		//$this->setBrowser("*firefox");
 		//$this->setBrowserUrl(GlobalConfig::$ROOT_URL);
	}

	public function test_ButtonClick()
	{
		// open the browser to the home page
 		//$this->open(GlobalConfig::$ROOT_URL);

		// click a button with the given id
 		//$this->click("id=buttonId");

		// verify that an element with a specific id exists after the button was clicked
 		//$this->assertTrue($this->isElementPresent("id=elementId"));
	}
}
