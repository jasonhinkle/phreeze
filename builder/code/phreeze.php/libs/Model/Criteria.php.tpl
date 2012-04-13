<?php
/** @package    {$connection->DBName|studlycaps}::Model */

/** import supporting libraries */
require_once("DAO/{$singular}CriteriaDAO.php");

/**
 * The {$singular}Criteria class extends {$singular}DAOCriteria and is used
 * to query the database for objects and collections
 * 
 * @inheritdocs
 * @package {$connection->DBName|studlycaps}::Model
 * @author ClassBuilder
 * @version 1.0
 */
class {$singular}Criteria extends {$singular}CriteriaDAO
{ldelim}
	
	/**
	 * For custom query logic, you may override OnProcess and set the $this->_where to whatever
	 * sql code is necessary.  If you choose to manually set _where then Phreeze will not touch
	 * your where clause at all and so any of the standard property names will be ignored
	 */
	/*
	function OnPrepare()
	{ldelim}
		if ($this->MyCustomField == "special value")
		{ldelim}
			// _where must begin with "where"
			$this->_where = "where db_field ....";
		{rdelim}
	{rdelim}
	*/

{rdelim}
?>