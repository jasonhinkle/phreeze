<?php

/**
 *
 */
class Savant3_Plugin_studlycaps extends Savant3_Plugin {

	public function studlycaps($string)
	{
		return ucwords(preg_replace("/(\_(.))/e","strtoupper('\\2')",strtolower($string)));
	}
}

?>