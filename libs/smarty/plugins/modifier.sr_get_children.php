<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty sr_get_children modifier plugin
 *
 * Type:     modifier<br>
 * Name:     sr_get_children<br>
 * Purpose:  returns an array of Page objects
 * @param Page $page
 * @param string $orderby
 * @param int $pagenum
 * @param int $pagesize
 * @return array of Page objects
 * @example {$page|sr_get_children:name[:orderby][:pagenum][:pagesize]}
 */
function smarty_modifier_sr_get_children($page, $orderby = "", $pagenum = 1, $pagesize = 0)
{
	
	$criteria = new PageCriteria();
	
	if ($orderby)
	{
		$criteria->SetOrder($orderby);
	}
	
    $ds = $page->GetChildren($criteria);
	
	$dp = $ds->GetDataPage($pagenum, $pagesize);
	
    return $dp->Rows;
}

/* vim: set expandtab: */

?>
