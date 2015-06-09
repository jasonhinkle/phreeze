<?php
/**
 * @package test::Tests
 */

/** includes necessary for this unit test go here: */
require_once("lib/BaseTestClass.php");

/**
 * Test functionality of the {$singular}Controller
 *
 * @package test::Tests
 * @author Phreeze Builder
 * @version 1.0
 *
 * Prevent PHPUnit from serializing DB connections created in setUpBeforeClass
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class tests_{$singular}Tests extends BaseTestClass
{
	/**
	 * TODO: set up DB fixture
	 */
	public static function setUpBeforeClass()
	{
		// $phreezer = GlobalConfig::GetInstance()->GetPhreezer();
		// $sql = "insert into ...";
		// $phreezer->DataAdapter->Execute($sql);
	}

	/**
	 * TODO: tear down DB fixture
	 */
	public static function tearDownAfterClass()
	{
		// $phreezer = GlobalConfig::GetInstance()->GetPhreezer();
		// $sql = "delete from ...";
		// $phreezer->DataAdapter->Execute($sql);
	}

	/**
	 * Tests that basic controller instantiation and session
	 * initiaization is working
	 */
	function test_InstantiateController()
	{
		require_once("Controller/{$singular}Controller.php");
		$this->InitController("{$singular}Controller");

		$this->assertNotEmpty($this->GetController());		
	}
	
	/**
	 * Tests that basic controller instantiation and session
	 * initiaization is working
	 */
	function test_ListView()
	{
		require_once("Controller/{$singular}Controller.php");
		$this->InitController("{$singular}Controller");
	
		// The query method will output JSON to the browser, which we can capture
		$this->GetController()->ListView();
	
		$this->AssertOutputContains("<html");
		$this->AssertOutputContains("</html>");
		$this->AssertOutputNotContains("<!-- ERROR");
	
		// after a render operation we should clear out any debug output
		$this->Reset();
	
	}
	
	/**
	 * Tests that basic controller instantiation and session
	 * initiaization is working
	 */
	function test_Query()
	{
		require_once("Controller/{$singular}Controller.php");
		$this->InitController("{$singular}Controller");
		
		// The query method will output JSON to the browser, which we can capture
		$this->GetController()->Query();
		
		// Validate API output is valid JSON and not an error
		$this->AssertApiOutputSuccessIs(true);
	
		// after a render operation we should clear out any debug output
		$this->Reset();
	
	}
	
	/**
	 * TODO: this reads a single record by primary key.  This test may or may not be relevant
	 */
	function test_Read()
	{
		require_once("Controller/{$singular}Controller.php");
		$this->InitController("{$singular}Controller");
		
		// TODO: change this value to any existing row in the table
		$primaryKeyValue = "1";
		
		// setting the url will allow the router to parse for parameters correctly
		$this->SetUrl('api/{$singular|lower}/'.$primaryKeyValue);
		
		// The query method will output JSON to the browser, which we can capture
		$this->GetController()->Read();
		
		// Validate API output is valid JSON and not an error
		$this->AssertApiOutputSuccessIs(true);
		
		// after a render operation we should clear out any debug output
		$this->Reset();
	}
	
	/**
	 * TODO: uncomment and provide valid values to enable testing of record creation
	 */
	/*
	function test_Create()
	{
		require_once("Controller/{$singular}Controller.php");
		$this->InitController("{$singular}Controller");
		
		// TODO: Enter valid values to be inserted into the database
		$obj = new stdClass();
{foreach from=$table->Columns item=column}
{if $column->Extra != 'auto_increment'}
		$obj->{$column->NameWithoutPrefix|studlycaps|lcfirst} = '';
{/if}
{/foreach}
		$json = json_encode($obj);
		
		// set the request method, body and URL so the controller can obtain it
		$this->SetRequestMethod('POST');
		$this->SetRequestBody($json);
		$this->SetUrl('api/{$singular|lower}');
		
		// The query method will output JSON to the browser, which we can capture
		$this->GetController()->Create();
		
		// Validate API output is valid JSON and not an error
		$this->AssertApiOutputSuccessIs(true);
		
		// after a render operation we should clear out any debug output
		$this->Reset();
	}
	//*/

	/**
	 * TODO: uncomment and provide valid values to enable testing of record updating
	 */
	/*
	function test_Update()
	{
		require_once("Controller/{$singular}Controller.php");
		$this->InitController("{$singular}Controller");
		
		// TODO: Update the primary key and Enter valid values to be updated
		$primaryKeyValue = "0";
		$obj = new stdClass();
{foreach from=$table->Columns item=column}
{if $column->Extra == 'auto_increment'}
		$obj->{$column->NameWithoutPrefix|studlycaps|lcfirst} = $primaryKeyValue;
{else}
		$obj->{$column->NameWithoutPrefix|studlycaps|lcfirst} = '';
{/if}
{/foreach}
		$json = json_encode($obj);
		
		// set the request method, body and URL so the controller can obtain it
		$this->SetRequestMethod('PUT');
		$this->SetRequestBody($json);
		$this->SetUrl('api/{$singular|lower}/'.$primaryKeyValue);
		
		// The query method will output JSON to the browser, which we can capture
		$this->GetController()->Update();
		
		// Validate API output is valid JSON and not an error
		$this->AssertApiOutputSuccessIs(true);
		
		// after a render operation we should clear out any debug output
		$this->Reset();
	}
	//*/

	/**
	 * TODO: uncomment and provide valid primary key to enable testing of record deletion
	 */
	/*
	function test_Delete()
	{
		// TODO: change this value to an existing row that is ok to delete
		$primaryKeyValue = "0";
		
		// set the request method and URL so the controller can obtain it
		$this->SetRequestMethod('DELETE');
		$this->SetUrl('api/{$singular|lower}/'.$primaryKeyValue);
		
		// The query method will output JSON to the browser, which we can capture
		require_once("Controller/{$singular}Controller.php");
		$this->InitController("{$singular}Controller");
		$this->GetController()->Delete();
		
		// Validate API output is valid JSON and not an error
		$this->AssertApiOutputSuccessIs(true);
		
		// after a render operation we should clear out any debug output
		$this->Reset();
	}
	//*/
	
}