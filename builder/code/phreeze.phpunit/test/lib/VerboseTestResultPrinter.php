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

	private $headerPrinted = false;
	
	protected function printHeader()
	{
		parent::printHeader();
	}
	
	/**
	 * Output to the console
	 * @param string $message to print
	 * @param string $color optional color (if supported by console)
	 */
	private function out($message,$color='',$linebreak=false)
	{
		echo ($color ? $this->formatWithColor($color, $message) : $message) . ($linebreak ? "\n" : '');
	}
	
	public function startTest(PHPUnit_Framework_Test $test)
	{
		$this->out(">> RUN '".$test->getName()."'...");
	}
 
	public function endTest(PHPUnit_Framework_Test $test, $time)
	{
		
		if (get_class($test) == 'PHPUnit_Framework_TestSuite') {
			// this occurs when the test suite setup has thrown an error
			$this->out(" SETUP FAIL",'fg-red',true);
		}
		elseif ($test->hasFailed()) {
			$this->out(" FAIL",'fg-red',true);
		}
		else {
			$msg = ($test instanceof PHPUnit_Framework_TestCase) ? ' OK (' . $test->getNumAssertions() . ' assertions)' : ' OK';
			$this->out($msg,'fg-green',true);
		}
		
		// copied from parent:endTest()
		if ($test instanceof PHPUnit_Framework_TestCase) {
			$this->numAssertions += $test->getNumAssertions();
		}
		else if ($test instanceof PHPUnit_Extensions_PhptTestCase) {
			$this->numAssertions++;
		}
	}
 
	public function startTestSuite(PHPUnit_Framework_TestSuite $suite)
	{
		parent::startTestSuite($suite);
		
		if (!$this->headerPrinted) {
			$header = " ______   __  __     ______     ______     ______     ______     ______    \n"
				. "/\  == \ /\ \_\ \   /\  == \   /\  ___\   /\  ___\   /\___  \   /\  ___\   \n"
				. "\ \  _-/ \ \  __ \  \ \  __<   \ \  __\   \ \  __\   \/_/  /__  \ \  __\   \n"
				. " \ \_\    \ \_\ \_\  \ \_\ \_\  \ \_____\  \ \_____\   /\_____\  \ \_____\ \n"
				. "  \/_/     \/_/\/_/   \/_/ /_/   \/_____/   \/_____/   \/_____/   \/_____/ \n";
                                                                           
			$this->out($header,'fg-blue',true);
			$this->out(" - - - - - - - - - - U N I T   T E S T   R U N N E R - - - - - - - - - -\n",'fg-magenta',true);
			$this->headerPrinted = true;
		}
		
		if ($suite->getName() != 'PHPUnit') $this->out("BEGIN SUITE '".$suite->getName()."'\n");
	}
 
	public function endTestSuite(PHPUnit_Framework_TestSuite $suite)
	{
		if ($suite->getName() != 'PHPUnit') $this->out("END SUITE '".$suite->getName()."'\n\n");
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
