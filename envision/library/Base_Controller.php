<?php

class Base_Controller extends Object {

	/**
	 * The name of the current controller.
	 * @var string
	 */
	public $Controller;
	
	/**
	 * The name of the action for the current controller.
	 * @var string.
	 */
	public $Action;
	
	/**
	 * The template object used to render our page.
	 * @var Template
	 */
	public $Template;
	
	/**
	 * The default constructor for the base controller.
	 * @param string $controller. The name of the current controller.
	 * @param string $action. The name of the current action.
	 */
	function __construct($controller, $action) {

		// Set the default variables.
		$this->Controller = $controller;
		$this->Action = $action;
		
		// Instantiate the template
		$this->Template = new Template($controller, $action);
		
	}
	
	/**
	 * This method is executed before the action is executed.
	 */
	public function OnBeforeAction() {
		
	}
	
	/**
	 * This method is executed after the action is executed.
	 */
	public function OnAfterAction() {
		
	}
	
}