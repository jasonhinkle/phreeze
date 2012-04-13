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
	 * 
	 */
	public function GetConfigFileContents()
	{
		if (!$this->configFileContents)
		{
			$this->configFileContents = file_get_contents($this->configFilePath);
		}
		
		return $this->configFileContents;
	}
	
	/**
	 * 
	 * @param unknown_type $name
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
	 * 
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
	 * 
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
	 * 
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
	 * 
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