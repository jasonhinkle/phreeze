<?php
/**
 * @package tests::Controller
 */

/** includes necessary for this unit test go here: */
require_once("TestUtils/GlobalConfig.php");

/**
 * Tests for Account
 *
 * @package Mtdticketing::Test::Controller
 * @author Christian Dawson
 * @version 1.0
 */
class tests_RouterTest extends BaseTestClass
{

	/**
	 * @see PHPUnit_Framework_TestCase::setUp()
	 */
	function setUp()
	{

	}

	/**
	 * @see PHPUnit_Framework_TestCase::tearDown()
	 */
	function tearDown()
	{
	}

	/**
	 * Test to make sure router is able to do a reverse lookup and get the URL for a
	 * given controller+method+params
	 */
	function test_GetUrl()
	{
		$this->Println("RouterTest.GetUrl");

		$routemap = array(
				// default route
				"GET:" => array("route" => "Test.One"),

				// simple route
				"GET:test2" => array("route" => "Test.Two"),

				// single param route
				"GET:test3/(:num)/route" => array("route" => "Test.Three", "params" => array("a" => 1)),

				// double param route
				"GET:test4/(:any)/route/(:num)" => array("route" => "Test.Four", "params" => array("a" => 1, "b" => 3)),

				// test route with no params, but params are passed into GetUrl
				"GET:test5/route" => array("route" => "Test5.Route")
			);

		$router = new GenericRouter('http://localhost/subdir/', 'Test.One', $routemap);
		$_SERVER['REQUEST_METHOD'] = "GET";

		// BEGIN TEST
		$this->Println(">> Router test 1");
		$this->assertEquals('http://localhost/subdir/', $router->GetUrl("Test", "One"));

		// BEGIN TEST
		$this->Println(">> Router test 2");
		$this->assertEquals('http://localhost/subdir/test2', $router->GetUrl("Test", "Two"));

		// BEGIN TEST
		$this->Println(">> Router test 3");
		$this->assertEquals('http://localhost/subdir/test3/222/route', $router->GetUrl("Test", "Three", array("a"=>222)));

		// BEGIN TEST
		$this->Println(">> Router test 4");
		$this->assertEquals('http://localhost/subdir/test4/xxx/route/333', $router->GetUrl("Test", "Four", array("a"=>"xxx", "b"=>333)));

		// BEGIN TEST
		$this->Println(">> Router test 5");
		$this->assertEquals('http://localhost/subdir/test5/route', $router->GetUrl("Test5", "Route", array() ));

		// BEGIN TEST
		$this->Println(">> Router test 6");
		$exceptionWasThrown = false;
		try
		{
			$url = $router->GetUrl("Test", "Unknown"); // unknown route should throw an exception
		}
		catch (Exception $ex)
		{
			$exceptionWasThrown = true;
		}

		$this->assertTrue($exceptionWasThrown,'Expected router to throw an exception for an unknown route');

	}

}
?>