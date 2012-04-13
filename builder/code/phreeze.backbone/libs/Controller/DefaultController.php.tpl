<?php
/** @package {$connection->DBName|studlycaps}::Controller */

/** import supporting libraries */
require_once("{$appname}BaseController.php");

/**
 * DefaultController is the entry point to the application
 *
 * @package {$connection->DBName|studlycaps}::Controller
 * @author ClassBuilder
 * @version 1.0
 */
class DefaultController extends {$appname}BaseController
{

	/**
	 * Override here for any controller-specific functionality
	 */
	protected function Init()
	{
		parent::Init();

		// TODO: add controller-wide bootstrap code
	}

	/**
	 * Display the home page for the application
	 */
	public function Home()
	{
		$this->Render();
	}

	/**
	 * Displayed when an invalid route is specified
	 */
	public function Error404()
	{
		$this->Render();
	}

	/**
	 * Display a fatal error message
	 */
	public function ErrorFatal()
	{
		$this->Render();
	}

	public function ErrorApi404()
	{
		$this->RenderErrorJSON('An unknown API endpoint was requested.');
	}

}
?>