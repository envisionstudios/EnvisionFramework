<?php
    
class EV_Directory extends EV_Object {
	
	public static function getFiles($path, $recursive = false) {
		
		// Create the files array
		$files = array();
		
		if (!EV_String::isNullOrEmpty($path)) {
			
			// Get the files in the iterator
			self::getFilesIterator($path, &$files, $recursive);
			
		}
		
		// return the files
		return $files;
		
	}
	
	/**
	 * The private helper function for getFiles();
	 * @param string $path The path to loop for looking for files
	 * @param string $files The files array to contain the file names
	 * @param bool $recursive Indicates weather to recursivly loop through the directories 
	 * @return 
	 */
	private static function getFilesIterator($path, &$files, $recursive) {
		
		// set the directory handle
		$handle = opendir($path);
		
		// Open the directory
		if ($handle) {
				
			// Read all the files in the path
			while(false !== ($file = readdir($handle))) {
				
				// Skip the current and parent directories
				if ($file == "." || $file == "..") {
					return;
				}
				
				// check if the file is actually a directory, and if recursive is true, the perform the recursion
				if (is_dir($file) && $recursive) {
					// Do the recursion
					self::getFilesIterator($file, &$files, $recursive);
				} else {
					
					// add the file to the array
					array_push($files, $file);
					
				}
				
			}
			
		}
		
	}
	
}

?>