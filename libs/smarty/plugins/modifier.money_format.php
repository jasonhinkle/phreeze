<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty money_format modifier plugin
 *
 * Type:     modifier<br>
 * Name:     money_format<br>
 * Purpose:  Formats a number as a currency string
 * @link http://www.php.net/money_format
 * @param float
 * @param string format (default %n)
 * @return string
 */
function smarty_modifier_money_format($number, $format='%n')
{
	//return money_format($format, $number);
	return is_numeric($number) ? number_format($number, 2, '.', ',') : "NaN";
}

/* vim: set expandtab: */
?>