<?php

class EV_Controller extends EV_Object {

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
	 * Determines if the controller is a system controller, and therefore should use the system templates.
	 */
	protected $isSystem = false;
	
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
		$this->Template = new EV_Template($controller, $action, $this->isSystem);
		
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
	
	/**
	 * This is the first method to run in the execution pipeline.
	 */
	public function OnInit() {
		
	}
	
	/**
	 * This is the last method to run in the execution pipeline
	 */
	public function OnEnd() {
		
	}
	
	/**
	 * Gets a value indicating if the controller is a system controller.
	 */
	public function getIsSystem() {
		return $this->isSystem;
	}
	
}