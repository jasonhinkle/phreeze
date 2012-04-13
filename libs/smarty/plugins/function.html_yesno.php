<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {html_yesno} function plugin
 *
 * Type:     function<br>
 * Name:     ternary<br>
 * Input:<br>
 *           - name         (required) - name of checkbox
 *           - yesval       (optional) - value of checkbox
 *           - noval        (optioal) - if this == value then box is checked
 *           - value        (required) - (if null will be set = to noval)
 * Purpose:  use in place of html_checkboxes when you only want one yes/no style checkbox
 * @param array
 * @param Smarty
 * @return string
 */
function smarty_function_html_yesno($params, &$smarty)
{
    $yesval = isset($params["yesval"]) ? $params["yesval"] : "1";
    $noval = isset($params["noval"]) ? $params["noval"] : "0";
	
	$val = isset($params["value"]) ? $params["value"] : "0";
    
    $yeschecked = ($yesval == $val) ? "checked=\"checked\"" : "";
    $nochecked = ($noval == $val) ? "checked=\"checked\"" : "";
    
    $yesno = "<input class=\"radio\" id=\"yes_" . $params["name"] . "\" type=\"radio\" name=\"" . $params["name"] . "\" value=\"" . $yesval . "\" " . $yeschecked . " /> <label for=\"yes_" . $params["name"] . "\">Yes</label>";
    $yesno .= "<input class=\"radio\" id=\"no_" . $params["name"] . "\" type=\"radio\" name=\"" . $params["name"] . "\" value=\"" . $noval . "\" " . $nochecked . " /> <label for=\"no_" . $params["name"] . "\">No</label>";
    return $yesno;
}

?>
