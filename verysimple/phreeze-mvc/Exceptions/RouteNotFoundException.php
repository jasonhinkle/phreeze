<?php
namespace PhreezeMVC\Exceptions;
use PhreezeMVC\Request;

class RouteNotFoundException extends \Exception
{
	public function __construct(Request $request,$message='Route not found',$code='404',$previous=null) {
		parent::__construct($message,$code,$previous);
		$this->request = $request;
	}
	
	/**
	 * @var Request
	 */
	public $request;
}