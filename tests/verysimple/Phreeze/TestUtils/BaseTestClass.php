<?php
/**
 * @package verysimple::Phreeze::TestUtils
 */

require_once 'OverrideController.php';

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

	/** @property Phreezer */
	protected $phreezer;

	/** @property Router */
	protected $router;

	/** @property Smarty */
	protected $smarty;

	private $_preserved_session = Array();

	private $_printLineCount = 0;

	/**
	 * Initialize the override controller so we have access to base controller methods like set/clear current user.
	 */
	final function __construct()
	{
// 		$gc = GlobalConfig::GetInstance();
// 		$this->phreezer =& $gc->GetPhreezer();
// 		$this->renderEngine =& $gc->GetRenderEngine();

// 		// swapping out generic router for a unit-testable mock router:
// 		$this->router = new MockRouter();

// 		// override controller to let us access protected controller methods
// 		$this->overrideController = new OverrideController(
// 			$this->phreezer
// 			, $this->renderEngine
// 			, $gc->GetContext()
// 			, $this->router
// 		);
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

		eval('$this->controller = new '.$classname.'($this->phreezer, $this->renderEngine, $gc->GetContext(), $this->router );');

		$this->controller->UnitTestMode = true;
		$this->controller->CaptureOutputMode = true;

		// clear all previous input
		$this->InputClearAll();

		// remove any authentication that was hanging around
		if ($clearAuth) $this->ClearCurrentUser();

		// get rid of any feedback or warnings
		$this->renderEngine->clear("warning");
		$this->renderEngine->clear("feedback");
		$this->overrideController->OverrideSetContext("feedback","");

		$this->Println("-- Controller '$classname' initialized");

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
		$this->Println("-- Removing compiled template files...");

		$path = COMPILE_PATH;
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
	}

	/**
	 * Output a line to the terminal with a trailing line break
	 * @param string $msg
	 */
	function Println($msg)
	{
		if ($this->_printLineCount == 0) echo "\r\n";
		echo "# " . $msg . "\n";
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
	function InputClearAll()
	{
		RequestUtil::ClearAll();
	}

	/**
	 * Set a value as if it was provided by form or querystring input
	 * @param string $var
	 * @param string $val
	 */
	function InputSet($var,$val)
	{
		RequestUtil::Set($var,$val);
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
		$this->overrideController->OverrideSetCurrentUser($user);

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
			if ($key != $this->overrideController->GUID)
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
		return $this->overrideController->OverrideGetCurrentUser();
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

		$this->overrideController->ClearCurrentUser();
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
		return $this->overrideController->OverrideGetContext($key);
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
	 * Verify that the controller output to the browser contains the given text
	 * @param string $text
	 */
	function AssertOutputContains($text)
	{
		$this->assertContains($text, $this->GetOutput());
	}

	/**
	 * Verify that the controller output to the browser does not contain the given text
	 * @param string $text
	 */
	function AssertOutputNotContains($text)
	{
		$this->assertNotContains($text, $this->GetOutput());
	}

}

?>