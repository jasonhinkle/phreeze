<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty sr_get_page modifier plugin
 *
 * Type:     modifier<br>
 * Name:     sr_get_page<br>
 * Purpose:  returns a Page object with the given Id
 * @param Page $page
 * @param int $id
 * @return Page
 * @example {$page|sr_get_property:name[:default][:nullval]}
 */
function smarty_modifier_sr_get_page($page,$id,$print_error=true)
{

    $phreezer = $page->GetPhreezer();
	
	$newpage = null;
	
	try
	{
		$newpage = $phreezer->Get("Page",$id);
	}
	catch (NotFoundException $nfe)
	{
		if ($print_error)
		{
			print "<span style='background-color:red;color:#ffffff;padding:2px;border:solid 1px black;'>" . strtoupper($nfe->getMessage()) . "</span>";
		}
	}
	
    return $newpage;
}

/* vim: set expandtab: */

?>
