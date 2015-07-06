<?php
/**
 * @package test::lib
 */

require_once 'OverrideController.php';
require_once 'TestObserver.php';

/**
 * BaseTestClass is an abstract class that can be inherited by all test classes
 * which will set up the framework, include paths and such as well as provide
 * various utility functions for easier testing
 */
abstract class BaseTestClass extends PHPUnit_Framework_TestCase
{
	/** @property OverrideController */
	private $overrideController;

	/** @property Controller */
	private $controller;

	private $_preserved_session = Array();
	private $_printLineCount = 0;
	
	/**
	 * Anything fired in the constructor will not appear in the code coverage report
	 */
	final function __construct()
	{

	}
	
	/**
	 * Returns a dummy controller object that can be used to fire private methods
	 * such as setting the current user
	 */
	protected function GetOverrideController()
	{
		if (!$this->overrideController) {
			
			$gc = GlobalConfig::GetInstance();
			
			// override controller to let us access protected controller methods
			$this->overrideController = new OverrideController(
					$gc->GetPhreezer(),
					$gc->GetRenderEngine(),
					$gc->GetContext(),
					new MockRouter()  // swap out generic router for a mock
			);
		}
		
		return $this->overrideController;
	}
	
	/**
	 * Assign a value to the render engine
	 */
	protected function Assign($key,$value)
	{
		$re = GlobalConfig::GetInstance()->GetRenderEngine();
		$re->assign($key,$value);
	}
	
	/**
	 * Reset the controller so that all data in the input and output
	 * buffers are cleared, any compiled template files are removed
	 * and (optionally) the user session is destroyed
	 * 
	 * @param bool $clearSession (default = true)
	 */
	protected function Reset($clearSession = true)
	{
		$this->SetUrl('');
		$this->SetRequestBody('');
		$this->ClearOutput();
		$this->ClearVars();
		$this->RemoveCompiledTemplateFiles();
		
		$re = GlobalConfig::GetInstance()->GetRenderEngine();
		
		if ($re) {
			$re->clearAll();
			$re->assign("ROOT_URL",GlobalConfig::$ROOT_URL);
			$re->assign("PHREEZE_VERSION",Phreezer::$Version);
			$re->assign("PHREEZE_PHAR",Phreezer::PharPath());
		}
		
		if ($clearSession) $this->ClearCurrentUser();
	}

	/**
	 * Initializes the controller to be tested and clears all previous
	 * authentication as well as output variable assignments
	 *
	 * @param string name of controller class (ex AccountController)
	 * @param bool true to clear all authentication
	 */
	function InitController($classname, $clearAuth = true)
	{
		$gc = GlobalConfig::GetInstance();
		eval('$this->controller = new '.$classname.'($gc->GetPhreezer(), $gc->GetRenderEngine(), $gc->GetContext(), $gc->GetRouter() );');

		$this->controller->UnitTestMode = true;
		$this->controller->CaptureOutputMode = true;

		// clear all previous input
		$this->ClearVars();

		// remove any authentication that was hanging around
		if ($clearAuth) $this->ClearCurrentUser();

		// get rid of any feedback or warnings
		$re = $gc->GetRenderEngine();
		$re->clear("warning");
		$re->clear("feedback");
		$this->GetOverrideController()->OverrideSetContext("feedback","");

	}
	
	/**
	 * Convenience method to return the global Phreezer object
	 * @return Phreezer
	 */
	function GetPhreezer()
	{
		return GlobalConfig::GetInstance()->GetPhreezer();
	}

	/**
	 * Removes all of the compiled template files that have been
	 * created by smarty.  existing template files can cause
	 * test to return inaccurate results due to cached data.
	 * Any files created while testing can cause the web app
	 * to crash due to incorrect ownership/permission of the files
	 */
	protected function RemoveCompiledTemplateFiles()
	{
		$path = GlobalConfig::$TEMPLATE_CACHE_PATH;
		
		if (!$path) return; // if not configured then this setup probably has no template cache
		
		$dir = opendir($path);

		while ( false !== ($file = readdir($dir)) )
		{
			if (substr($file,-7) == "tpl.php")
			{
				@chmod($path.$file, 0777);
				@unlink($path.$file);
			}
		}

		closedir($dir);
		
		// $this->Println("Compiled template files removed.",'BaseTestClass.RemoveCompiledTemplateFiles');
	}

	/**
	 * Returns the name of the method that called
	 */
	function GetCallingMethodName()
	{
		$e = new Exception();
		$trace = $e->getTrace();
		return $trace[2]['function'];
	}
	
	/**
	 * Output a line to the terminal with a trailing line break
	 * @param string $msg
	 * @param string $prefix.  If not provided then the calling class name will be used
	 */
	function Println($msg,$prefix=null)
	{
		if ($prefix == null) 
			$prefix = str_replace('tests_', '', get_called_class()) 
			. '.' . str_replace('test_', '', $this->GetCallingMethodName());

		if ($this->_printLineCount == 0) echo "\r\n";
		echo ($prefix ? $prefix . ": " : '') . $msg . "\r\n";
		$this->_printLineCount++;
	}

	/**
	 * This will attach an observer to phreezer which will cause debug info to output
	 * for the rest of this test suite
	 */
	function AttachObserver()
	{
		require_once("verysimple/Phreeze/ObserveToBrowser.php");
		$otb = new ObserveToBrowser();
		$this->phreezer->AttachObserver($otb);
	}

	/**
	 * returns a reference to the controller that was created in InitController
	 * @return Controller
	 */
	protected function GetController()
	{
		if (!$this->controller) throw new Exception("InitController was not called in setUp()");
		return $this->controller;
	}

	/**
	 * Used internally to simulate file uploads
	 * @TODO: this seems to have a hard-coded path of 'data' that might need to be altered
	 * @param string $varname
	 * @param string $filename
	 * @param string $mimetype
	 * @param bool $error whether the upload is considered a failure or not
	 */
	private function GetUploadStructure($varname,$filename,$mimetype,$error=0)
	{
		$fullpath = realpath("./data/".$filename);
		$structure = array(
				"error"=>$error
				,"name"=>$filename
				,"tmp_name"=>$fullpath
				,"size"=>filesize($fullpath)
				,"type"=>$mimetype
		);
		return $structure;
	}

	/**
	 * Simulate uploading a file so that it will trick a form handler to think a file upload exists
	 * @param string $varname
	 * @param string $filename
	 * @param string $mimetype
	 */
	function UploadFile($varname,$filename,$mimetype = "text/plain")
	{
		// set request to testmode so we can fake an upload
		RequestUtil::$TestMode = true;
		$_FILES[$varname] = $this->GetUploadStructure($varname,$filename,$mimetype);
	}

	/**
	 * Simulate uploading a file so that it will trick a form handler to think a file upload failed
	 * @param string $varname the form name expected
	 * @param string $filename the file name expected
	 * @param string $mimetype the mime type the file is expcted to be
	 */
	function UploadInvalidFile($varname,$filename,$mimetype = "text/plain")
	{
		// set request to testmode so we can fake an upload
		RequestUtil::$TestMode = true;
		$_FILES[$varname] = $this->GetUploadStructure($varname,$filename,$mimetype,4);
	}

	/**
	 * Clear all previous form and querystring input
	 */
	function ClearVars()
	{
		RequestUtil::ClearAll();
	}

	/**
	 * Set a value as if it was provided by form or querystring input
	 * @param string $var
	 * @param string $val
	 */
	function SetVar($var,$val)
	{
		RequestUtil::Set($var,$val);
	}
	
	/**
	 * Set the url so that URL params can be read by the Router
	 * @param string $url
	 */
	function SetUrl($url)
	{
		$_REQUEST['_REWRITE_COMMAND'] = $url;
		
		// calling get route is required in order to access params
		GlobalConfig::GetInstance()->GetRouter()->GetRoute();
	}
	
	/**
	 * Set the Request body (used in insert/update tests)
	 * @param string $contents
	 */
	function SetRequestBody($contents)
	{
		RequestUtil::SetBody($contents);
	}
	
	/**
	 * Set the HTTP request method 
	 * @param string $method (GET | POST | PUT | DELETE)
	 */
	function SetRequestMethod($method)
	{
		$_SERVER['REQUEST_METHOD'] = $method;
	}

	/**
	 * Set a value in the session
	 * @param string $key
	 * @param mixed $val
	 */
	function SessionSet($key,$val)
	{
		$_SESSION[$key] = $val;
	}

	/**
	 * Verify if a value exists in the session
	 * @param string $key
	 */
	function SessionIsSet($key)
	{
		return isset($_SESSION[$key]);
	}

	/**
	 * Get a value from the session
	 * @param string $key
	 * @return mixed
	 */
	function SessionGet($key)
	{
		return $_SESSION[$key];
	}

	/**
	 * Authenticate with the given IAuthenticatable object
	 * @param IAuthenticatable $user.  If preserve_sesssion
	 * is true then the existing session key will be re-used
	 *
	 * @param bool $preserve_session
	 */
	function SetCurrentUser(IAuthenticatable $user,$preserve_session = false)
	{
		if ($preserve_session) $this->PreserveSession();

		$this->ClearCurrentUser();
		$this->GetOverrideController()->OverrideSetCurrentUser($user);

		if ($preserve_session) $this->RestoreSession();
	}


	/**
	 * Used internally by ClearSession to preserve the
	 * session identifier key
	 */
	private function PreserveSession()
	{
		foreach ($_SESSION as $key => $val)
		{
			if ($key != $this->GetOverrideController()->GUID)
			{
				$this->_preserved_session[$key] = $val;
			}
		}

	}

	/**
	 * Used internally by ClearSession to restore the session
	 * identifier key
	 */
	private function RestoreSession()
	{
		foreach ($this->_preserved_session as $key => $val)
		{
			$_SESSION[$key] = $val;
		}
	}

	/**
	 * Return the current user from the session
	 * @return Ambigous <IAuthenticatable, NULL>
	 */
	function GetCurrentUser()
	{
		return $this->GetOverrideController()->OverrideGetCurrentUser();
	}

	/**
	 * Clear the current user session.  if perserve_session is set to true
	 * then the session key will be restored after the session is cleared.
	 *
	 * @param boolean $preserve_session if true, do not destroy the session key
	 */
	function ClearCurrentUser($preserve_session = false)
	{
		if ($preserve_session) $this->PreserveSession();

		$this->GetOverrideController()->ClearCurrentUser();
		if ($this->controller) $this->controller->ClearCurrentUser();

		if ($preserve_session) $this->RestoreSession();
	}

	/**
	 * Return the object with the given key from the context
	 * @param string $key
	 * @return Ambigous <value, NULL>
	 */
	function GetContext($key)
	{
		return $this->GetOverrideController()->OverrideGetContext($key);
	}

	/**
	 * Return the output that the controller has rendered to the browser
	 */
	function GetOutput()
	{
		return $this->controller->DebugOutput;
	}

	/**
	 * Clears out the debug output
	 */
	function ClearOutput()
	{
		$this->controller->DebugOutput = "";
	}
	
	/**
	 * Validate that correct JSON code was returned from an API call
	 * with either a true or false success status
	 * @param bool $trueOrFalse
	 */
	function AssertApiOutputSuccessIs($trueOrFalse = true)
	{
		$json = $this->GetOutput();
		$obj = json_decode($json);
		
		$this->assertNotEmpty($obj,'Invalid JSON');
		
		$success = property_exists($obj,'success') ? $obj->success : true;
		$message = property_exists($obj,'message') ? $obj->message : '(no message)';
		$this->assertEquals($trueOrFalse,$success,'Unexpected JSON Response: ' . $message);
	}

	/**
	 * Verify that the controller output to the browser contains the given text
	 * @param string $text
	 * @param string message to display if assertion fails
	 * @param bool true to ignore character case (default = false)
	 */
	function AssertOutputContains($text, $message = '', $ignoreCase = false)
	{
		$this->assertContains($text, $this->GetOutput(), $message, $ignoreCase);
	}

	/**
	 * Verify that the controller output to the browser does not contain the given text
	 * @param string $text
	 */
	function AssertOutputNotContains($text, $message = '', $ignoreCase = false)
	{
		$this->assertNotContains($text, $this->GetOutput(), $message, $ignoreCase);
	}

}

?>