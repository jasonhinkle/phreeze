<?php
/** @package    verysimple::Util */

/**
 * Static utility class for working with Dates
 *
 * @package		verysimple::Util
 * @author		Jason Hinkle
 * @copyright	1997-2011 VerySimple, Inc.
 * @license		LGPL http://www.gnu.org/licenses/lgpl.html
 * @version		1.0
 */
class VsDateUtil
{
	/**
	 * Return current date as string in the specified format
	 * @param string $format
	 */
	static function Today($format = "Y-m-d")
	{
		return self::Now($format);
	}
	
	/**
	 * Return current date/time as string in the specified format
	 * @param string $format
	 */
	static function Now($format = "Y-m-d H:i:s")
	{
		return date($format);
	}
	
	/**
	 * Return yesterday's date as string in the specified format
	 * @param string $format
	 */
	static function Yesterday($format = "Y-m-d")
	{
		return self::DaysAgo(1,$format);
	}

	/**
	 * Return tomorrow's date as string in the specified format
	 * @param string $format
	 */
	static function Tomorrow($format = "Y-m-d")
	{
		return self::DaysFromNow(1,$format);
	}
	
	/**
	 * Return the date/time 24 hours ago as string in the specified format
	 * @param string $format
	 */
	static function TwentyFourHoursAgo($format = "Y-m-d H:i:s")
	{
		return self::HoursAgo(24,$format);
	}

	/**
	 * Return the date/time 24 hours from now as string in the specified format
	 * @param string $format
	 */
	static function TwentyFourHoursFromNow($format = "Y-m-d H:i:s")
	{
		return self::HoursFromNow(24,$format);
	}
	
	/**
	 * Return date as a string the specified number of days ago
	 * @param int $days
	 * @param string $format
	 */
	static function DaysAgo($days, $format = "Y-m-d")
	{
		return date($format,strtotime(self::Now() . " - $days days"));
	}
	
	/**
	 * Return date as a string the specified number of days from now
	 * @param int $days
	 * @param string $format
	 */
	static function DaysFromNow($days, $format = "Y-m-d")
	{
		return date($format,strtotime(self::Now() . " + $days days"));
	}

	/**
	 * Return date/time as a string the specified number of hours ago
	 * @param int $hours
	 * @param string $format
	 */
	static function HoursAgo($hours, $format = "Y-m-d H:i:s")
	{
		return date($format,strtotime(self::Now() . " - $hours hours"));
	}
	
	/**
	 * Return date/time as a string the specified number of hours from now
	 * @param int $hours
	 * @param string $format
	 */
	static function HoursFromNow($hours, $format = "Y-m-d H:i:s")
	{
		return date($format,strtotime(self::Now() . " - $hours hours"));
	}
	
	
}

?>