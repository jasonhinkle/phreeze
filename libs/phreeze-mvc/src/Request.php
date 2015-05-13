<?php
namespace PhreezeMVC;

/**
 * Wrapper for an HTTP request
 * @author jason
 */
class Request {

	public $method;
	public $uri;
	public $url;
	public $params;

	/**
	 * Returns a URL Route Parameter if it exists, otherwise returns the default value
	 * @param string $key
	 * @param string $default
	 * @return string
	 */
	public function getParam($key,$default=null) {
		return isset($this->params[$key]) ? $this->params[$key] : $default;
	}
	
	/**
	 * Returns a GET variable if it exists, otherwise returns the default value
	 * @param string $key
	 * @param string $default
	 * @return string
	 */
	public function getGet($key,$default=null) {
		return isset($_GET[$key]) ? $_GET[$key] : $default;
	}
	
	/**
	 * Returns a POST variable if it exists, otherwise returns the default value
	 * @param string $key
	 * @param string $default
	 * @return string
	 */
	public function getPost($key,$default=null) {
		return isset($_POST[$key]) ? $_POST[$key] : $default;
	}
	
	/**
	 * Return the Post body as a string.  Note that this can 
	 * only be read once.
	 * @return string
	 */
	public function getBodyAsString() {
		return file_get_contents('php://input');
	}
	
	/**
	 * Return the Post body as a stream.  This makes a seekable
	 * copy of the stream so that it can be re-read
	 * @return resource
	 */
	public function getBodyAsStream() {
		$rawInput = fopen('php://input', 'r');
		$tempStream = fopen('php://temp', 'r+');
		stream_copy_to_stream($rawInput, $tempStream);
		rewind($tempStream);
		return $tempStream;
	}
	
	/**
	 * Return all HTTP headers using getallheaders() if available,
	 * otherwise manually build the headers using $_SERVER superglobal
	 * @return array
	 */
	public function getHeaders() {
		$headers = getallheaders();
	
		if ($headers === false) {
			$headers = array();
			foreach ($_SERVER as $name => $value) { 
				if (substr($name, 0, 5) == 'HTTP_') { 
					$name = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5))))); 
					$headers[$name] = $value; 
				}
				else if ($name == "CONTENT_TYPE") { 
					$headers["Content-Type"] = $value; 
				}
				else if ($name == "CONTENT_LENGTH") { 
					$headers["Content-Length"] = $value; 
				}
			}
		}
	
		return $headers;
	}
	
}