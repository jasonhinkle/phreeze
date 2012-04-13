<?php
/** @package    {$connection->DBName|studlycaps}::Model::DAO */

/** import supporting libraries */
require_once("verysimple/Phreeze/IDaoMap.php");

/**
 * {$singular}Map is a static class with functions used to get FieldMap and KeyMap information that
 * is used by Phreeze to map the {$singular}DAO to the {$table->Name} datastore.
 *
 * WARNING: THIS IS AN AUTO-GENERATED FILE
 *
 * This file should generally not be edited by hand except in special circumstances.
 * You can override the default fetching strategies for KeyMaps in _config.php.
 * Leaving this file alone will allow easy re-generation of all DAOs in the event of schema changes
 *
 * @package {$connection->DBName|studlycaps}::Model::DAO
 * @author ClassBuilder
 * @version 1.0
 */
class {$singular}Map implements IDaoMap
{ldelim}
	/**
	 * Returns a singleton array of FieldMaps for the {$singular} object
	 *
	 * @access public
	 * @return array of FieldMaps
	 */
	public static function GetFieldMaps()
	{ldelim}
		static $fm = null;
		if ($fm == null)
		{ldelim}
			$fm = Array();
{foreach from=$table->Columns item=column}			$fm["{$column->NameWithoutPrefix|studlycaps}"] = new FieldMap("{$column->NameWithoutPrefix|studlycaps}","{$table->Name}","{$column->Name}",{if $column->Key == "PRI"}true{else}false{/if},FM_TYPE_{$column->Type|upper},{if $column->Size}{$column->Size|replace:',':'.'}{else}null{/if},{if $column->Default}"{$column->Default}"{else}null{/if},{if $column->Extra == 'auto_increment'}true{else}false{/if});
{/foreach}
		{rdelim}
		return $fm;
	{rdelim}

	/**
	 * Returns a singleton array of KeyMaps for the {$singular} object
	 *
	 * @access public
	 * @return array of KeyMaps
	 */
	public static function GetKeyMaps()
	{ldelim}
		static $km = null;
		if ($km == null)
		{ldelim}
			$km = Array();
{foreach from=$table->Sets item=set}			$km["{$set->Name}"] = new KeyMap("{$set->Name}", "{$set->KeyColumnNoPrefix|studlycaps}", "{$set->SetTableName|studlycaps}", "{$set->SetKeyColumnNoPrefix|studlycaps}", KM_TYPE_ONETOMANY, KM_LOAD_LAZY);  // use KM_LOAD_EAGER with caution here (one-to-one relationships only)
{/foreach}
{foreach from=$table->Constraints item=constraint}			$km["{$constraint->Name}"] = new KeyMap("{$constraint->Name}", "{$constraint->KeyColumnNoPrefix|studlycaps}", "{$constraint->ReferenceTableName|studlycaps}", "{$constraint->ReferenceKeyColumnNoPrefix|studlycaps}", KM_TYPE_MANYTOONE, KM_LOAD_LAZY); // you change to KM_LOAD_EAGER here or (preferrably) make the change in _config.php
{/foreach}
		{rdelim}
		return $km;
	{rdelim}

{rdelim}

?>