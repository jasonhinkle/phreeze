<?php
/**
 * 
 */
require __DIR__.'/vendor/autoload.php';

use PhreezeORM\Phreezer;
use PhreezeMVC\Router;
use PhreezeMVC\Response;

$phreezer = new Phreezer();
$router = new Router('/phreeze4');

// ----- DEFINE ROUTES -----
$router->get('/', function($request) {
	return new Response($request,'Hello Phreeze4!');
});

$router->get('/user/{username}', function($request) {
	$response = new Response($request,print_r($request,1));
	$response->contentType = 'text/plain';
	return $response;
});


// ----- APP ENTRY POINT -----
try {
	
	$response = $router->dispatch();
	
	$response->send();
	
	//echo $response->request->getParam('username');
	//echo $response->request->getGet('var');
	
}
catch (PhreezeMVC\Exceptions\RouteNotFoundException $nfe) {
	echo $nfe->getMessage();
}
catch (PhreezeMVC\Exceptions\MethodNotAllowedException $mnae) {
	echo $mnae->getMessage();
}
catch (Exception $ex) {
	echo "Error: " . $ex->getMessage();
}