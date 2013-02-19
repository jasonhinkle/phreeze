<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty convert camelcase to spaces
 *
 * Type:     modifier<br>
 * Name:     studlycaps<br>
 * Purpose:  convert string to camelcase
 * @param string
 * @return string
 */
function smarty_modifier_camelcase2space($string)
{
    $string = preg_replace('/(?<=\\w)(?=[A-Z])/'," $1", $string);
	return trim($string);
}

?>
