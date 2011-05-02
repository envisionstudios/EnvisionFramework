<?php

class Template extends Object {
	
	/**
	 * Stores values that can later be used in our template.
	 * @var array. Collection of variables to use in our templates.
	 */
	protected $_vars = array();
	
	/**
	 * The controller for the current template.
	 * @var string
	 */
	protected $_controller;
	
	/**
	 * The action for the current controller.
	 * @var string
	 */
	protected $_action;
	
	/**
	 * The default constructor for the template.
	 * @param string $controller
	 * @param string $action
	 */
	function __construct($controller, $action) {
		// Set the controller and action
		$this->_action = $action;
		$this->_controller = $controller;
	}
	
	/**
	 * Render our layout.
	 */
	public function Render() {
				
		// extract our variables.
		extract($this->_vars);
		
		// generate our layout file name
		$layoutFile = APP_PATH.'views/Page.php';
				
		// parse the template
		$output = $this->parseTemplate($layoutFile, true);
		
		// print it.
		print(eval("return<<<END\n$output\nEND;\n"));
		
	}
	
	private function parseTemplate($file, $isLayout = false) {

		// If the file does not exist, throw an error
		if (!file_exists($file)) {
			throw new Exception("Unable to load Layout. '$file' does not exist.");	
		}
		
		// Get the contents of the file.
		$output = file_get_contents($file);
		
		// If we are rendering the layout, we need to parse the view and load it into {$Layout}
		if ($isLayout) {
			
			// generate our view file name
			$viewFile = APP_PATH.'views/'.$this->_controller.'/'.ucwords($this->_action).'.php';
			
			// Load the view file.
			if (!file_exists($viewFile)) {
				throw new Exception("Unable to load View. '$viewFile' does not exist.");
			}
			
			// If there is the {$Layout} tag, replace it with the contents of the view
			if (preg_match('/\{\$Layout\}/', $output)) {
				$output = preg_replace('/\{\$Layout\}/', file_get_contents($viewFile), $output);
			}
			
		}
		
		// return the page output.
		return $output;
		
	}
	
}

?>