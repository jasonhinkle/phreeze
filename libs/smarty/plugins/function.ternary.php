<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {ternary} function plugin
 *
 * Type:     function<br>
 * Name:     ternary<br>
 * Input:<br>
 *           - expression    (required) - boolean
 *           - istrue        (required) - value if true
 *           - isfalse       (required) - value if false
 * Purpose:  Performs a ternary operation on the given expression and returns the appropriate value
 * @param array
 * @param Smarty
 * @return string
 */
function smarty_function_ternary($params, &$smarty)
{
	return ($params["expression"] ? $params["istrue"] : $params["isfalse"]);
}

?>
