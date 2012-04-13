<?php
/** @package    verysimple::DB::Reflection */

/**
 * DBcolumn is an object representation of column
 * @package    verysimple::DB::Reflection
 * @author Jason Hinkle
 * @copyright  1997-2007 VerySimple, Inc.
 * @license    http://www.gnu.org/licenses/lgpl.html  LGPL
 * @version 1.0
 */
 class DBColumn
{
	public $Table;
	public $Name;
	public $Type;
	public $Unsigned;
	public $Size;
	public $Key;
	public $Null;
	public $Default;
	public $Extra;
	public $Comment;
	public $NameWithoutPrefix; // populated by DBTable if there is a prefix
	public $MaxSize;
	public $Keys = array();
	public $Constraints = array();
	
	/**
	 * Instantiate new DBColumn
	 *
	 * @access public
	 * @param DBTable $table
	 * @param Array $row result from "describe table" statement
	 */	
	function __construct($table, $row)
	{
		// typical type is something like varchar(40)
		$typesize = explode("(",$row["Type"]);
		
		$tmp = isset($typesize[1]) ? str_replace(")","", $typesize[1]) : "" ;
		$sizesign = explode(" ", $tmp);
		
		$this->Table =& $table;
		$this->Name = $row["Field"];
		$this->NameWithoutPrefix = $row["Field"];
		$this->Type = $typesize[0];
		$this->Unsigned = isset($sizesign[1]);
		$this->Size = $sizesign[0] ;
		$this->Null = $row["Null"];
		$this->Key = $row["Key"];
		$this->Default = $row["Default"];
		$this->Extra = $row["Extra"];
		
		// if ($this->Key == "MUL") print " ########################## " . print_r($row,1) . " ########################## ";
		
		// size may be saved for decimals as "n,n" so we need to convert that to an int
		$tmp = explode(",",$this->Size);
		$this->MaxSize = count($tmp) > 1 ? ($tmp[0] + $tmp[1]) : $this->Size;
	}
	
	function GetPhpType()
	{
		$rt = $this->Type;
		switch ($this->Type)
		{
			case "smallint":
			case "bigint":
			case "tinyint":
			case "mediumint":
				$rt = "int";
				break;
			case "varchar":
			case "text":
			case "tinytext":
				$rt = "string";
				break;
			case "date":
			case "datetime":
				$rt = "date";
				break;
			case "decimal":
			case "float":
				$rt = "float";
				break;
			default;
				break;
		}
		
		return $rt;
	}
	
	function GetSqliteType()
	{
		$rt = $this->Type;
		switch ($this->Type)
		{
			case "int":
				$rt = "integer";
				break;
			case "smallint":
				$rt = "integer";
				break;
			case "tinyint":
				$rt = "integer";
				break;
			case "varchar":
				$rt = "text";
				break;
			case "text":
				$rt = "text";
				break;
			case "tinytext":
				$rt = "text";
				break;
			case "date":
				$rt = "datetime";
				break;
			case "datetime":
				$rt = "datetime";
				break;
			case "mediumint":
				$rt = "integer";
				break;
			case "bigint":
				$rt = "integer";
				break;
			case "decimal":
				$rt = "real";
				break;
			case "float":
				$rt = "real";
				break;
			default;
				break;
		}
		
		return $rt;
	}
	
	function GetFlexType()
	{
		$rt = $this->Type;
		switch ($this->Type)
		{
			case "int":
				$rt = "int";
				break;
			case "smallint":
				$rt = "int";
				break;
			case "tinyint":
				$rt = $this->MaxSize > 1 ? "int" : "Boolean";
				break;
			case "varchar":
				$rt = "String";
				break;
			case "text":
				$rt = "String";
				break;
			case "tinytext":
				$rt = "String";
				break;
			case "datetime":
				$rt = "Date";
				break;
			case "mediumint":
				$rt = "int";
				break;
			case "bigint":
				$rt = "int";
				break;
			case "decimal":
				$rt = "Number";
				break;
			case "float":
				$rt = "Number";
				break;
			default;
				break;
		}
		
		return $rt;
	}

}

?>