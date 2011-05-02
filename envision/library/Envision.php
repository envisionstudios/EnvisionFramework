<?php

class Envision extends Object {
	
	/**
	 * The router object to handle and manipulate routes.
	 * @var Router
	 */
	public $Router;
	
	/**
	 * Constructor for the Envision object
	 */
	public function __construct() {
		// Instantiate the router object
		$this->Router = new Router();
	}
	
}