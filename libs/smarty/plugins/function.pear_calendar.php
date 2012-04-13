<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty {pear_calendar} function plugin
 *
 * Type:     function<br>
 * Name:     ternary<br>
 * Input:<br>
 *           - calendar	(required) - EventMonth object (extended from Calendar_Decorator_Textual)
 *           - hide_header	(optional) - set to 1 to hide the header w/ month name
 *           - hide_footer	(optional) - set to 1 to hide the header w/ month navigation
 *           - hide_daynames	(optional) - set to 1 to hide the table header with day names
 *           - hide_events	(optional) - set to 1 to hide the events
 *           - dayname_format	(optional) one, two, short or long
 *           - page_url	(optional) url used for prev/next links.  must end with either ? or &amp;
 * Purpose:  to 
 * @param array
 * @param Smarty
 * @return string
 */
function smarty_function_pear_calendar($params, &$smarty)
{
	if (!isset($params["month"]))
	{
		$smarty->trigger_error("pear_calendar: 'month' parameter is required");
		exit;
	}
	
	// get all the parameters
	$month = $params["month"];
	$hide_header = isset($params["hide_header"]);
    $hide_footer = isset($params["hide_footer"]);
    $hide_daynames = isset($params["hide_daynames"]);
    $hide_events = isset($params["hide_events"]);
    $dayname_format = isset($params["dayname_format"]) ? $params["dayname_format"] : "long";
	$page_url = isset($params["page_url"]) ? $params["page_url"] : "";
	
	// begin calendar generation
	
	if (!$hide_header)
	{
		echo "<div class=\"calendar_header\"><div>" . $month->thisMonthName() 
		. " ". $month->thisYear() ."</div></div>\n";
	}	

	echo "<table class=\"calendar\">\n";
	$counter = 0;


	// print the header
	if (!$hide_daynames)
	{
		echo "  <tr>\n";
		foreach ($month->weekdayNames($dayname_format) as $name)
		{
			echo "    <th>$name</th>\n";
		}
		echo "  </tr>\n";
	}
	
	//$cm = 0;
	//$cy = 0;
	
	while ($day = $month->fetch()) 
	{
		$counter++;
		
		if ($day->isFirst()) {
			echo "  <tr>\n";
		}

		$is_today = date("Y-m-d") == date("Y-m-d", $day->getTimestamp());
		$has_events = property_exists($day,events) && is_array($day->events);

		$html_class = ($day->isEmpty() ? " empty" : "") 
			. ($is_today ? " today" : "")
			. ($has_events ? " has_events" : "");

		echo "    <td" . ($html_class ? " class=\"" .  trim($html_class) . "\"" : "") . ">\n";

		echo "      <div class=\"date_number".$html_class."\">".$day->thisDay()."</div>\n";
		echo "      <div class=\"date_events".$html_class."\">";
		
		if ($has_events && (!$hide_events))
		{
			foreach ($day->events as $event)
			{
				echo $event->toHTML();
			}
		}
		else
		{
			echo "&nbsp;";
		}
		echo "</div>\n";
		
		echo "    </td>\n";

		if ($day->isLast()) {
			echo "  </tr>\n";
		}
	}

	if (!$counter)
	{
		echo "<tr class=\"calendar_row\"><td class=\"calendar_cell\">pear_calendar: No days were fetched.  Did you remember to call \$month->build() ?</td></tr>";
	}

	echo "</table>\n";

	if (!$hide_footer)
	{
		$full_url = smarty_function_pear_calendar_full_url($page_url);
		$pm = $month->prevMonth();
		$nm = $month->nextMonth();
		$py = ($pm == 12) ? $month->prevYear() : $month->thisYear();
		$ny =  ($nm == 1) ? $month->nextYear() : $month->thisYear();
		echo "<div class=\"calendar_footer\"><div>"
			. "<a class=\"calendar_nav\" href=\"".$full_url."year=$py&month=$pm\">Previous Month</a>" 
			. " <a class=\"calendar_nav\" href=\"".$full_url."year=&month=\">Current Month</a>"
			. " <a class=\"calendar_nav\" href=\"".$full_url."year=$ny&month=$nm\">Next Month</a>"
			. "</div></div>\n";
	}	
}

function smarty_function_pear_calendar_full_url($page_url)
{
	if (!$page_url)
	{
		$page_url = $_SERVER["PHP_SELF"];
		$delim = "?";
		$no_pass = array("PHPSESSID","WT_FPC","year","month","SimpleSecure");
		
		// reconstruct the querystring
		foreach ($_REQUEST as $key => $val)
		{
			if ( !in_array($key,$no_pass,false) )
			{
				$page_url .= $delim . urlencode($key) . "=" . urlencode($val);
				$delim = "&amp;";
			}
		}
		
		// make sure we can append
		$page_url .= $delim;
	}
	return $page_url;
}

?>
