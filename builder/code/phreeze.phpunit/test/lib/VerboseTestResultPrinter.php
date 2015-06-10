<?php
/**
 * @package test::lib
 */

/**
 * This test listener outputs the name as each test is running
 * instead of simply printing progress dots as the tests run
 */
class VerboseTestResultPrinter extends PHPUnit_TextUI_ResultPrinter
{
	static $COLOR_RED = "\033[31m";
	static $COLOR_GREEN = "\033[32m";
	static $COLOR_NORMAL = "\033[0m";
	
	public function startTest(PHPUnit_Framework_Test $test)
	{
		printf(">> RUN '%s'...", $test->getName());
	}
 
	public function endTest(PHPUnit_Framework_Test $test, $time)
	{
		
		if (get_class($test) == 'PHPUnit_Framework_TestSuite') {
			// this occurs when the test suite setup has thrown an error
			printf(self::$COLOR_RED." SETUP FAIL".self::$COLOR_NORMAL."\n");
		}
		elseif ($test->hasFailed()) {
			printf(self::$COLOR_RED." FAIL".self::$COLOR_NORMAL."\n");
		}
		else {
			printf(self::$COLOR_GREEN." OK".self::$COLOR_NORMAL."\n");
		}
		
		if ($test instanceof PHPUnit_Framework_TestCase) {
			$this->numAssertions += $test->getNumAssertions();
		}
		else if ($test instanceof PHPUnit_Extensions_PhptTestCase) {
			$this->numAssertions++;
		}
	}
 
	public function startTestSuite(PHPUnit_Framework_TestSuite $suite)
	{
		if ($suite->getName() != 'PHPUnit') printf("BEGIN SUITE '%s'\n", $suite->getName());
	}
 
	public function endTestSuite(PHPUnit_Framework_TestSuite $suite)
	{
		if ($suite->getName() != 'PHPUnit') printf("END SUITE '%s'\n\n", $suite->getName());
	}
	
	/**
	 * Overriding this method suppresses all of the various dots
	 * result codes that PHPUnit sends to the console
	 * @param string $progress
	 */
	protected function writeProgress($progress)
	{
		// suppress output;
	}
	
}
