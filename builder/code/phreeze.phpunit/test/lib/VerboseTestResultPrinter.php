<?php
/**
 * @package verysimple::phreeze
 */

/**
 * This test listener outputs the name as each test is running
 * instead of simply printing progress dots as the tests run
 * @version 1.1
 * @author Jason Hinkle <verysimple.com>
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
		
		// copied from parent:endTest()
		if ($test instanceof PHPUnit_Framework_TestCase) {
			$this->numAssertions += $test->getNumAssertions();
		}
		else if ($test instanceof PHPUnit_Extensions_PhptTestCase) {
			$this->numAssertions++;
		}
		$this->lastTestFailed = false;
		
		
		// custom printing code
		if (get_class($test) == 'PHPUnit_Framework_TestSuite') {
			// this occurs when the test suite setup has thrown an error
			$this->out(" SETUP FAIL",'fg-red',true);
		}
		elseif ($test->hasFailed()) {
			$this->out(" FAIL",'fg-red',true);
		}
		else {
			
			$numAssertions = ($test instanceof PHPUnit_Framework_TestCase) ? $test->getNumAssertions() : 1;
		
			if ($numAssertions > 0) {
				$this->out(' OK (' . $numAssertions . ' assertions)','fg-green',true);
			}
			else {
				$this->out(' SKIPPED (0 assertions)','fg-yellow',true);
			}
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
			$this->out(" - - - - - - - - - T E S T   A L L   T H E   T H I N G S - - - - - - - - - ",'fg-blue',true);
			$this->out('','',true);
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
