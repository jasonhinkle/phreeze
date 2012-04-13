<?php
/** @package    verysimple::DB::Reflection */

/** import supporting libraries */
require_once("DBEventHandler.php");
require_once("DBConnectionString.php");
require_once("PEAR.php");

define("DBC_ERROR_NOT_CONNECTED",1);
define("DBC_ERROR_IN_QUERY",2);

/**
 * DBConnection provides connectivity to a MySQL Server
 *
 * @package    verysimple::DB::Reflection
 * @author Jason Hinkle
 * @copyright  1997-2007 VerySimple, Inc.
 * @license    http://www.gnu.org/licenses/lgpl.html  LGPL
 * @version 1.0
 */
class DBConnection extends PEAR  // extend PEAR to use the destructor functionality
{
	public $Host;
	public $Port;
	public $Username;
	public $Password;
	public $DBName;

	private $dbconn;
	private $handler;
	private $dbopen;

	/**
	 * Instantiate new DBConnection
	 *
	 * @access public
	 * @param DBConnectionString $host
	 * @param DBEventHandler $port
	 */	
	function __construct($dbconnstring, $handler = null)
	{
        $this->PEAR();  // call the base class
        
        $this->dbopen = false;
		$this->Host = $dbconnstring->Host;
		$this->Port = $dbconnstring->Port;
		$this->Username = $dbconnstring->Username;
		$this->Password = $dbconnstring->Password;
		$this->DBName = $dbconnstring->DBName;
		
		if ($handler)
		{
			$this->handler =& $handler;
		}
		else
		{
			$this->handler = new DBEventHandler();
		}
		
		$this->handler->Log(DBH_LOG_INFO, "Connection Initialized");
	}

    /**
     * Destructor closes the db connection.
     *
     * @access     public
     */    
    function _DBConnection()
    {
        $this->Disconnect();
    }
    
    /**
	 * Opens a connection to the MySQL Server and selects the specified database
	 *
	 * @access public
	 * @param string $dbname
	 */	
	function Connect()
	{
		$this->handler->Log(DBH_LOG_INFO, "Opening Connection...");
		if ($this->dbopen)
		{
			$this->handler->Log(DBH_LOG_WARNING, "Connection Already Open");
		}
		else
		{
			if ( !$this->dbconn = mysql_connect($this->Host . ":" . $this->Port, $this->Username, $this->Password) )
			{
				$this->handler->Crash("Error connecting to database: " . mysql_error());
			}
			
			if (!mysql_select_db($this->DBName, $this->dbconn))
			{
				$this->handler->Crash("Unable to select database " . $this->DBName);
			}
			
			$this->handler->Log(DBH_LOG_INFO, "Connection Open");
			$this->dbopen = true;
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
		if (!$this->dbopen)
		{
			if ($auto)
			{
				$this->Connect();
			}
			else
			{
				$this->handler->Crash(DBC_ERROR_NOT_CONNECTED, "DB is not connected.  Please call DBConnection->Connect() first.");
			}
		}
	}

	/**
	 * Closing the connection to the MySQL Server
	 *
	 * @access public
	 */	
	function Disconnect()
	{
		$this->handler->Log(DBH_LOG_INFO, "Closing Connection...");
		
		if ($this->dbopen)
		{
			mysql_close($this->dbconn);
			$this->dbopen = false;
			$this->handler->Log(DBH_LOG_INFO, "Connection closed");
		}
		else
		{
			$this->handler->Log(DBH_LOG_WARNING, "Connection Already Closed");
		}
	}

	/**
	 * Executes a SQL select statement and returns a MySQL resultset
	 *
	 * @access public
	 * @param string $sql
	 * @return mysql_query
	 */	
	function Select($sql)
	{
		$this->RequireConnection(true);
		
		$this->handler->Log(DBH_LOG_QUERY, "Executing Query", $sql);
		
		if ( !$rs = mysql_query($sql, $this->dbconn) )
		{
		   $this->handler->Crash(0, 'Error executing SQL: ' . mysql_error());
		}
		
		return $rs;
	}

	/**
	 * Executes a SQL query that does not return a resultset
	 *
	 * @access public
	 * @param string $sql
	 */	
	function Update($sql)
	{
		$this->RequireConnection(true);

		if ( !$result = mysql_query($sql, $this->dbconn) )
		{
		   $this->handler->Crash('Error executing SQL: ' . mysql_error());
		}
	}
	
	/**
	 * Moves the database curser forward and returns the current row as an associative array
	 *
	 * @access public
	 * @param mysql_query $rs
	 * @return Array
	 */	
	function Next($rs)
	{
		$this->RequireConnection();

		$this->handler->Log(DBH_LOG_DEBUG, "Fetching next result as array");
		return mysql_fetch_assoc($rs);
	}
	
	/**
	 * Releases the resources for the given resultset
	 *
	 * @access public
	 * @param mysql_query $rs
	 */	
	function Release($rs)
	{
		$this->RequireConnection();

		$this->handler->Log(DBH_LOG_DEBUG, "Releasing result resources");
		mysql_free_result($rs);
	}
	
}

?>