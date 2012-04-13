<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty plural modifier plugin attemps to make a singular word plural
 *
 * Type:     modifier<br>
 * Name:     studlycaps<br>
 * Purpose:  convert string word to plural form
 * @param string
 * @return string
 */
function smarty_modifier_plural($string)
{
	$lastletter = substr($string,-1);
    if ($lastletter == 'y') return substr($string,0,-1) . 'ies';
    if ($lastletter == 's' || $lastletter == 'x' || $lastletter == 'z') return $string . 'es';
    
    $last2letters = substr($string,-2);
    if ($last2letters == 'sh' || $last2letters == 'ch') return $string . 'es';
    
    return $string . 's';
}

?>
