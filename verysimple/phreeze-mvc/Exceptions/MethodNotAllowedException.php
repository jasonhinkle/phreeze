<?php
namespace PhreezeMVC\Exceptions;
use PhreezeMVC\Request;

class MethodNotAllowedException extends \Exception
{
	public function __construct(Request $request,$allowedMethods,$message='Route not found',$code='405',$previous=null) {
		parent::__construct($message,$code,$previous);
		$this->request = $request;
		$this->allowedMethods = $allowedMethods;
	}
	
	/**
	 * 
	 * @var Request
	 */
	public $request;
	
	public $allowedMethods;
}