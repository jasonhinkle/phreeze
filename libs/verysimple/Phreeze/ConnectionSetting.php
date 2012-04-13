<?php
/** @package    verysimple::Phreeze */

/**
 * ConnectionSetting object contains information about the data store used for object persistance.
 *
 * @package    verysimple::Phreeze 
 * @author     VerySimple Inc.
 * @copyright  1997-2007 VerySimple, Inc.
 * @license    http://www.gnu.org/licenses/lgpl.html  LGPL
 * @version    2.0
 */
class ConnectionSetting
{

    var $Type = "mysql";
    var $ConnectionString;
    var $DBName;
    var $Username;
    var $Password;
    var $TablePrefix;

     /**
     * Constructor
     *
     */
    function __construct($connection_code = "")
    {
        if ($connection_code != "")
        {
            $this->Unserialize($connection_code);
        }
    }
    
     /**
     * Returns an DSN array compatible with PEAR::DB
     *
     */
    function GetDSN()
    {
	    return array(
		    'phptype'  => $this->Type,
		    'username' => $this->Username,
		    'password' => $this->Password,
		    'hostspec' => $this->ConnectionString,
		    'database' => $this->DBName,
	    );
	}
    
    /**
     * Returns an options array compatible with PEAR::DB
     *
     */
    function GetOptions()
    {
        return array(
	        'debug'          => 2,
	        // 'portability' => DB_PORTABILITY_NONE,
        );
    }
    
    /**
     * Serialize to string
     *
     */
    function Serialize()
    {
        return base64_encode(serialize($this));
    }

    /**
     * Populate info from serialized string
     *
     */
    function Unserialize(&$serialized)
    {
        // load the util from the serialized code
        $tmp = unserialize(base64_decode($serialized));
        $this->Type = $tmp->Type;
        $this->Username = $tmp->Username;
        $this->Password = $tmp->Password;
        $this->ConnectionString = $tmp->ConnectionString;
        $this->DBName = $tmp->DBName;
        $this->Type = $tmp->Type;
        $this->TablePrefix = $tmp->TablePrefix;
    }
    
}


?>