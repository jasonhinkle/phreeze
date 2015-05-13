<?php
namespace PhreezeMVC;

require __DIR__.'/../../vendor/nikic/fast-route/src/bootstrap.php';
use FastRoute;

class Router {
	
	/**
	 * @var FastRoute\Dispatcher\GroupCountBased
	 */
	private $dispatcher;
	
	/**
	 * @var FastRoute\RouteCollector
	 */
	private $collector;
	
	/**
	 * @var string
	 */
	private $prefix;
	
	/**
	 * @var bool
	 */
	private $routesHaveChanged = true;

	/**
	 * Constructor instantiates the Router
	 * @param string $prefix provide if this web app is is a sub-directory and not the root web directory (ex "/myapp")
	 */
	public function __construct($prefix = '') 
	{

		$this->prefix = $prefix;
		
		// the collector is instantiated when we instantiate the router.  the dispatcher in instantiated after all routes are added
		$this->collector = new FastRoute\RouteCollector(
			new FastRoute\RouteParser\Std(), new FastRoute\DataGenerator\GroupCountBased()
		);
	
	}
	
	/**
	 * Add a route to the application
	 * @param string $method HTTP request method (GET, POST, PUT, DELETE, HEAD)
	 * @param string $pattern The regex pattern to match
	 * @param callable $handler a callback with the signature: function(PhreezeMVC\Request $req)
	 */
	public function route($method, $pattern, callable $handler) {
		
		$this->routesHaveChanged = true;
		return $this->collector->addRoute($method, $pattern, $handler);
	}
	
	/**
	 * Alias for route('GET',$pattern, $handler)
	 * @param unknown $pattern
	 * @param callable $handler
	 */
	public function get($pattern, callable $handler) {
		return $this->route('GET', $pattern, $handler);
	}
	
	/**
	 * Alias for route('POST',$pattern, $handler)
	 * @param unknown $pattern
	 * @param callable $handler
	 */
	public function post($pattern, callable $handler) {
		return $this->route('POST', $pattern, $handler);
	}
	
	/**
	 * Alias for route('PUT',$pattern, $handler)
	 * @param unknown $pattern
	 * @param callable $handler
	 */
	public function put($pattern, callable $handler) {
		return $this->route('PUT', $pattern, $handler);
	}
	
	/**
	 * Alias for route('DELETE',$pattern, $handler)
	 * @param unknown $pattern
	 * @param callable $handler
	 */
	public function delete($pattern, callable $handler) {
		return $this->route('DELETE', $pattern, $handler);
	}
	
	/**
	 * Parse the method/URI and call the approriate route handler
	 * @param string $method HTTP request method (default is $_SERVER['REQUEST_METHOD'])
	 * @param string $uri the route URI (default is normalized version of $_SERVER['REQUEST_URI'])
	 * @throws Exceptions\RouteNotFoundException
	 * @throws Exceptions\MethodNotAllowedException
	 * @return mixed whatever is returned from the route handler specified in route()
	 */
	public function dispatch($method = null, $uri = null) {

		// dispatcher can be initialized now
		if ($this->routesHaveChanged) $this->dispatcher = new FastRoute\Dispatcher\GroupCountBased($this->collector->getData());
		$this->routesHaveChanged = false;
		
		$request = new Request();
		$request->method = $method ? $method : $_SERVER['REQUEST_METHOD'];
		$request->url = substr($uri ? $uri : $_SERVER['REQUEST_URI'],strlen($this->prefix));
		$request->uri = parse_url($request->url, PHP_URL_PATH);
		
		$routeInfo = $this->dispatcher->dispatch($request->method, $request->uri);

		switch ($routeInfo[0]) 
		{
			case FastRoute\Dispatcher::FOUND:
				
				// list($result,$handler,$params) = $routeInfo;
				$request->params = $routeInfo[2];
				return call_user_func($routeInfo[1],$request);
				
			case FastRoute\Dispatcher::NOT_FOUND:
				
				throw new Exceptions\RouteNotFoundException($request);
				break;
				
			case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
				
				// list($result,$allowedRoutes) = $routeInfo;
				throw new Exceptions\MethodNotAllowedException($request,$routeInfo[1]);
				break;
		}
	}

}
