<?php
/**
 * @package tests::Controller
 */

/** includes necessary for this unit test go here: */
require_once("TestUtils/GlobalConfig.php");

/**
 * Tests for Controller
 *
 * @package Test::Phreeze::Controller
 * @author Jason Hinkle
 * @version 1.0
 */
class tests_ControllerTest extends BaseTestClass
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
	function test_Init()
	{
		$this->Println("ControllerTest.Init");

		$this->assertTrue(true);
	}

}
?>