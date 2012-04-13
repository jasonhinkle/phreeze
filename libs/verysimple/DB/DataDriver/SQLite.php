<?php 
/** @package verysimple::DB::DataDriver */

require_once("IDataDriver.php");

/**
 * An implementation of IDataDriver that communicates with
 * a SQLite database file.  This is one of the native drivers
 * supported by Phreeze
 *
 * @package    verysimple::DB::DataDriver
 * @author     VerySimple Inc. <noreply@verysimple.com>
 * @copyright  1997-2010 VerySimple Inc.
 * @license    http://www.gnu.org/licenses/lgpl.html  LGPL
 * @version    1.0
 */
class DataDriverSQLite implements IDataDriver
{	
	/**
	 * @inheritdocs
	 */
	function GetServerType()
	{
		return "SQLite";
	}
	
	function Ping($connection)
	{
		 throw new Exception("Not Implemented");
	}
	
	/**
	 * @inheritdocs
	 */
	function Open($connectionstring,$database,$username,$password) 
	{
		if ( !$connection =  new SQLite3($connectionstring, SQLITE3_OPEN_READWRITE,$password) )
		{
			throw new Exception("Error connecting to database: Unable to open the database file.");
		}

		return $connection;
	}
	
	/**
	 * @inheritdocs
	 */
	function Close($connection) 
	{
		@$connection->close(); // ignore warnings
	}
	
	/**
	 * @inheritdocs
	 */
	function Query($connection,$sql) 
	{

		if ( !$rs = $connection->query($sql) )
		{
			throw new Exception($connection->lastErrorMsg());
		}
		
		return $rs;
	}

	/**
	 * @inheritdocs
	 */
	function Execute($connection,$sql) 
	{
		return $connection->exec($sql);
	}
	
	/**
	 * @inheritdocs
	 */
	function Fetch($connection,$rs) 
	{
		return $rs->fetchArray(SQLITE3_ASSOC);
	}

	/**
	 * @inheritdocs
	 */
	function GetLastInsertId($connection) 
	{
		return $connection->lastInsertRowID();
	}

	/**
	 * @inheritdocs
	 */
	function GetLastError($connection)
	{
		return $connection->lastErrorMsg();
	}
	
	/**
	 * @inheritdocs
	 */
	function Release($connection,$rs) 
	{
		$rs->finalize();
	}
	
	/**
	 * @inheritdocs
	 * @TODO: use SQLite
	 */
	function Escape($val) 
	{
		return str_replace("'","''",$val);
 	}
	
	/**
	 * @inheritdocs
	 */
 	function GetTableNames($connection, $dbname, $ommitEmptyTables = false) 
	{
		if ($ommitEmptyTables) throw new Exception("SQLite DataDriver doesn't support returning only non-empty tables.  Set ommitEmptyTables arg to false to use this method.");
		
		$rs = $this->Query($connection,"SELECT name FROM sqlite_master WHERE type='table' and name != 'sqlite_sequence' ORDER BY name");

		$tables = array();
		
		while ( $row = $this->Fetch($connection,$rs) )
		{
			$tables[] = $row['name'];
		}
		
		return $tables;
		
 	}
	
	/**
	 * @inheritdocs
	 */
 	function Optimize($connection,$table) 
	{
		if ($table) throw new Exception("SQLite optimization is database-wide.  Call Optimize() with a blank/null table arg to use this method.");
		$this->Execute($connection,"VACUUM");
	}
	
}

?>