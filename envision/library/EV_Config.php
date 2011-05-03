<?php

class EV_Config extends EV_Object {
	
	/**
	 * Stores the instance of the configuration
	 * @var unknown_type
	 */
	private static $_instance = null;
	
	/**
	 * The static array to contain the values.
	 * @var array
	 */
	private $_config = array();
	
	/**
	 * Load the configuration data. If $configPath is null, then the default app/config path will be used. 
	 * @param string $configPath
	 */
	public function loadConfiguration($configPath = null) {
		
		// If the $config path is null, set to default
		if ($configPath == null) {
			$configPath = APP_PATH.'config/';	
		}
		
		// If the directory does not exist, exit with error
		if (!is_dir($configPath)) {
			trigger_error("The configuration path '$configPath' does not exist.", E_USER_ERROR);	
		}
		
		// Create handle
		$dh = opendir($configPath);
		
		// Ensure we can loop through files
		if ($dh) {
				
			// Read all the files in the path
			while(false !== ($file = readdir($dh))) {
		
				// define our empty $_config array
				$_config = array();

				// Skip . and .. dirs
				if ($file == "." || $file == "..") {
					continue;
				}
				
				// Get the full file-path
				$filePath = $configPath.$file;
				
				// get the configuration section from file name
				$section = basename($filePath, ".php");
				
				// include the config file
				include($filePath);
				
				// Merge the array
				$this->_config[$section] = $_config[$section]; 
				
			}
			
		}
		
		// close the directory
		closedir($dh);
		
	}
	
	/**
	 * Gets an value from the configuration by section. 
	 * If the $name param is null, then the entire section is returned as an array.
	 * @param string $section The name of the configuration section.
	 * @param unknown_type $name The name of the key to return. If null, then the entire section is returned as an array.
	 */
	public function getItem($section, $name = null) {

		// If the $section is null or the section does not exist, return null
		if ($section == null || !isset($this->_config[$section])) {
			return null;
		}
		
		// if the name is null, just return the section
		if ($name == null) {
			return $this->_config[$section];
		} else {
			// The name was specified, so return the value of the config item
			return $this->_config[$section][$name];	
		}
		
	}
	
	/**
	 * Returns the current instance of the config object.
	 */
	public static function current() {
		
		// If the instance is null, instantiate it and load the configuration
		if (self::$_instance == null) {
			
			// instantiate and load config
			self::$_instance = new EV_Config();
			self::$_instance->loadConfiguration();
				
		}
		
		// return the instance
		return self::$_instance;
		
	}
	
	/**
	 * Gets an value from the configuration by section. 
	 * If the $name param is null, then the entire section is returned as an array.
	 * @param string $section The name of the configuration section.
	 * @param unknown_type $name The name of the key to return. If null, then the entire section is returned as an array.
	 */
	public static function get($section, $name = null) {		
		return self::current()->getItem($section, $name);
	}
	
}