<?php
/**
 * Smarty siteREACT plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty siteREACT
 *
 * @param array
 * @param Smarty
 * @return string
 */
function smarty_function_sr_property($params, &$smarty)
{
	$page = null;

	if (isset($params["page"]))
	{
		// specific page object was provided as a parameter
		$page = $params["page"];
	}
	else
	{
		// use the default which is the primary page that was assigned
		$vars = $smarty->get_template_vars();
		$page = $vars["page"];
	}
	
	$name = $params["name"];
	
	$val = "";
	
	if ($page->GetProperty($name) == null)
	{
		$val = isset($params["nullval"]) ? $params["nullval"] : "<span style='background-color:red;color:#ffffff;padding:2px;border:solid 1px black;'>UNDEFINED: '$name'</span>";
	}
	else
	{
		$val = $page->GetProperty($name)->ParsedValue;
	}
	
	if ($val == "")
	{
		$val = isset($params["default"]) ? $params["default"] : "";
	}
	
	return $val;
	
}

?>
