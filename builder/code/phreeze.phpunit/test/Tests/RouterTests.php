<?php
/**
 * @package test::Tests
 */

/** includes necessary for this unit test go here: */
require_once("lib/BaseTestClass.php");


/**
 * Test the basic functionality of the routing functionality
 *
 * @package test::Tests
 * @author Phreeze Builder
 * @version 1.0
 */
class tests_RouterTests extends BaseTestClass
{
	/**
	 * Returns a router instantiated with a route map ready for testing
	 * (note that $_SERVER['REQUEST_METHOD'] is set to "GET" in bootstrap.php)
	 * @return GenericRouter
	 */
	private function GetTestRouter($baseUrl='http://localhost/',$defaultAction='default')
	{
		$routemap = array(
				"GET" => array("route" => "Default.DefaultAction"),
				"GET:home" => array("route" => "Default.Home"),
				"GET:error" => array("route" => "Default.ErrorFatal"),
		);
		
		return new GenericRouter($baseUrl, $defaultAction, $routemap);
	}
	
	
	/**
	 * This test verifies that basic routing lookup is working
	 */
	function test_VerifyLookup()
	{
		$router = $this->GetTestRouter();
		
		$route = $router->GetRoute('GET:home');
		
		$this->assertEquals(2, count($route),'Router returned an unexpected value');
		$this->assertEquals('Default', $route[0],'Router returned an unexpected controller name');
		$this->assertEquals('Home', $route[1],'Router returned an unexpected method name');
	}
	
	/**
	 * This test verifies that a reverse route lookup works
	 */
	function test_VerifyReverseLookup()
	{
		$baseUrl = 'http://localhost/subdir/';
		$router = $this->GetTestRouter($baseUrl);
		
		$_SERVER['REQUEST_METHOD'] = "GET";
		$url = $router->GetUrl("Default", "Home");
		
		$this->assertEquals($baseUrl . 'home', $url);
	}
	
	/**
	 * This test verifies that an exception is thrown for an invalid route
	 */
	function test_VerifyFileNotFound()
	{
		$router = $this->GetTestRouter();
		$exceptionWasThrown = false;
		
		try {
			$url = $router->GetUrl("Test", "Unknown"); // unknown route should throw an exception
		}
		catch (Exception $ex) {
			$exceptionWasThrown = true;
		}
	
		$this->assertTrue($exceptionWasThrown,'Expected router to throw an exception for an unknown route');
	}
	
}
