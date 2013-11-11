<?php
/** @package Builder::Controller */

/** import supporting libraries */
require_once("BaseController.php");
require_once("verysimple/IO/FolderHelper.php");
require_once("verysimple/DB/Reflection/DBServer.php");
require_once("libs/App/AppConfig.php");

/**
 * DefaultController is the entry point to the application
 *
 * @package Adserv::Controller
 * @author ClassBuilder
 * @version 1.0
 */
class AnalyzerController extends BaseController
{

	/**
	 * Override here for any controller-specific functionality
	 */
	protected function Init()
	{
		parent::Init();
	}

	/**
	 * Analyze the database schema and display a listing of tables and options
	 */
	public function Analyze()
	{

		$cstring = $this->GetConnectionString();

		// initialize the database connection
		$handler = new DBEventHandler();
		$connection = new DBConnection($cstring, $handler);
		$server = new DBServer($connection);

		// load up the available packages (based on files: code/*.config)
		$folder = new FolderHelper( GlobalConfig::$APP_ROOT . '/code/' );
		$files = $folder->GetFiles('/config/');
		$packages = Array();

		foreach ($files as $fileHelper)
		{
			$packages[] = new AppConfig($fileHelper->Path);
		}
		
		uasort(
			$packages,
			function ($a, $b) {
				return $a->GetName() > $b->GetName() ? 1 : -1;
			}
		);

		// read and parse the database structure
		$dbSchema = new DBSchema($server);

		$appname = $this->GetAppName($connection);

		// header("Content-type: text/plain"); print_r($schema); die();

		// initialize parameters that will be passed on to the code templates
		$params = array();
		$params[] = new AppParameter('PathToVerySimpleScripts', '/scripts/verysimple/');
		$params[] = new AppParameter('PathToExtScripts', '/scripts/ext-2/');
		$params[] = new AppParameter('AppName', $dbSchema->Name);

		$this->Assign("dbSchema",$dbSchema);
		$this->Assign("packages",$packages);
		$this->Assign("params", $params);
		$this->Assign("appname", $appname);

		// $this->RenderEngine->savant->addPlugins(array('Savant3_Filter_studlycaps', 'filter'));

		$this->Assign('host', $cstring->Host);
		$this->Assign('port', $cstring->Port);
		$this->Assign('type', $cstring->Type);
		$this->Assign('schema', $cstring->DBName);
		$this->Assign('username', $cstring->Username);
		$this->Assign('password', $cstring->Password);

		$this->Render();
	}
}
?>