<?php
/** @package    {$connection->DBName|studlycaps}::Model::DAO */

/** import supporting libraries */
require_once("verysimple/Phreeze/Criteria.php");

/**
 * {$singular}Criteria allows custom querying for the {$singular} object.
 *
 * WARNING: THIS IS AN AUTO-GENERATED FILE
 *
 * This file should generally not be edited by hand except in special circumstances.
 * Add any custom business logic to the ModelCriteria class which is extended from this class.
 * Leaving this file alone will allow easy re-generation of all DAOs in the event of schema changes
 *
 * @inheritdocs
 * @package {$connection->DBName|studlycaps}::Model::DAO
 * @author ClassBuilder
 * @version 1.0
 */
class {$singular}CriteriaDAO extends Criteria
{ldelim}

{foreach from=$table->Columns item=column}	public ${$column->NameWithoutPrefix|studlycaps}_Equals;
	public ${$column->NameWithoutPrefix|studlycaps}_NotEquals;
	public ${$column->NameWithoutPrefix|studlycaps}_IsLike;
	public ${$column->NameWithoutPrefix|studlycaps}_IsNotLike;
	public ${$column->NameWithoutPrefix|studlycaps}_BeginsWith;
	public ${$column->NameWithoutPrefix|studlycaps}_EndsWith;
	public ${$column->NameWithoutPrefix|studlycaps}_GreaterThan;
	public ${$column->NameWithoutPrefix|studlycaps}_GreaterThanOrEqual;
	public ${$column->NameWithoutPrefix|studlycaps}_LessThan;
	public ${$column->NameWithoutPrefix|studlycaps}_LessThanOrEqual;
	public ${$column->NameWithoutPrefix|studlycaps}_In;
	public ${$column->NameWithoutPrefix|studlycaps}_IsNotEmpty;
	public ${$column->NameWithoutPrefix|studlycaps}_IsEmpty;
	public ${$column->NameWithoutPrefix|studlycaps}_BitwiseOr;
	public ${$column->NameWithoutPrefix|studlycaps}_BitwiseAnd;
{/foreach}

{rdelim}

?>