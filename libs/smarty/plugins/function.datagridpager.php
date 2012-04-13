<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {datagridpager} function plugin
 *
 * Type:     function<br>
 * Name:     datagrid<br>
 * Input:<br>
 *           - page       (required) - DataPage object
 * Purpose:  used to display a PageModel object pagination links
 * @param array
 * @param Smarty
 * @return string
 */
 
function smarty_function_datagrid_geturl($original, $key, $val)
{
    if (strpos($original,"?") > 0)
    {
        $delim = "&amp;";
    }
    else
    {
        $delim = "?";
    }
    return $original . $delim . $key . "=" . $val;
}

/* returns html page navigation
 */
function smarty_function_datagrid_get_pagenav(&$page, $page_url, $result_name = "Result")
{
    // if page url parameter is specifed, then we use that.
    // otherwise, reconstruct it from the querystring.  we need to do this
    // in order to change the page arg without disturbing the other vars
    if (!$page_url)
    {
        $page_url = $_SERVER["PHP_SELF"];
        $delim = "?";
        
        // reconstruct the querystring
        foreach ($_REQUEST as $key => $val)
        {
            if ($key == "PHPSESSID" || $key == "cp")
            {
                // don't pass these through
            }
            else
            {
                $page_url .= $delim . urlencode($key) . "=" . urlencode($val);
                $delim = "&amp;";
            }
           
        }
    }
    
    $returnval = "<p class=\"pageNav\">" . $page->TotalResults . " ".$result_name."(s)";

    if ($page->CurrentPage > 1 || $page->TotalPages > $page->CurrentPage)
    {
        
        $returnval .= " Page " . $page->CurrentPage . " of " . $page->TotalPages;
        
        if ($page->CurrentPage > 1)
        {
            $returnval .= " <a class=\"pageNavPrevious\" href=\"" . $page_url . $delim . "cp=" . ($page->CurrentPage - 1). "\">Previous</a>";
        }
        if ($page->TotalPages > $page->CurrentPage)
        {
            $returnval .= " <a class=\"pageNavNext\" href=\"" . $page_url . $delim . "cp=" . ($page->CurrentPage + 1). "\">Next</a>";
        }
    }
    
    $returnval .= "</p>";
    
    return $returnval;
}

/* returns a datagrid, which is basically an html datagrid
 * see the plugin docs for parameters
 */
function smarty_function_datagridpager($params, &$smarty)
{
    $page = isset($params["page"]) ? $params["page"] : null;
    $record_name = isset($params["record_name"]) ? $params["record_name"] : "Record";
    $no_records = isset($params["no_records"]) ? $params["no_records"] : "No matching " . $record_name . "s";
    
    $return_val = "";
    
    if (!$page)
    {
        return "Error in datagrid: page parameter is required";
    }
    
        
    $return_val .= smarty_function_datagrid_get_pagenav($page, $page_url, $record_name);

    return $return_val;
}


?>
