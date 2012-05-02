<?php
/**
 * @package Adserv
 *
 * MACHINE-SPECIFIC CONFIGURATION SETTINGS
 *
 * The configuration settings in this file can be changed to suit the
 * machine on which the app is running (ex. local, staging or production).
 *
 * This file should not be added to version control, rather a template
 * file should be added instead and then copied for each install
 */

require_once 'verysimple/Phreeze/ConnectionSetting.php';
require_once("verysimple/HTTP/RequestUtil.php");

/**
 * Normally DB connection info would go here, but phreeze builder doesn't actually
 * have any back-end schema
 */
GlobalConfig::$CONNECTION_SETTING = new ConnectionSetting();
GlobalConfig::$CONNECTION_SETTING->ConnectionString = "";
GlobalConfig::$CONNECTION_SETTING->DBName = "";
GlobalConfig::$CONNECTION_SETTING->Username = "";
GlobalConfig::$CONNECTION_SETTING->Password = "";

/** the root url of the application with trailing slash, for example http://localhost/adserv/ */
GlobalConfig::$ROOT_URL = RequestUtil::GetServerRootUrl() . 'phreeze/builder/';

/** timezone */
// date_default_timezone_set("UTC");

/** functions for php 5.2 compatibility */
if (!function_exists('lcfirst')) {
	function lcfirst($string) {
		return substr_replace($string, strtolower(substr($string, 0, 1)), 0, 1);
	}
}

/** level 2 cache */

/** additional machine-specific settings */

?>