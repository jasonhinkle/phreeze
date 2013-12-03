<?php
/**
 * @package test::Tests
 */

/** includes necessary for this unit test go here: */
require_once 'PHPUnit/Framework/TestSuite.php';

/**
 * This test runner file will scan for test files in the "Tests" subdirectory
 * and add them to the test suite so long as the filename ends in .php
 * and the class name matches the file name, for example "Basic.php" classname is "test_Basic"
 * 
 * WARNING: RENAMING THIS FILE WITHOUT UPDATING THE CODE INSIDE COULD CAUSE AN INFININE LOOP
 * 
 * @package test::Tests
 * @author Phreeze Builder
 * @version 1.0
 */
class AllTests
{
    public static function suite()
    {
    	// echo "\nINITIALIZE 'AllTests' Test Suite\n";
    	
        $suite = new PHPUnit_Framework_TestSuite('PHPUnit');
        $files = scandir('Tests/');
        
        foreach ($files as $file) {
        	if (strpos($file,'.php') > -1 && $file != 'AllTests.php') {
        		$testClass = 'tests_' . str_replace('.php', '', $file);
        		// echo ">> LOCATED CLASS $testClass\n";
        		require_once $file;
        		$suite->addTestSuite($testClass);
        	}
        }
        
        // echo "\n";
        return $suite;
    }
}