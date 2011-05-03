<?php

class EV_Model extends EV_Object {
	
	/**
	 * The constructor for the model.
	 */
	public function __construct() {
			
		// Create the class properties
		foreach($this->generateProperties() as $k => $v) {
			$this->$v = null;
		}
		
	}
	
	/**
	 * Gets the properties from the database. The properties will be the ColumnNames in the database.
	 * @return Array containing the field names from the database.
	 */
	private function generateProperties() {
		
		// Get the results
		$results = EV_DB::current()->query("SHOW COLUMNS FROM ".self::getName());
		
		// Create the fields array
		$fields = array();
		
		// Loop through the results, only getting the "Field" column
		foreach ($results as $column) {
			$fields[] = $column["Field"];			
		}
		
		// return the field names
		return $fields;	
		
	}
	
	/**
	 * Selects multiple modles of a single model type from the database. 
	 * @param string $model  The name of the model.
	 * @param string $filter  The filter to apply to the model. This will be used as the WHERE clause.
	 * @param string $join  The statement to join additional tables.
	 * @param string $sort  How to order the results. This will be used as the ORDER BY clause.
	 * @param string $limit  Limit the result set.
	 * @return The ResultSet of the data. If no results are found, and empty ResultSet is returned.
	 */
	public static function getAll($filter = '', $join = '', $sort = '', $limit = '') {
				
		$results = EV_DB::current()->selectModels(self::getName(), $filter, $join, $sort, $limit);
		
		// Get the name of the model
		$modelname = self::getName();
		
		$model = new $modelname();
		
		// Set the properties of the object
		foreach($results[0] as $k => $v) {
			$model->$k = $v;
		}
		
		// return the object
		return $model;
		
	}
	
}