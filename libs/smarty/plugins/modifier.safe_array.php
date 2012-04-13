<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty safe_array modifier plugin
 *
 * Type:     modifier<br>
 * Name:     safe_array<br>
 * Purpose:  show an array value without throwing a PHP error if undefined
 * @param string ignore value used only so modifier will work properly in concatanation
 * @param array the array
 * @param string the key that you hope is in the array
 * @param string if the array value is an object, you can specify which property you want (optional)
 * @param string if the key doesn't exist, the error message you want (optional)
 * @return string
 @example {$myarray|safe_array:key:property:errormessage}
 */
function smarty_modifier_safe_array($prefix, $myarray, $key, $property = "", $errormsg = "-NULL-")
{
    $returnval = ($errormsg != "-NULL-") ? $errormsg : ("ERROR: ARRAY KEY '" . $key . "' IS UNDEFINED");
    
    //print "<pre>key is $key</pre>";
    //print_r($myarray);
    
    if (isset($myarray[$key]))
    {
        if ($property != "")
        {
            $returnval = $myarray[$key]->$property;
        }
        else
        {
            $returnval = $myarray[$key];
        }
    }
    
    return $returnval;
}

/* vim: set expandtab: */

?>
