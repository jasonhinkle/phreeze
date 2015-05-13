<?php
namespace PhreezeMVC;

class Response {

	/**
	 * @var PhreezeMVC\Request
	 */
	public $request;
	
	/**
	 * @var string or callable
	 */
	public $output;
	
	/**
	 * @var string
	 */
	public $contentType = null;
	
	/**
	 * @var string
	 */
	public $contentDisposition = null;
	
	/**
	 * @param Request $request
	 * @param Variant $output string or callable with signature: function(PhreezeMVC\Response $response)
	 */
	public function __construct(Request $request, $output) {
		$this->request = $request;
		$this->output = $output;
	}
	
	public function sendHeaders() {
		if ($this->contentType) header('Content-Type: ' . $this->contentType);
		if ($this->contentDisposition) header('Content-Disposition: ' . $this->contentDisposition);
	}
	
	public function send() {

		if (is_callable($this->output)) {
			call_user_func($this>output,$this);
		}
		else {
			$this->sendHeaders();
			echo (string) $this->output;
		}
	}

}