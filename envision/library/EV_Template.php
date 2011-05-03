<?php

class EV_Template extends EV_Object {
	
	/**
	 * Stores values that can later be used in our template.
	 * @var array. Collection of variables to use in our templates.
	 */
	protected $vars = array();
	
	/**
	 * The controller for the current template.
	 * @var string
	 */
	protected $controller;
	
	/**
	 * The action for the current controller.
	 * @var string
	 */
	protected $action;
	
	/**
	 * Determines if the current request is for a system request
	 * @var string
	 */
	protected $isSystem;
	
	/**
	 * The default constructor for the template.
	 * @param string $controller
	 * @param string $action
	 */
	function __construct($controller, $action, $isSystem = false) {
		// Set the controller and action
		$this->controller = $controller;
		$this->action = $action;
		$this->isSystem = $isSystem;
	}
	
	/**
	 * Render our layout.
	 */
	public function Render() {
				
		// extract our variables.
		extract($this->vars);
		
		// generate our layout file name
		$layoutFile = ($this->isSystem ? FRAMEWORK_PATH : APP_PATH).'views/Page.php';
				
		// parse the template
		$output = $this->parseTemplate($layoutFile, true);
		
		// print it.
		print(eval("return<<<END\n$output\nEND;\n"));
		
	}
	
	private function parseTemplate($file, $isLayout = false) {

		// If the file does not exist, throw an error
		if (!file_exists($file)) {
			trigger_error("Unable to load Layout. '$file' does not exist.", E_USER_ERROR);	
		}
		
		// Get the contents of the file.
		$output = file_get_contents($file);
		
		// If we are rendering the layout, we need to parse the view and load it into {$Layout}
		if ($isLayout) {
			
			$viewPathSuffix = 'views/'.$this->controller.'/'.ucwords($this->action).'.php'; 
			
			// generate our view file name
			$viewFile = APP_PATH.$viewPathSuffix;
			
			// Check to see if it as system view.
			if (!file_exists($viewFile)) {
				$viewFile = FRAMEWORK_PATH.$viewPathSuffix;
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