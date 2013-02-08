<?php
/** @package Builder::Controller */

/** import supporting libraries */
require_once("BaseController.php");

/**
 * DefaultController is the entry point to the application
 *
 * @package Adserv::Controller
 * @author ClassBuilder
 * @version 1.0
 */
class DefaultController extends BaseController
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
		$this->Assign('hidenav', true);
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

}
?>