<?php

class EV_MysqlDB extends EV_DB
{
	/*
	 * Constructor.// Define the sql to select installed plugins
	 * $sql = "SELECT * FROM plugins";
	 * 
	 * 	// reference the gloabl database variable.
	 * global $database;
	 * 
	 * // Get the list of currently installed plugins
	 * $database->returnResult($sql);
	 * 
	 * Creates the new database object, setting the values for the username, password, host and name properties
	 */
	function __construct() {
		
		// Set the value of the class members
		$this->username = EV_Config::get('database', 'username');
		$this->password = EV_Config::get('database', 'password');
		$this->host = EV_Config::get('database', 'server');
		$this->name = EV_Config::get('database', 'dbname');
		
		// Set the db property to null, so we can check whether it connected.
		$this->db = null;
		
	}
	
	/**
	 * Opens the connection to the database
	 */
	protected function openConnection() {
		
		// Connect to the database server
		$this->db = mysql_connect($this->host, $this->username, $this->password);
		
		// If the sql error is not null, set the error message
		if (!EV_String::isNullOrEmpty(mysql_error())) {
			
			// Set the message
			trigger_error(sprintf("Error occurred connecting to server '%s'. Error: %s", $this->host, mysql_error()), E_USER_WARNING);
			
		}
		
		// Select the database from server and use it
		$db_selected = mysql_select_db($this->name, $this->db);
		
		// Ensure the database is selected.
		if (!$db_selected) {
			
			// database not selected, set the error
			trigger_error(sprintf("Error selecting database '%s'. Error: %s", $this->name, mysql_error()), E_USER_WARNING);
		}
		
		
	}
	
	/**
	 * Closes an existing connection to the database if its open
	 */
	protected function closeConnection() {

		// Ensure the connection is open before closing
		if ($this->db != null) {

			// Close the database connection
			mysql_close($this->db);
			
			// set db value to null
			$this->db = null;
			
		}
	}
	
	/**
	 * @param $sql - The sql query to execute.
	 * @return Result Set. If the resultsOnly value is set to true, the array containing the results is returned, not the ResultSet object.
	 */
	public function query($sql) {
		
		// Connect to the database
		$this->openConnection();
		
		// Get the results from the cache
		$results = EV_DataCache::get(md5($sql));
		
		// If the results are null, fetch from database
		if ($results === null) {
			
			// Generate the query result
			$queryResult = mysql_query($sql, $this->db);
			
			// If there was an error message, set it, and return the error handler
			if (!EV_String::isNullOrEmpty(mysql_error())) {
				
				// Set the error message
				$this->setErrorMessage();
				
				// Set the error message and exit
				trigger_error($this->getErrorMessage(), E_USER_WARNING);
					
			}
			
			// Create the results array
			$results = array();
			
			// Get the row
			$row = mysql_fetch_assoc($queryResult);
			
			// Loop through each of the rows in the result set
			while ($row) {
	
				// Set the current row value to the assoc array of data.
				array_push($results, $row);
				
				// Reset the row value
				$row = mysql_fetch_assoc($queryResult);
				
			}
			
			// Work done, close the connection
			$this->closeConnection();
			
			// Add to the cache for next time
			EV_DataCache::add(md5($sql), $results);
			
		}
		
		// return the resutls
		return $results;
		
	}
	
	/**
	 * @param $sql: The query to execute
	 * @return The value of the first column in the first row of the results. 
	 */
	public function executeScalar($sql) {

		// Connect to the database
		$this->openConnection();
		
		// Get the result from the query
		$queryResult = mysql_query($sql, $this->db);
		
		// If there was an error message, set it, and return the error handler
		if (!EV_String::isNullOrEmpty(mysql_error())) {
			
			// Set the error message
			$this->setErrorMessage();
			
			// Set the error message and exit
			trigger_error($this->getErrorMessage(), E_USER_WARNING);
				
		}
		
		// get the result row
		$row = mysql_fetch_row($queryResult);
		
		// Set the lastID property of the class.
		$this->lastID = mysql_insert_id($this->db);
		
		// Close the connection
		$this->closeConnection();
		
		// If the first element of the row array is not null, return it.
		// Otherwise, return null
		if ($row[0] != null) {
			return $row[0];
		} else {
			return null;
		}
		
	}
	
	/**
	 * @param $sql. The query to execute
	 * @return The number of rows affected by the query. Use Database->getLastID to get the ID of the last inserted row.
	 */
	public function execute($sql) {

		// Connect to the database
		$this->openConnection();
		
		// Execute the query
		mysql_query($sql, $this->db);
		
		// If there was an error message, set it, and return the error handler
		if (!EV_String::isNullOrEmpty(mysql_error())) {
			
			// Set the error message
			$this->setErrorMessage();
			
			// Set the error message and exit
			trigger_error($this->getErrorMessage(), E_USER_WARNING);
				
		}
		
		// Set the lastID property of the class.
		$this->lastID = mysql_insert_id($this->db);
		
		// Get the amount of rows affected.
		$rowsAffected = mysql_affected_rows($this->db);
		
		// Close the connection
		$this->closeConnection();
		
		// Return the rows affected value.
		return $rowsAffected;
		
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
	public function selectModels($model, $filter = '', $join = '', $sort = '', $limit = '') {
		
		// reset parameters to empty if null
		$filter = ($filter === null ? '' : trim($filter));
		$join = ($join === null ? '' : trim($join));
		$sort = ($sort === null ? '' : trim($sort));
		$limit = ($limit === null ? '' : trim($limit));
		
		// generate our SQL statement
		$sql = self::generateSqlStatement($model, $filter, $join, $sort, $limit);
		
		// Creeate the variable to store the results into.
		$results = EV_DB::current()->query($sql);
		
		// return the results
		return $results;
		
		
	}
	
	/**
	 * Selects a single model from the database. 
	 * @param string $model  The name of the model.
	 * @param string $filter  The filter to apply to the model. This will be used as the WHERE clause.
	 * @param string $join  The statement to join additional tables.
	 * @param string $sort  How to order the results. This will be used as the ORDER BY clause.
	 * @param string $limit  Limit the result set.
	 * @return The Model object. If a result is not found, null is returned.
	 */
	public function selectSingleModel($model, $filter = '', $join = '', $sort = '', $limit = '') {
		
	}
	
	/**
	 * Selects a single model from the database. 
	 * @param string $model  The name of the model.
	 * @param string $id  The ID of the model to get.
	 * @return The Model object. If no model with the $id is found, null is returned.
	 */
	public function selectSingleModelByID($model, $id) {
		
	}
	
	/**
	 * Generates the SQL statement that is used to select Models.
	 * @param string $model  The name of the model.
	 * @param string $filter  The filter to apply to the model. This will be used as the WHERE clause.
	 * @param string $join  The statement to join additional tables.
	 * @param string $sort  How to order the results. This will be used as the ORDER BY clause.
	 * @param string $limit  Limit the result set.
	 */
	private static function generateSqlStatement($model, $filter, $join, $sort, $limit) {

		
		// replace keywords that should not be included
		// WHERE
		if (substr(strtolower($filter), 0, 5) == "where") {
			$filter = substr($filter, 5);
		}
		
		// ORDER BY
		if (substr(strtolower($sort), 0, 8) == "order by") {
			$sort = substr($sort, 8);
		}
		
		// LIMIT
		if (substr(strtolower($limit), 0, 5) == "limit") {
			$limit = substr($limit, 5);
		}
		
		// Begin to generate the query
		$sql = 'SELECT * FROM '.$model;
		
		// Append the join if we have a join
		if (strlen($join) > 0) {
			$sql .= ' '.$join;	
		}
		
		// If we have a filter, we now need to apply that
		if (strlen($filter) > 0) {
			$sql .= ' WHERE '.$filter;	
		}
		
		// If we have a sort value, we need to add that also
		if (strlen($sort) > 0) {
			$sql .= ' ORDER BY '.$sort;	
		}
		
		// append our limit
		if (strlen($limit) > 0) {
			$sql .= ' LIMIT '.$limit;
		}
		
		// Append our ; to finalise the query
		$sql .= ';';
		
		// return our SQL
		return $sql;
		
	}
	
	
	
}

?>