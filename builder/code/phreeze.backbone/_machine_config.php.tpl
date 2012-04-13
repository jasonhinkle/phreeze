<?php
/**
 * @package {$appname}
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

/** database connection settings */
GlobalConfig::$CONNECTION_SETTING = new ConnectionSetting();
GlobalConfig::$CONNECTION_SETTING->ConnectionString = "{$connection->Host}:{$connection->Port}";
GlobalConfig::$CONNECTION_SETTING->DBName = "{$connection->DBName}";
GlobalConfig::$CONNECTION_SETTING->Username = "{$connection->Username}";
GlobalConfig::$CONNECTION_SETTING->Password = "{$connection->Password}";

/** the root url of the application with trailing slash, for example http://localhost/{$appname|lower}/ */
GlobalConfig::$ROOT_URL = RequestUtil::GetServerRootUrl() . '{$appname|lower}/';

/** timezone */
// date_default_timezone_set("UTC");

/** level 2 cache */
// GlobalConfig::$LEVEL_2_CACHE = new MemCacheProxy(array('localhost'=>'11211'));
// GlobalConfig::$LEVEL_2_CACHE_TEMP_PATH = sys_get_temp_dir();
// GlobalConfig::$LEVEL_2_CACHE_TIMEOUT = 15;

/** additional machine-specific settings */

?>