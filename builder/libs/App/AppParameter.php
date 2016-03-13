<?php
/**
 * Holds all the vars that will be presented in the parameters section.
 *
 * @package Phreeze::ClassBuilder
 * @author  laplix
 * @since	2007-11-02
 */
class AppParameter
{
	public $name;
	public $value;

	/**
	* Constructor.
	* @param string $name	 Parameter name.
	* @param string $value	Parameter value.
	*/
	function __construct($name=null, $value=null)
	{
	  $this->name = $name;
	  $this->value = $value;
	}
}

?>
