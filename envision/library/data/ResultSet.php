<?php

class EV_ResultSet extends EV_Object
{

	/**
	 * @var Multi-dimension array. 
	 */
	private $resultSet;
	
	/**
	 * @var integer. The amount of rows returned from a sql query
	 */
	private $rowCount;
	
	/**
	 * @var resource. The result from executing mysql_query
	 */
	private $queryResult;
	
	function __construct($queryResult) {
		
		// Set the row count
		$this->rowCount = 0;
		
		// Create the array to hold the results
		$this->resultSet = array();

		// If the result is not false, then generate the array of values
		if ($queryResult != false) {
			
			// Set the resultSet variable
			$this->queryResult = $queryResult;
			
			// populate the resultSet
			$this->_populateResultSet();
			
		}
		
	}
	
	/**
	 * @return Array containing the result set. The array is a multi-dimension array in the format: $resultSet[rowIndex][columnName]
	 */
	public function getResultSet() {
		return $this->resultSet;
	}
	
	/**
	 * @return The amount of rows returned from the mysql query. 
	 */
	public function getRowCount() {
		return $this->rowCount;
	}
	
	/**
	 * Populates the resultSet property from the mysql result resource.
	 * @return none.
	 */
	private function _populateResultSet() {
		
		// Set the index iterator
		$i = 0;
		
		$row = mysql_fetch_assoc($this->queryResult);
		
		// Loop through each of the rows in the result set
		while ($row) {

			// Set the current row value to the assoc array of data.
			$this->resultSet[$i] = $row;
			
			// increment the iterator counter
			$i++;

			// Reset the row value
			$row = mysql_fetch_assoc($this->queryResult);
			
		}
		
		// Set the row count value
		$this->rowCount = $i;
		
		// Now that were done, we can release the resource
		$this->queryResult = null;
		
	}

}

?>