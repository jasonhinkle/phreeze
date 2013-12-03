<?php
/**
 * @package test::lib
 */

/**
 * For debugging phreeze.  will only display the first 100 chars of the debug info
 */
class TestObserver implements IObserver
{
	public function Observe($obj, $ltype = OBSERVE_INFO)
	{
		$output = print_r($obj,1);
		if ($ltype != OBSERVE_QUERY)
		{
			$output = substr( $output, 0, 100);
		}

		print "OBSERVER >> " . $output . "\r\n";
	}
}

?>