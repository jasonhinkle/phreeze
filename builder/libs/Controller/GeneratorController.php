<?php
/** @package Builder::Controller */

/** import supporting libraries */
require_once("BaseController.php");
require_once("verysimple/IO/FolderHelper.php");
require_once("verysimple/DB/Reflection/DBServer.php");
require_once("libs/App/AppConfig.php");
include_once("util/zip.lib.php");
include_once("smarty/Smarty.class.php");

/**
 * DefaultController is the entry point to the application
 *
 * @package Adserv::Controller
 * @author ClassBuilder
 * @version 1.0
 */
class GeneratorController extends BaseController
{

	/** 
	 * @var number of items to display in the topnav (including the home line).  
	 * If there are more tables than will fit then the last item will be a 
	 * dropdown containing all of the remaining tables
	 */
	static $DEFAULT_MAX_ITEMS_IN_TOPNAV = 4;
	
	/**
	 * Override here for any controller-specific functionality
	 */
	protected function Init()
	{
		parent::Init();
	}

	/**
	 * Generate the application based on the selected tables and options
	 */
	public function Generate()
	{
		// check for all required fields
		if ( empty($_REQUEST["table_name"]) )
		{
			throw new Exception("Please select at least one table to generate");
		}

		$cstring = $this->GetConnectionString();

		// initialize the database connection
		$handler = new DBEventHandler();
		$connection = new DBConnection($cstring, $handler);
		$server = new DBServer($connection);
		$dbSchema = new DBSchema($server);

		$debug = isset($_REQUEST["debug"]) && $_REQUEST["debug"] == "1";
		$parameters = array();
		$tableNames = $_REQUEST["table_name"];
		$packageName = $_REQUEST["package"];
		$debug_output = "";
		
		$selectedTables = array();
		foreach ($tableNames as $tableName)
		{
			$selectedTables[] = $dbSchema->Tables[$tableName];
		}
		
		// see if arbitrary parameters were passed in - in which case they will be passed through to the templates
		$tmp = RequestUtil::Get('parameters');
		if ($tmp)
		{
			$pairs = explode("\n", str_replace("\r","", $tmp) );
			foreach ($pairs as $pair)
			{
				list($key,$val) = explode("=",$pair,2);
				$parameters[$key] = $val;
			}
		}
		
		// check for required parameters
		if (!array_key_exists('max_items_in_topnav', $parameters)) $parameters['max_items_in_topnav'] = self::$DEFAULT_MAX_ITEMS_IN_TOPNAV;

		$zipFile = new zipfile();

		$codeRoot = GlobalConfig::$APP_ROOT . '/code/';
		$tempRoot = GlobalConfig::$APP_ROOT . '/temp/';

		// initialize smarty
		$smarty = new Smarty();
		$smarty->template_dir = $codeRoot;
		$smarty->compile_dir = $tempRoot;
		$smarty->config_dir = $tempRoot;
		$smarty->cache_dir = $tempRoot;
		$smarty->caching = false;

		$appname = RequestUtil::Get("appname");
		$appRoot = RequestUtil::Get("appRoot");
		$includePath = RequestUtil::Get("includePath");
		$includePhar = RequestUtil::Get("includePhar");
		$enableLongPolling = RequestUtil::Get("enableLongPolling");

		$config = new AppConfig($codeRoot  . $packageName);
		
		foreach ($config->GetTemplateFiles() as $templateFile)
		{
			if ($templateFile->generate_mode == 3)
			{
				if ($includePhar == '1')
				{
					// proceed, copy the phar file
					$templateFile->generate_mode = 2;
				}
				else
				{
					// skip the phar file
					continue;
				}
			}
			
			if ($templateFile->generate_mode == 2)
			{
				// this is a template that is copied without parsing to the project (ie images, static files, etc)
				$templateFilename = str_replace(
						array('{$appname}','{$appname|lower}','{$appname|upper}'),
						array($appname,strtolower($appname),strtoupper($appname)),
						$templateFile->destination
				);

				$contents = file_get_contents($codeRoot . $templateFile->source);

				// this is a direct copy
				if ($debug)
				{
					$debug_output .= "\r\n###############################################################\r\n"
					. "# $templateFilename\r\n###############################################################\r\n"
					. "(contents of " . $codeRoot . $templateFile->source . ")\r\n";
				}
				else
				{
					$zipFile->addFile( $contents , $templateFilename);
				}
			}
			elseif ($templateFile->generate_mode == 1)
			{
				// single template where one is generated for the entire project instead of one for each selected table
				$templateFilename = str_replace(
						array('{$appRoot}','{$appRoot|lower}','{$appRoot|upper}'),
						array($appRoot,strtolower($appRoot),strtoupper($appRoot)),
						$templateFile->destination
				);

				$smarty->clearAllAssign();

				foreach ($parameters as $key => $val)
				{
					$smarty->assign($key,$val);
				}

				$smarty->assign("tableNames",$tableNames);
				$smarty->assign("templateFilename",$templateFilename);
				$smarty->assign("schema",$dbSchema);
				$smarty->assign("tables",$dbSchema->Tables);
				$smarty->assign("connection",$cstring);
				$smarty->assign("appname",$appname);
				$smarty->assign("appRoot",$appRoot);
				$smarty->assign("includePath",$includePath);
				$smarty->assign("includePhar",$includePhar);
				$smarty->assign("enableLongPolling",$enableLongPolling);
				$smarty->assign("PHREEZE_VERSION",Phreezer::$Version);

				$tableInfos = Array();
				
				// add all tables to a tableInfos array that can be used for cross-referencing by table name
						foreach ($dbSchema->Tables as $table)
				{
					if ($table->GetPrimaryKeyName())
					{
						$tableName = $table->Name;
						$tableInfos[$tableName] = Array();
						$tableInfos[$tableName]['table'] = $dbSchema->Tables[$tableName];
						$tableInfos[$tableName]['singular'] = $_REQUEST[$tableName."_singular"];
						$tableInfos[$tableName]['plural'] = $_REQUEST[$tableName."_plural"];
						$tableInfos[$tableName]['prefix'] = $_REQUEST[$tableName."_prefix"];
						$tableInfos[$tableName]['templateFilename'] = $templateFilename;
					}
				}
				
				$smarty->assign("tableInfos",$tableInfos);
				$smarty->assign("selectedTables",$selectedTables);

				if ($debug)
				{
					$debug_output .= "\r\n###############################################################\r\n"
					. "# $templateFilename\r\n###############################################################\r\n"
					. $smarty->fetch($templateFile->source) . "\r\n";
				}
				else
				{
					// we don't like bare linefeed characters
					$content = $body = preg_replace("/^(?=\n)|[^\r](?=\n)/", "\\0\r", $smarty->fetch($templateFile->source));

					$zipFile->addFile( $content , $templateFilename);
				}
			}
			else
			{
				// enumerate all selected tables and merge them with the selected template
				// append each to the zip file for output
				foreach ($tableNames as $tableName)
				{
					$singular = $_REQUEST[$tableName."_singular"];
					$plural = $_REQUEST[$tableName."_plural"];
					$prefix = $_REQUEST[$tableName."_prefix"];

					$templateFilename = str_replace(
							array('{$singular}','{$plural}','{$table}','{$appname}','{$singular|lower}','{$plural|lower}','{$table|lower}','{$appname|lower}','{$singular|upper}','{$plural|upper}','{$table|upper}','{$appname|upper}'),
							array($singular,$plural,$tableName,$appname,strtolower($singular),strtolower($plural),strtolower($tableName),strtolower($appname),strtoupper($singular),strtoupper($plural),strtoupper($tableName),strtoupper($appname)),
							$templateFile->destination);

					$smarty->clearAllAssign();
					$smarty->assign("appname",$appname);
					$smarty->assign("singular",$singular);
					$smarty->assign("plural",$plural);
					$smarty->assign("prefix",$prefix);
					$smarty->assign("templateFilename",$templateFilename);
					$smarty->assign("table",$dbSchema->Tables[$tableName]);
					$smarty->assign("connection",$cstring);
					$smarty->assign("appRoot",$appRoot);
					$smarty->assign("includePath",$includePath);
					$smarty->assign("includePhar",$includePhar);
					$smarty->assign("enableLongPolling",$enableLongPolling);
					$smarty->assign("PHREEZE_VERSION",Phreezer::$Version);

					$tableInfos = Array();
					
					// add all tables to a tableInfos array that can be used for cross-referencing by table name
					foreach ($dbSchema->Tables as $table)
					{
						if ($table->GetPrimaryKeyName())
						{
							$tableName = $table->Name;
							$tableInfos[$tableName] = Array();
							$tableInfos[$tableName]['table'] = $dbSchema->Tables[$tableName];
							$tableInfos[$tableName]['singular'] = $_REQUEST[$tableName."_singular"];
							$tableInfos[$tableName]['plural'] = $_REQUEST[$tableName."_plural"];
							$tableInfos[$tableName]['prefix'] = $_REQUEST[$tableName."_prefix"];
							$tableInfos[$tableName]['templateFilename'] = $templateFilename;
						}
					}
					
					$smarty->assign("tableInfos",$tableInfos);
					$smarty->assign("selectedTables",$selectedTables);
					
					foreach ($parameters as $key => $val)
					{
						$smarty->assign($key,$val);
					}

					//print "<pre>"; print_r($dbSchema->Tables[$tableName]->PrimaryKeyIsAutoIncrement()); die();
					if ($debug)
					{
						$debug_output .= "\r\n###############################################################\r\n"
						. "# $templateFilename\r\n###############################################################\r\n"
						. $smarty->fetch($templateFile->source) . "\r\n";
					}
					else
					{
						$zipFile->addFile( $smarty->fetch($templateFile->source) , $templateFilename);
					}

				}
			}

		}

		if ($debug)
		{
			header("Content-type: text/plain");
			print $debug_output;
		}
		else
		{
			// now output the zip as binary data to the browser
			header("Content-type: application/force-download");

			// laplix 2007-11-02.
			// Use the application name provided by the user in show_tables.
			//header("Content-disposition: attachment; filename=".str_replace(" ","_",$G_CONNSTR->DBName).".zip");
			header("Content-disposition: attachment; filename=".str_replace(" ","_",strtolower(str_replace("/","", $appRoot))).".zip");

			header("Content-Transfer-Encoding: Binary");
			header('Content-Type: application/zip');
			print $zipFile->file();
		}
	}
}
?>