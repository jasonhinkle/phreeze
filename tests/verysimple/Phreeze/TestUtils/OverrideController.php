<?php
/**
 * @package tests::Shared
 */

require_once 'verysimple/Phreeze/Controller.php';

/**
 * Extends the controller and exposes various protected and
 * private functions so that we can access them during testing
 */
class OverrideController extends Controller
{
	protected function Init() {
		$this->ModelName = "NULL";
	}
	function OverrideSetCurrentUser($usr) {
		return $this->SetCurrentUser($usr);
	}
	function OverrideGetCurrentUser() {
		return $this->GetCurrentUser();
	}
	function OverrideGetContext($key) {
		return $this->Context->Get($key);
	}
	function OverrideSetContext($key,$val) {
		return $this->Context->Set($key,$val);
	}
}
?>