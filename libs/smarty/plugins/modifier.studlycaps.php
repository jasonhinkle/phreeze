<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty studlycaps modifier plugin
 *
 * Type:     modifier<br>
 * Name:     studlycaps<br>
 * Purpose:  convert string to studlycapscase
 * @param string
 * @return string
 */
function smarty_modifier_studlycaps($string)
{
	return ucwords(
			preg_replace_callback(
					"/(\_(.))/",
					create_function('$matches', 'return strtoupper($matches[2]);'),
					strtolower($string)
			)
	);
}

?>
