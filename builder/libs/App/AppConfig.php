<?php

require_once("AppParameter.php");
require_once("TemplateFile.php");

/**
 *
 * @package Phreeze::ClassBuilder
 * @author  j.hinkle
 */
class AppConfig
{
	private $configFilePath;
	private $configFileContents;
	private $files;
	private $name;
	private $description;

	/**
	 * Constructor.
	 * @param string path to config file
	 */
	function AppConfig($configFilePath)
	{
		$this->configFilePath = $configFilePath;
	}
	
	/**
	 * Return the text of the config file
	 */
	public function GetConfigFileContents()
	{
		if (!$this->configFileContents)
		{
			// get rid of windows-style carriage returns
			$this->configFileContents = str_replace("\r\n", "\n", file_get_contents($this->configFilePath) );
		}
		
		return $this->configFileContents;
	}
	
	/**
	 * Return a property from the config file with the given name
	 * @param string $name
	 */
	private function GetProperty($name)
	{
		$lines = explode("\n", $this->GetConfigFileContents());
		// print $this->GetConfigFileContents();
		
		foreach ($lines as $line)
		{
			$pair = explode("=",trim($line),2);
			if ($pair[0] == $name) return isset($pair[1]) ? $pair[1] : "";
		}
		
		throw new Exception("UNKNOWN PROPERTY $name");
	}

	/**
	 * Returns the path to the config file
	 */
	public function GetConfigFile()
	{
//		return $this->configFile;
//		
//		return strrpos($this->configFilePath,'/');
//		
		return substr($this->configFilePath,
			strrpos($this->configFilePath,'/')+1
			);
	}
	
	/**
	 * Returns the name of the selected app
	 */
	public function GetName()
	{
		if (!$this->name)
		{
			$this->name = $this->GetProperty("name");
		}
		
		return $this->name;
	}
	
	/**
	 * Returns the description of the selected app
	 */
	public function GetDescription()
	{
		if (!$this->description)
		{
			$this->description = $this->GetProperty("description");
		}
		
		return $this->description;
		
	}
	
	/**
	 * Returns an array of TemplateFile objects that will be used when generating
	 * an application
	 */
	public function GetTemplateFiles()
	{
		$files = array();
		
		list($nothing,$data) = explode("[files]", $this->GetConfigFileContents(),2);
		
		if ($data)
		{
			$lines = explode("\n",$data);
			
			foreach ($lines as $line)
			{
				if ($line) $files[] = new TemplateFile($line);
			}
		}
		
		return $files;
	}
	
	/**
	 * 
	 */
	public function GetAppProperties()
	{
	}
}

?>