<?php
/** @package    verysimple::Phreeze */

/** import supporting libraries */
require_once("IObservable.php");
require_once("ConnectionSetting.php");
require_once("DataPage.php");
require_once("DataSet.php");
require_once("QueryBuilder.php");
require_once("verysimple/DB/DataDriver/IDataDriver.php");

/**
 * DataAdapter abstracts and provides access to the data store
 *
 * @package    verysimple::Phreeze
 * @author     VerySimple Inc. <noreply@verysimple.com>
 * @copyright  1997-2005 VerySimple Inc.
 * @license    http://www.gnu.org/licenses/lgpl.html  LGPL
 * @version    2.0
 */
class DataAdapter implements IObservable
{
    
    private $_observers = Array();
    private $_csetting;
	private $_dbconn;
	private $_dbopen;
	private $_driver;
	
	/** @var instance of the driver class, used for escaping */
	static $DRIVER_INSTANCE = null;
	
    /**
    * Contructor initializes the object
    *
    * @access public
    * @param ConnectionSetting $csetting
    * @param Observable $listener
    * @param IDataDriver (optional) if not provided, then DataAdapter will attempt to instantiate one based on ConnectionSetting->Type
    */
    function __construct($csetting, $listener = null, IDataDriver $driver = null)
    {
    	$this->_driver = $driver;
    	
    	
    	if ($this->_driver == null) 
    	{
    		// the driver was not explicitly provided so we will try to create one from 
    		// the connection setting based on the database types that we do know about
    		switch($csetting->Type)
    		{
    			case "mysql":
					include_once("verysimple/DB/DataDriver/MySQL.php");
    				$this->_driver  = new DataDriverMySQL();
    				break;
    			case "sqlite":
					include_once("verysimple/DB/DataDriver/SQLite.php");
    				$this->_driver  = new DataDriverSQLite();
    				break;
    			default:
					include_once("verysimple/DB/DataDriver/".$csetting->Type.".php");
					$classname = "DataDriver" . $csetting->Type;
    				$this->_driver  = new $classname();
    				break;
    		}
    	}
    	
    	DataAdapter::$DRIVER_INSTANCE = $this->_driver;
    	
		$this->AttachObserver($listener);
		$this->_csetting =& $csetting;
		$this->Observe("DataAdapter Instantiated", OBSERVE_DEBUG);
	}
	
	
	/**
     * Destructor closes the db connection.
     *
     * @access     public
     */    
	function __destruct()
	{
		$this->Observe("DataAdapter Destructor Firing...",OBSERVE_DEBUG);
		$this->Close();
	}
	
    /**
	 * Returns name of the DB currently in use
	 *
	 * @access public
	 * @return string
	 */	
	function GetDBName()
	{
		return $this->_csetting->DBName;
	}
	
    /**
	 * Opens a connection to the data server and selects the specified database
	 *
	 * @access public
	 */	
	function Open()
	{
		$this->Observe("Opening Connection...",OBSERVE_DEBUG);
		
		if ($this->_dbopen)
		{
			$this->Observe("Connection Already Open",OBSERVE_WARN);
		}
		else
		{
			try
			{
				$this->_dbconn = $this->_driver->Open(
					$this->_csetting->ConnectionString, 
					$this->_csetting->DBName, 
					$this->_csetting->Username, 
					$this->_csetting->Password);
			}
			catch (Exception $ex)
			{
				$this->Observe("Error connecting to database: " . $ex->getMessage(),OBSERVE_FATAL);
				throw new Exception("Error connecting to database: " . $ex->getMessage());
			}
			
			$this->_dbopen = true;
			$this->Observe("Connection Open",OBSERVE_DEBUG);
		}
	}
	
	/**
	 * Closing the connection to the data Server
	 *
	 * @access public
	 */	
	function Close()
	{
		$this->Observe("Closing Connection...",OBSERVE_DEBUG);
		
		if ($this->_dbopen)
		{
			$this->_driver->Close($this->_dbconn); // ignore warnings
			$this->_dbopen = false;
			$this->Observe("Connection Closed",OBSERVE_DEBUG);
		}
		else
		{
			$this->Observe("Connection Not Open",OBSERVE_DEBUG);
		}
	}
    
    /**
	 * Checks that the connection is open and if not, crashes
	 *
	 * @access public
	 * @param bool $auto Automatically try to connect if connection isn't already open
	 */	
	private function RequireConnection($auto = false)
	{
		if ($this->_dbopen)
		{
			// $this->_driver->Ping($this->_dbconn);
		}
		else
		{
			if ($auto)
			{
				$this->Open();
			}
			else
			{
				$this->Observe("DB is not connected.  Please call DBConnection->Open() first.",OBSERVE_FATAL);
				throw new Exception("DB is not connected.  Please call DBConnection->Open() first.");
			}
		}
	}
	
	/**
	 * Executes a SQL select statement and returns a resultset that can be read
	 * using Fetch
	 *
	 * @access public
	 * @param string $sql
	 * @return resultset (dependent on the type of driver used)
	 */	
	function Select($sql)
	{
		$this->RequireConnection(true);
		$this->Observe("(DataAdapter.Select) " . $sql, OBSERVE_QUERY);
		
		try
		{
			$rs = $this->_driver->Query($this->_dbconn,$sql);
		}
		catch (Exception $ex)
		{
			$this->Observe("Error executing SQL: " . $ex->getMessage(),OBSERVE_FATAL);
			throw new Exception('Error executing SQL: ' . $ex->getMessage());
		}
		
		return $rs;
	}
	
	/**
	 * Executes a SQL query that does not return a resultset
	 *
	 * @access public
	 * @param string $sql
	 * @return int number of records affected
	 */	
	function Execute($sql)
	{
		$this->RequireConnection(true);
		$this->Observe("(DataAdapter.Execute) " . $sql, OBSERVE_QUERY);
		$result = -1;
		
		try
		{
			$result = $this->_driver->Execute($this->_dbconn, $sql);
		}
		catch (Exception $ex)
		{
			$this->Observe("Error executing SQL: " . $ex->getMessage(),OBSERVE_FATAL);
			throw new Exception('Error executing SQL: ' . $ex->getMessage());
		}
		
		return $result;
	}
	
	
	/**
	 * Returns an array of all table names in the current database
	 * @param bool true to ommit tables that are empty (default = false)
	 * @return array
	 */
	public function GetTableNames($ommitEmptyTables = false)
	{
		return $this->_driver->GetTableName($this->_dbconn,$this->GetDBName(),$ommitEmptyTables);
	}
	
	/**
	 * Runs OPTIMIZE TABLE on all tables in the current database
	 * @return array results for each table
	 */
	public function OptimizeTables()
	{
		$results = array();
		$table_names = $this->_driver->GetTableNames($this->_dbconn,$this->GetDBName());
		
		foreach ($table_names as $table_name)
		{
			$results[$table_name] = $this->_driver->Optimize($this->_dbconn,$table_name);
		}
		
		return $results;
		
	}
	/**
	 * Returns last auto-inserted Id
	 *
	 * @access public
	 * @return int
	 */	
	function GetLastInsertId()
	{
		$this->RequireConnection();
		$this->Observe("GetLastInsertId", OBSERVE_QUERY);
		return $this->_driver->GetLastInsertId($this->_dbconn);
	}
	
	/**
	 * Moves the database curser forward and returns the current row as an associative array
	 * the resultset passed in must have been created by the same database driver that
	 * was connected when Select was called
	 *
	 * @access public
	 * @param resultset $rs
	 * @return Array
	 */	
	function Fetch($rs)
	{
		$this->RequireConnection();

		$this->Observe("Fetching next result as array",OBSERVE_DEBUG);
		return $this->_driver->Fetch($this->_dbconn,$rs);
	}
	
	/**
	 * Releases the resources for the given resultset.  the resultset must have 
	 * been created by the same database driver
	 *
	 * @access public
	 * @param resultset $rs
	 */	
	function Release($rs)
	{
		$this->RequireConnection();

		$this->Observe("Releasing result resources",OBSERVE_DEBUG);
		$this->_driver->Release($this->_dbconn,$rs);
	}
	
	/**
	 * Removes any illegal chars from a value to prepare it for use in SQL
	 *
	 * @access public
	 * @param string $val
	 * @return string
	 */	
    public static function Escape($val)
    {
		// this is an unfortunate leftover from poor design of making this function static
		// we cannon use the driver's escape method without a static reference
		if (!DataAdapter::$DRIVER_INSTANCE) throw new Exception("DataAdapter must be instantiated before Escape can be called");

		// if magic quotes are enabled, then we need to stip the slashes that php added
		if (get_magic_quotes_runtime() || get_magic_quotes_gpc()) $val = stripslashes($val);
		
		// $driver->RequireConnection(true);
		return DataAdapter::$DRIVER_INSTANCE->Escape($val);
	}
	
	
    /**
    * Registers/attaches an IObserver to this object
    *
    * @access public
	* @param IObserver $observer
    */
	public function AttachObserver($listener)
	{
		if ($listener) $this->_observers[] =& $listener;
	}
	
    /**
    * Fires the Observe event on all registered observers
    *
    * @access public
    * @param variant $obj the $obj or message that you want to log/listen to, etc.
    * @param int $ltype the type/level
    */
	public function Observe($obj, $ltype = OBSERVE_INFO)
	{
		foreach ($this->_observers as $observer) @$observer->Observe($obj, $ltype);
	}
}
?>