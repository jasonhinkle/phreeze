<?php
/**
 * This bootstrap file will setup the enviroment so that
 * a Phreeze application can be tested from the command line.
 * 
 * The phpunit.xml file in this directory instructs PHPUnit
 * to include this file prior to running any tests.
 * 
 * Note that code executed in this file is not included in
 * the code coverage report
 */

/* 
 * setting server variables to simulate a web enviroment
 */
$_SERVER["REQUEST_URI"] = "/";
$_SERVER["HTTP_HOST"] = "localhost";
$_SERVER["REMOTE_ADDR"] = "1.1.1.1";
$_SERVER['HTTP_USER_AGENT'] = 'CMD';
$_SERVER['REQUEST_METHOD'] = "GET";

include_once("../_global_config.php");

// before _app_config is loaded we need to override the app root
GlobalConfig::$APP_ROOT = realpath("../");
include_once("../_app_config.php");

if (file_exists("../_machine_config.php")) {
	require_once("../_machine_config.php");
}
else {
	require_once("../_machine_config.DEFAULT.php");
}

/* require framework libs */
require_once("verysimple/HTTP/RequestUtil.php");
require_once("verysimple/Phreeze/Phreezer.php");
require_once("verysimple/Phreeze/Controller.php");
require_once("verysimple/Phreeze/Dispatcher.php");
require_once("verysimple/Util/ExceptionFormatter.php");
require_once("verysimple/Phreeze/SmartyRenderEngine.php");
require_once("verysimple/Phreeze/MockRouter.php");