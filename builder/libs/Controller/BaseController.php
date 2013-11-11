<?php
/** @package    Builder::Controller */

/** import supporting libraries */
require_once("verysimple/Phreeze/Controller.php");
require_once("verysimple/DB/Reflection/DBConnectionString.php");

/**
 * AdservBaseController is a base class Controller class from which
 * the front controllers inherit.  it is not necessary to use this
 * class or any code, however you may use if for application-wide
 * functions such as authentication
 *
 * @package Adserv::Controller
 * @author ClassBuilder
 * @version 1.0
 */
class BaseController extends Controller
{

	/**
	 * Init is called by the base controller before the action method
	 * is called.  This provided an oportunity to hook into the system
	 * for all application actions.  This is a good place for authentication
	 * code.
	 */
	protected function Init()
	{
		// TODO: add app-wide bootsrap code
	}

	/**
	 * Get connection string based on request variables
	 */
	protected function GetConnectionString()
	{
		$cstring = new DBConnectionString();
		$cstring->Host = RequestUtil::Get('host');
		$cstring->Port = RequestUtil::Get('port');
		$cstring->Username = RequestUtil::Get('username');
		$cstring->Password = RequestUtil::Get('password');
		$cstring->DBName = RequestUtil::Get('schema');
		$cstring->Type = RequestUtil::Get('type','MySQL');
		return $cstring;
	}

	/**
	 * Guest at an appname based on the db connection settings
	 * @param Connection $connection
	 */
	protected function GetAppName($connection)
	{
		return ucwords(
				preg_replace_callback(
						"/(\_(.))/",
						create_function('$matches', 'return strtoupper($matches[2]);'),
						strtolower($connection->DBName)
				)
		);

	}

}
?>