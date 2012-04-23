<?php
require_once('IRouter.php');

/**
 * @author eventricsupport
 *
 * Sets up a mock router for testing purposes.
 */
class MockRouter implements IRouter
{
	private $_params = array();
	private $_uri;
	private $_url;

	/**
	 *
	 * @param unknown_type $paramName
	 * @param unknown_type $paramName
	 */
	public function SetUrlParam( $paramName, $value )
	{
		$this->_params[$paramName] = $value;
	}

	/**
	 * @inheritdocs
	 */
	public function GetRoute( $uri = "" )
	{

	}

	/**
	 * @see IRouter::GetUri()
	 */
	public function GetUri()
	{
		return $this->_uri;
	}

	/**
	 * @inheritdocs
	 */
	public function GetUrl( $controller, $method, $params = '' )
	{
		return $this->_url;
	}

	/**
	 * @inheritdocs
	 */
	public function GetUrlParams()
	{
		return $this->_params;
	}

	/**
	 * @inheritdocs
	 */
	public function GetUrlParam($paramKey, $default = '')
	{
		return array_key_exists($paramKey, $this->_params) ? $this->_params[$paramKey] : "";
	}

	/**
	 *
	 * @param unknown_type $value
	 */
	public function SetUri( $value )
	{
		$this->_uri = $value;
	}

	/**
	 *
	 * @param unknown_type $value
	 */
	public function SetUrl( $value )
	{
		$this->_url = $value;
	}

	public function ClearUrlParams()
	{
		$this->_params = array();
	}
}
?>