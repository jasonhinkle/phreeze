<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {html_dynamic_options} function plugin is an adapter for html_options that allows 
 * a comma-separated list instead of an associative array.  Useful for situations where you 
 * do not want to assign the choices in PHP.  Except for parsing the options parameter, all
 * functionality is delegated to html_options so the 
 *
 * Type:     function<br>
 * Name:     html_dynamic_options<br>
 * Input:<br>
 *           - name       (optional) - string default "select"
 *           - values     (required if no options supplied) - array, OR STRING IN THIS FORMAT "val1,val2,val3" ...
 *           - options    (required if no values supplied) - array, OR STRING IN THIS FORMAT "key1=val1,key2=val2" ...
 *           - selected   (optional) - string default not set
 *           - output     (optional) - if not supplied, will be set to the same as "values"
 * Purpose:  Prints the list of <option> tags generated from
 *           the passed parameters
 * @link http://smarty.php.net/manual/en/language.function.html.options.php {html_image}
 *      (Smarty online manual)
 * @author Jason Hinkle <verysimple.com>
 * @param array
 * @param Smarty
 * @return string
 * @uses smarty_function_escape_special_chars()
 */
function smarty_function_html_dynamic_options($params, &$smarty)
{
	require_once("function.html_options.php");
	
	if (isset($params["options"]) && (!is_array($params["options"])) )
	{
		$pairs = explode(",",$params["options"]);
		$params["options"] = array();
		foreach ($pairs as $pair)
		{
			$keyval = explode("=",$pair);
			$params["options"][$keyval[0]] = isset($keyval[1]) ? $keyval[1] : $keyval[0];
		}
	}

	if ( isset($params["values"]) )
	{
		if (!is_array($params["values"]))
		{
			$params["values"] = explode(",",$params["values"]);
		}

		if (!isset($params["output"]))
		{
			$params["output"] = $params["values"];
		}

	}
	
	return smarty_function_html_options($params, $smarty);
}

?>
