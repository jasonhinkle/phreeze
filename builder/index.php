<?php
/** @package    Phreeze Builder */

/* GlobalConfig object contains all configuration information for the app */
include_once("_global_config.php");
include_once("_app_config.php");
include_once("_machine_config.php");

/* require framework libs */
require_once("verysimple/Phreeze/Dispatcher.php");

// the global config is used for all dependency injection
$gc = GlobalConfig::GetInstance();

try
{
	Dispatcher::Dispatch(
		$gc->GetPhreezer(),
		$gc->GetRenderEngine(),
		'',
		$gc->GetContext(),
		$gc->GetRouter()
	);
}
catch (exception $ex)
{
	$gc->GetRenderEngine()->assign("message",$ex->getMessage());
	$gc->GetRenderEngine()->assign("stacktrace",$ex->getTraceAsString());
	$gc->GetRenderEngine()->assign("code",$ex->getCode());

	try
	{
		$gc->GetRenderEngine()->display("DefaultErrorFatal.tpl");
	}
	catch (Exception $ex2)
	{
		// this means there is an error with the template, in which case we can't display it nicely
		echo "<style>* { font-family: verdana, arial, helvetica, sans-serif; }</style>\n";
		echo "<h1>Fatal Error:</h1>\n";
		echo '<h3>' . htmlentities($ex->getMessage()) . "</h3>\n";
		echo "<h4>Original Stack Trace:</h4>\n";
		echo '<textarea wrap="off" style="height: 200px; width: 100%;">' . htmlentities($ex->getTraceAsString()) . '</textarea>';
		echo "<h4>In addition to the above error, the default error template could not be displayed:</h4>\n";
		echo '<textarea wrap="off" style="height: 200px; width: 100%;">' . htmlentities($ex2->getMessage()) . "\n\n" . htmlentities($ex2->getTraceAsString()) . '</textarea>';
	}

}

?>