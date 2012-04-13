<?php
/*
* Smarty plugin
* -------------------------------------------------------------
* File:     function.weather.php
* Type:     function
* Name:     weather
* Purpose:  displays weather for a specific area
* Example:	{weather licensekey="d59d83c745b5d20d" partnerkey="1014788205" location="USIL0225"}
* -------------------------------------------------------------
*/
function smarty_function_weather($params, &$smarty)
{

    if(!isset($params['zipcode']) || !preg_match("/\d{5}/",$params['zipcode'])) {
		$smarty->trigger_error("Invalid or missing zipcode: Please supply a valid 5-digit zipcode!");
		return;	
	}
	
    $zipcode = $params['zipcode'];
    
    $url = "http://xml.weather.yahoo.com/forecastrss?p=${zipcode}&u=f";
    
	$output = "<div id='weather'>";
	
	$xml = "";
	
	try 
	{
	    $weather = file_get_contents($url);
	}
	catch(exception $ex)
	{
	}
	
	if ($weather) {
		
		preg_match_all("/<yweather\:(.*) (.*) \/>/imU",$weather,$matches,PREG_SET_ORDER);
		
		$weather = array();
		
		// lets make a weather array!
		foreach($matches as $key=>$value) {
			
			$arr = array();
			$values = preg_split("/\"\s/",$value[2]);
			
			foreach($values as $val) {
				list($id,$result) = split("=",$val);
				$arr[$id] = str_replace('"','',$result);
			}
			
			$weather[$value[1]] = $arr;
			
		}
		
		/**
		
			ok.. heres the deal:
			this little script simulates an XSLT processor, which does NOT come standard
			with php's installation configuration..
			
			we're regexing out the <yweather:xxx xxx=yyy zzz=aaa /> attribute nodes
			and then turning them into arrays
			
			heres what you have to work with:
				
				Array
				(
					[0] => location
					[1] => units
					[2] => wind
					[3] => atmosphere
					[4] => astronomy
					[5] => condition
					[6] => forecast
				)
			
			take a peek at the rss result that yahoo gives with array breakdowns
			
			cool!
			
		**/
		
		$weather_image_url = 'http://us.i1.yimg.com/us.yimg.com/i/us/we/52/' . $weather['condition']['code'] . '.gif';
		
		$output .= "<div id='weatherImage'><img id='weatherIcon' src=\"" . $weather_image_url . "\" /></div>\n";
		$output .= "<div id='weatherTitle'>" . $weather['location']['city'] . ", " . $weather['location']['region'] .  "</div>";
		$output .= "<div id='weatherDegrees'>Temp. " . $weather['condition']['temp'] . "&deg;</div>\n";
		$output .= "<div id='weatherCondition'>" . $weather['condition']['text'] . "</div>\n";
		$output .= "<div id='weatherCopyright'>Provided by Yahoo! Weather</div>\n";
		
	} else {
		$output .= "<div id='weatherTitle'>Current Weather Unavailable</div>";
	}

	$output .= "</div>\n";
	
	return $output;
}
?> 