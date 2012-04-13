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
function smarty_modifier_underscore2space($string)
{
    return ucwords(str_replace("_"," ",strtolower($string)));
}

?>
