<?php

class DevelController extends EV_Controller {
	
	/**
	 * Set the controller as a system controller.
	 */
	protected $isSystem = true;
	
	public function index() {
		
	}
	
	public function clearCache() {

		// clear the cache
		EV_Cache::clearCache();
		
	}
	
	
}