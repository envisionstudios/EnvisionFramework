<?php

class EV_ModelCollection extends EV_Object implements Iterator {
	
	/**
	 * Array that will contain the collection of models
	 * @var array
	 */
	protected $items; 
	
	/**
	 * Creates a new instance of the ModelCollection class.
	 */
	function __construct($items = null) {
		
		// Instantiate the items array
		$this->items = array();
		
		// If the items parameter is not null and is an array, merge it into the class $items collection
		if ($items != null && is_array($items)) {
			$this->items = array_merge($this->items, $items);	
		}
		
		
	}
	
	/** 
	 * Returns the current element in the collection
	 */
	public function current() {
		return current($this->items);
	}

	/** 
	 * Fetches the key for the current element from the array
	 */
	public function key() {
		return key($this->items);
	}

	/** 
	 * Advance the internal array pointer of an array to the next element.
	 */
	public function next() {
		return next($this->items);		
	}

	/**
	 * Set the internal pointer of an array to its first element
	 */
	public function rewind() {
		return reset($this->items);
	}

	/** 
	 * Determines if the current element is valid.
	 */
	public function valid() {
		return false !== current($this->items);
	}

	
}