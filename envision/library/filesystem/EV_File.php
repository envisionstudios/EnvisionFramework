<?php

// Define our constants that can be used for the File class

class EV_File extends EV_Object {

	/**
	 * Writes data to a file. If the file does not exist, it will be created.
	 * @param string $filename
	 * The filename of the the file to create.
	 * @param string $data
	 * The data to write to the file.
	 * @param boolean $append [optional]
	 * TRUE will append $data to the end of the file, FALSE will overwrite the file. The default value is FALSE.
	 * @param string $manualMode [optional]
	 * If you wish to use a mode other than 'w' or 'a' then specify it here.
	 * @return boolean
	 * Returns true if the write was successful, false if it was not.
	 */
	public static function writeFile($filename, $data, $append = false, $manualMode = NULL) {
	
		if (EV_String::isNullOrEmpty($filename) || $data == NULL) {
		
			return false;
			// TODO: Add error checking code.
			
		}
		
		// Try to write the contents
		try {
		
			// Set the append mode or overwrite mode
			$mode = ($append == true ? "a" : "w");
			
			// If a manual mode has been entered, use that. if not, revert back to $mode.
			$mode = ($manualMode != NULL ? $manualMode : $mode);
		
			// Create the file pointer
			$fp = fopen($filename, $mode);
			
			// Lock the file
			flock($fp, LOCK_EX);
			
			// Write the contents to the file
			fwrite($fp, $data);
			
			// Unlock the file
			flock($fp, LOCK_UN);
			
			// Close the file
			fclose($fp);
			
			// Return true
			return true;
			
		} catch (Exception $ex) {
			
			trigger_error($ex, E_USER_ERROR);
			
		}
		
	
		
	}

}

?>