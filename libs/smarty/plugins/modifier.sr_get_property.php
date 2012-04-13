<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty sr_get_property modifier plugin
 *
 * Type:     modifier<br>
 * Name:     sr_get_property<br>
 * Purpose:  to get a dynamic property of a page without throwing undefined warning
 * @param Page
 * @param string name property name
 * @param string default if val is blank (optional)
 * @param string nullval if property is undefined (optional)
 * @return string
 * @example {$page|sr_get_property:name[:default][:nullval]}
 */
function smarty_modifier_sr_get_property($page, $name, $default="",$nullval="Undefined Property")
{

    $prop = $page->GetProperty($name);
	
	if ($prop == null)
	{
		$val = $nullval;
	}
	else
	{
		$val = $prop->ParsedValue;
	}
	
	if ($val == "")
	{
		$val = $default;
	}
	
    return $val;
}

/* vim: set expandtab: */

?>
