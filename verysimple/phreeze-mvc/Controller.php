<?php
namespace PhreezeMVC;

class Controller {

	static function Run($controllerName,$methodName,$config,$request) {
		
		$controller = new $controllerName($config);
		return $controller->$methodName($request);
		
	}
	
	public function __construct($config = null) {
		
	}

}