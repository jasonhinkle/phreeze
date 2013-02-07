<?php
/**
 * Phreeze Command Line Phar Builder
 * 
 * @example  php build.php
 */

if ( php_sapi_name() == 'cli' )
{
	$APP_ROOT = realpath("./");
	
	set_include_path(
			$APP_ROOT . '/../libs' . PATH_SEPARATOR .
			get_include_path()
	);
	
	require_once 'verysimple/Phreeze/Phreezer.php';
	$version = Phreezer::$Version;
	$path = 'phreeze-'.$version.'.phar'; 
	
	echo "Generating phreeze-".$version.".phar ...\n";
	
	$archive = new Phar($path);
	$archive->buildFromDirectory('../libs');
	$archive->setStub(file_get_contents('stub.php'));
	
	echo "Finished.\n";
}
else
{
	echo 'build.php should be run from the command line';
}
