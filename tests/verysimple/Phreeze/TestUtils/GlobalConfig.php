<?php
/**
 * @package verysimple::Phreeze::TestUtils
 */

/* ensure the framework libraries can be located */
set_include_path(
		realpath("./verysimple/Phreeze/TestUtils/") .
		PATH_SEPARATOR . realpath("./verysimple/Phreeze/TestUtils/libs/") .
		PATH_SEPARATOR . realpath("../libs/") .
		PATH_SEPARATOR . get_include_path()
);

// require unit testing helper classes
require_once 'BaseTestClass.php';

/* require framework libs */
require_once("verysimple/HTTP/RequestUtil.php");
require_once("verysimple/Phreeze/Phreezer.php");
require_once("verysimple/Phreeze/Controller.php");
require_once("verysimple/Phreeze/Dispatcher.php");
require_once("verysimple/Util/ExceptionFormatter.php");
require_once("verysimple/Phreeze/SmartyRenderEngine.php");
require_once("verysimple/Phreeze/MockRouter.php");

// set this so OAuth doesn't crash
$_SERVER["REQUEST_URI"] = "/";
$_SERVER["HTTP_HOST"] = "localhost";
$_SERVER["REMOTE_ADDR"] = "1.1.1.1";

/**
 * contains all configurations used in the framework
 */
class GlobalConfig
{
	public function GetRouter()
	{
		return new MockRouter();
	}

	public function GetPhreezer()
	{

	}

	public function GetRenderEngine()
	{

	}

	public function GetContext()
	{

	}
}
?>