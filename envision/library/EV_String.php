<?php

class EV_String extends EV_Object
{
	
	/**
	 * Checks to see if an object is null or empty. A string is empty if strlen(#str) returns 0.
	 * If a $str is not actually a string, then returns true.
	 * @param string $str
	 * @return Returns false if the string is not empty. Returns true if the $str is null, empty string or not a string. 
	 */
	public static function isNullOrEmpty($str) {
	
		// Check to see if the string is a string, is null or is empty.
		if (!is_string($str) || $str == NULL || strlen($str) == 0) {
			return true;
		} else {
			// The string is not empty, so return false
			return false;
		}
		
	}
	
}
?>