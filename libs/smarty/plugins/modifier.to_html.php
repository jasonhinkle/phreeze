<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty to_html modifier plugin
 *
 * Type:     modifier<br>
 * Name:     to_html<br>
 * Purpose:  format a string for html, mainly to show line breaks properly
 * @param string
 * @return string
 */
function smarty_modifier_to_html($string)
{
    return str_replace("\n","<br />",$string);
}

/* vim: set expandtab: */

?>
