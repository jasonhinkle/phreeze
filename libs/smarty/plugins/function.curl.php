<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {curl} function plugin
 *
 * Type:     function<br>
 * Name:     curl<br>
 * Purpose:  make http request to a url using curl and return the results.
 *           If method is post, querystring will be removed from end of url
 *           send as post data
 * @param string url
 * @param string method GET or POST
 * @param Smarty
 */
function smarty_function_curl($params, &$smarty)
{

    if (!isset($params['url']) || $params['url'] == '') 
    {
        $smarty->trigger_error("curl_post: missing 'url' parameter");
        return;
    }
    
    // TODO: add support for GET
    return _curl_post($params['url'],array());

}

// private function - does http post command
function _curl_post ($pageSpec, $data, $verify_cert = false) 
{
	// convert the data array into a url querystring
	$post_data = "";
	$delim = "";
	foreach (array_keys($data) as $key)
	{
		$post_data .= $delim . $key ."=" . $data[$key];
		$delim = "&";
	}

	$agent = "curl_post.1";
	// $header[] = "Accept: text/vnd.wap.wml,*.*";    
	$ch = curl_init($pageSpec);
	
	curl_setopt($ch,		CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch,		CURLOPT_VERBOSE, 0); ########### debug
	curl_setopt($ch,	    CURLOPT_USERAGENT, $agent);
	//curl_setopt($ch,	    CURLOPT_HTTPHEADER, $header);
	curl_setopt($ch,		CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch,		CURLOPT_COOKIEJAR, "cook");
	curl_setopt($ch,		CURLOPT_COOKIEFILE, "cook");
	curl_setopt($ch,		CURLOPT_POST, 1);
	curl_setopt($ch,		CURLOPT_POSTFIELDS, $post_data);
	curl_setopt($ch,		CURLOPT_SSL_VERIFYPEER, $verify_cert);
	curl_setopt($ch,		CURLOPT_NOPROGRESS, 1);
	
	$tmp = curl_exec ($ch);
	$error = curl_error($ch);
	
	if ($error != "") {$tmp .= $error;}
	curl_close ($ch);
	
	return $tmp;
}
/* vim: set expandtab: */

?>
