<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {datagrid} function plugin
 *
 * Type:     function<br>
 * Name:     datagrid<br>
 * Input:<br>
 *           - page       (required) - DataPage object
 *           - columns     (optional) - comma separated list in this format: 
 *                                      field1[|label1[|formula1]], field2[|label2[|formula2]], etc...
 *                                      The formula field is a sprintf expression or one of the following
 *                                      magic vars:  "date(m/d/Y)", "yes/no", "$"
 *                                      http://perldoc.perl.org/functions/sprintf.html for more info
 *           - edit_url    (optional) - url for editing a row
 *           - delete_url  (optional) - url for editing a row
 *           - page_url    (optional) - url for editing a row
 *           - primary_key (optional) - field that is the primary key for the object
 * Purpose:  used to display a PageModel object as a DataGrid
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
function smarty_function_datagrid_get_pagenav(&$page, $page_url, $result_name = "result")
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
function smarty_function_datagrid($params, &$smarty)
{
    $page = isset($params["page"]) ? $params["page"] : null;
    $column_string = isset($params["columns"]) ? $params["columns"] : null;
    $total_string = isset($params["totals"]) ? $params["totals"] : null;
    $sortable_string = isset($params["sortable"]) ? $params["sortable"] : null;
    $edit_url = isset($params["edit_url"]) ? $params["edit_url"] : null;
    $delete_url = isset($params["delete_url"]) ? $params["delete_url"] : null;
    $page_url = isset($params["page_url"]) ? $params["page_url"] : null;
    $primary_key = isset($params["primary_key"]) ? $params["primary_key"] : "Id";
    $record_name = isset($params["record_name"]) ? $params["record_name"] : "record";
    $no_records = isset($params["no_records"]) ? $params["no_records"] : "No matching " . $record_name . "s";
    $editButtonText = isset($params["EditButtonText"]) ? "Details" : "Edit";
    $tableClass = isset($params["table_class"]) ? $params["table_class"] : "basic";
    
    $return_val = "";
    
    if (!$page)
    {
        return "Error in datagrid: page parameter is required";
    }
    
    if ($sortable_string)
    {
        $sortable = explode(",",$sortable_string);
        $ob = isset($_REQUEST["ob"]) ? $_REQUEST["ob"] : "";
    
        $sort_url = $_SERVER["PHP_SELF"];
        $delim = "?";
            
        // reconstruct the querystring
        foreach ($_REQUEST as $key => $val)
        {
            if ($key == "PHPSESSID" || $key == "ob" || $key == "cp")
            {
                // don't pass these through
            }
            else
            {
                $sort_url .= $delim . urlencode($key) . "=" . urlencode($val);
                $delim = "&amp;";
            }
            
        }
    }
    else
    {
        $sortable = Array();
        $sort_url = "";
        $ob = "";
    }

    
    if ($column_string)
    {
        $buff = explode(",",$column_string);
        $columns = array_values($buff);
    }
    else
    {
        $buff = get_object_vars($page->ObjectInstance);
        $columns = array_keys($buff);
    }
    
    // if totals are specified, initialize the array that will hold the sum
    $totals = explode(",",$total_string);
    $totals_sum = Array();
    
    if ($total_string)
    {
		foreach($totals as $x)
		{
			$totals_sum[$x] = 0;
		}
	}

    
    if (count($page->Rows) > 0)
    {
    
        $return_val .= "<table class='".htmlentities($tableClass)."'>\n";
        
        $return_val .= "<thead>\n<tr>\n";
        if ($edit_url) {$return_val .= "<th>&nbsp;</th>\n";}
        foreach ($columns as $column)
        {
            $pair = explode("|",$column,3);
            $label = isset($pair[1]) ? $pair[1] : $pair[0];
            $return_val .= "<th>";
            
            $sort_key = "";
            
            if (in_array($pair[0], $sortable))
            {
                $sort_key = $pair[0];
            }
            else if (in_array($label, $sortable))
            {
                $sort_key = $label;
            }
            
            if ($sort_key)
            {
                $return_val .= "<a href='" . smarty_function_datagrid_geturl($sort_url,"ob",$sort_key) . "'>" . $label . "</a>";
                if ($ob == $sort_key)
                {
                    $return_val .= "&nbsp;&darr;";
                }
            }
            else
            {
                $return_val .= $label;
            }
            $return_val .= "</th>\n";
        }
        if ($delete_url) {$return_val .= "<th>&nbsp;</th>\n";}
        $return_val .= "</tr>\n</thead>\n";
        
        $return_val .= "<tbody>\n";

		$rowcounter = 0;
        foreach ($page->Rows as $obj)
        {
			$rowcounter++;
			
			$class = ($rowcounter % 2) ? "even" : "odd";
			
            $return_val .= "<tr class='$class'>\n";
            
            if ($edit_url) 
            {
                $return_val .= "<td class='$class'><div class='edit'><a href='" . smarty_function_datagrid_geturl($edit_url, $primary_key, $obj->$primary_key) . "'>$editButtonText</a></div></td>\n";
            }
            
            foreach ($columns as $column)
            {
                $pair = explode("|",$column,4);
                $prop = $pair[0];
                $formula = isset($pair[2]) ? $pair[2] : "";
                $style = isset($pair[3]) ? (" " . $pair[3]) : "";
                
                $cellclass = $class . $style;
                
                $orig_val = "";
                $formatted_val = "";
                
                // if this is a method, we need to parse it so it gets called properly.
                // TODO: recurse this so we can go deeper than 1 child
                $pair = explode("()->",$prop);
                if (count($pair) > 1)
                {
					// this is a method that returns an object and we are getting
					// a property of this object
					if(strstr($pair[1],"()"))
					{
						// this is a method call on the return object
						$pair2 = explode("()",$pair[1]);
						$orig_val = $obj->$pair[0]()->$pair2[0]();
					}
					else
					{
						//this is just a property on the return object
						$orig_val = $obj->$pair[0]()->$pair[1];
					}
                }
                elseif(strstr($prop,"()"))
                {
					// this is just a method call
					$pair = explode("()",$prop);
                    $orig_val = $obj->$pair[0]();
				}
                else
                {
                    $orig_val = $obj->$prop;
                }
                
                // sum up the totals columns if specified
				if ($total_string)
				{
					if (isset($totals_sum[$prop]))
					{
						$totals_sum[$prop] += $orig_val;
					}
				}

                // the third optional parameter is a formula.  see the function doc to see the 
                // additional allowed params
                $formatted_val = smarty_function_datagrid_format($orig_val,$formula);

                $return_val .= "<td class='$cellclass'>" . $formatted_val . "</td>\n";
            }
            
            if ($delete_url) 
            {
                $return_val .= "<td class='$class'><div class='deleteButton'><a onclick=\"return confirm('Delete this record?');\" href='" . smarty_function_datagrid_geturl($delete_url, $primary_key, $obj->$primary_key) . "'>Delete</a></div></td>\n";
            }
            $return_val .= "</tr>\n";
        }
        
        $return_val .= "</tbody>\n";
        
        // add the footer totals
		if ($total_string)
		{
            if ($edit_url) 
            {
                $return_val .= "<td class='footer'>&nbsp;</td>\n";
            }
			foreach ($columns as $column)
            {
				$pair = explode("|",$column,4);
				$prop = $pair[0];
                $formula = isset($pair[2]) ? $pair[2] : "";
                $style = isset($pair[3]) ? (" " . $pair[3]) : "";

                $cellclass = "footer" . $style;

				if (isset($totals_sum[$prop]))
				{
					$formatted_val = smarty_function_datagrid_format($totals_sum[$prop],$formula);
					$return_val .= "<td class='$cellclass'>" . $formatted_val . "</td>\n";
				}
				else
				{
					$return_val .= "<td class='footer'>&nbsp;</td>\n";
				}
			}
			
        if ($delete_url) {$return_val .= "<td class='footer'>&nbsp;</td>\n";}

		}
		
		
        
        $return_val .= "</table>\n";
        
        $return_val .= smarty_function_datagrid_get_pagenav($page, $page_url);
    }
    else
    {
        // there are no results
        $return_val .= "<p class='dataInformation'>$no_records</p>";
    }
    
    return $return_val;
}

/* returns a value formated with a provided formula
 */
function smarty_function_datagrid_format($orig_val,$formula)
{
	if ($formula == "")
	{
		$formatted_val = $orig_val;
	}
	elseif ($formula == "date(m/d/Y)")
	{
		$formatted_val = date("m/d/Y", strtotime($orig_val));
	}
	elseif ($formula == "yes/no")
	{
		$formatted_val = $orig_val ? "Yes" : "No";
	}
	elseif ($formula == "$")
	{
		$formatted_val = "$ " . number_format($orig_val,2);
	}
	elseif ($formula == "mailto")
	{
		$formatted_val = "<a href='mailto:$orig_val'>$orig_val</a>";
	}
	else
	{
		$formatted_val = sprintf($formula, $orig_val);
	}
	
	return $formatted_val;
}
?>
