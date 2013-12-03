<?php
/**
 * @package test::Tests
 */

/** includes necessary for this unit test go here: */
require_once("lib/BaseTestClass.php");


/**
 * Test the functionality of the DefaultController
 *
 * @package test::Tests
 * @author Phreeze Builder
 * @version 1.0
 */
class tests_DefaultTests extends BaseTestClass
{

	/**
	 * This test instantiates the DefaultController but doesn't call any methods.
	 * This should validate that the basic environment is setup correctly
	 */
	function test_InstantiateController()
	{
		require_once("Controller/DefaultController.php");
		$this->InitController("DefaultController");
		$this->assertNotEmpty($this->GetController());
		
	}
	
	/**
	 * This test calls the "Home" method on the DefaultController which should
	 * verify that basic template rendering is functioning correctly
	 */
	function test_RenderHomeTemplate()
	{
		require_once("Controller/DefaultController.php");
		$this->InitController("DefaultController");

		$this->GetController()->Home();
		$this->AssertOutputContains("<html");
		$this->AssertOutputContains("</html>");
	
		// after a render operation we should clear out any debug output
		$this->Reset();
	}
	
	/**
	 * This test calls the "Home" method on the DefaultController which should
	 * verify that basic template rendering is functioning correctly
	 */
	function test_RenderErrorTemplate()
	{
		require_once("Controller/DefaultController.php");
		$this->InitController("DefaultController");
	
		$this->Assign('message','UNIT TEST ERROR');
		$this->Assign('stacktrace','');
		$this->GetController()->ErrorFatal();
		$this->AssertOutputContains("<html");
		$this->AssertOutputContains("UNIT TEST ERROR");
		$this->AssertOutputContains("</html>");
	
		// after a render operation we should clear out any debug output
		$this->Reset();
	}
	
	/**
	 * This test calls the "Home" method on the DefaultController which should
	 * verify that basic template rendering is functioning correctly
	 */
	function test_Render404Template()
	{
		require_once("Controller/DefaultController.php");
		$this->InitController("DefaultController");
	
		$this->GetController()->Error404();
		$this->AssertOutputContains("<html");
		$this->AssertOutputContains("</html>");
	
		// after a render operation we should clear out any debug output
		$this->Reset();
	}
	
	/**
	 * This test calls the "Home" method on the DefaultController which should
	 * verify that basic template rendering is functioning correctly
	 */
	function test_RenderApi404Template()
	{
		require_once("Controller/DefaultController.php");
		$this->InitController("DefaultController");
	
		$this->GetController()->ErrorApi404();

		// GetOutput will return whatever data was output to the browser.  In this case we expect valid JSON
		$json = $this->GetOutput();
		$obj = json_decode($json);
		
		// Validate API output is valid JSON and *IS* an error
		$this->AssertApiOutputSuccessIs(false);
	
		// after a render operation we should clear out any debug output
		$this->Reset();
	}


}
