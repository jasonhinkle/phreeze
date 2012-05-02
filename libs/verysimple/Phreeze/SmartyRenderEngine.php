<?php
/** @package    verysimple::Phreeze */

/** import supporting libraries */
require_once("smarty/Smarty.class.php");
require_once("verysimple/Phreeze/IRenderEngine.php");

/**
 * SmartyRenderEngine is an implementation of IRenderEngine that uses
 * the Smarty template engine to render views
 *
 * @package    verysimple::Phreeze
 * @author     VerySimple Inc.
 * @copyright  1997-2011 VerySimple, Inc.
 * @license    http://www.gnu.org/licenses/lgpl.html  LGPL
 * @version    1.0
 */
class SmartyRenderEngine extends Smarty implements IRenderEngine
{

	/**
	 * @param string $templatePath
	 * @param string $compilePath
	 */
	function __construct($templatePath = '',$compilePath = '')
	{
		parent::__construct();

		if ($templatePath) $this->template_dir = $templatePath;

		if ($compilePath)
		{
			$this->compile_dir = $compilePath;
			$this->config_dir = $compilePath;
			$this->cache_dir = $compilePath;
		}
	}

	/**
	 * @see IRenderEngine::assign()
	 */
	function assign($key, $value)
	{
		return parent::assign($key,$value,false);
	}

	/**
	 * @see IRenderEngine::display()
	 */
	function display($template)
	{
		return parent::display($template);
	}

	/**
	 * @see IRenderEngine::fetch()
	 */
	function fetch($template)
	{
		return parent::fetch($template);
	}

	/**
	 * @see IRenderEngine::clear()
	 */
	function clear($key)
	{
		$this->clearAssign($key);
	}

	/**
	 * @see IRenderEngine::clearAll()
	 */
	function clearAll()
	{
		$this->clearAllAssign();
	}

	/**
	 * @see IRenderEngine::getAll()
	 */
	function getAll()
	{
		return $this->getTemplateVars();
	}


}

?>