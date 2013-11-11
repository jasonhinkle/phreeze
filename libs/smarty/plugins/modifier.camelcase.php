<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty camelcase modifier plugin
 *
 * Type:     modifier<br>
 * Name:     studlycaps<br>
 * Purpose:  convert string to camelcase
 * @param string
 * @return string
 */
function smarty_modifier_camelcase($string)
{
    $string = ucwords(
    		preg_replace_callback(
    				"/(\_(.))/",
    				create_function('$matches', 'return strtoupper($matches[2]);'),
    				strtolower($string)
    		)
    );
    
    return strtolower(substr($string,0,1)) . substr($string,1);
}

?>
