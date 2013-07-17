<?php
/** @package    {$connection->DBName|studlycaps}::Reporter */

/** import supporting libraries */
require_once("verysimple/Phreeze/Reporter.php");

/**
 * This is an example Reporter based on the {$singular} object.  The reporter object
 * allows you to run arbitrary queries that return data which may or may not fith within
 * the data access API.  This can include aggregate data or subsets of data.
 *
 * Note that Reporters are read-only and cannot be used for saving data.
 *
 * @package {$connection->DBName|studlycaps}::Model::DAO
 * @author ClassBuilder
 * @version 1.0
 */
class {$singular}Reporter extends Reporter
{ldelim}

	// the properties in this class must match the columns returned by GetCustomQuery().
	// 'CustomFieldExample' is an example that is not part of the `{$table->Name}` table
	public $CustomFieldExample;

{foreach from=$table->Columns item=column}
	public ${$column->NameWithoutPrefix|studlycaps};
{/foreach}

	/*
	* GetCustomQuery returns a fully formed SQL statement.  The result columns
	* must match with the properties of this reporter object.
	*
	* @see Reporter::GetCustomQuery
	* @param Criteria $criteria
	* @return string SQL statement
	*/
	static function GetCustomQuery($criteria)
	{ldelim}
		$sql = "select
			'custom value here...' as CustomFieldExample
{foreach from=$table->Columns item=column name="colsForEach"}
			,`{$table->Name}`.`{$column->Name}` as {$column->NameWithoutPrefix|studlycaps}
{/foreach}
		from `{$table->Name}`";

		// the criteria can be used or you can write your own custom logic.
		// be sure to escape any user input with $criteria->Escape()
		$sql .= $criteria->GetWhere();
		$sql .= $criteria->GetOrder();

		return $sql;
	{rdelim}
	
	/*
	* GetCustomCountQuery returns a fully formed SQL statement that will count
	* the results.  This query must return the correct number of results that
	* GetCustomQuery would, given the same criteria
	*
	* @see Reporter::GetCustomCountQuery
	* @param Criteria $criteria
	* @return string SQL statement
	*/
	static function GetCustomCountQuery($criteria)
	{ldelim}
		$sql = "select count(1) as counter from `{$table->Name}`";

		// the criteria can be used or you can write your own custom logic.
		// be sure to escape any user input with $criteria->Escape()
		$sql .= $criteria->GetWhere();

		return $sql;
	{rdelim}
{rdelim}

?>