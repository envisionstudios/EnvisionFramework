<?php 

/*
 * Autoloads any classes required by ContentEngine. This function means that we never need to "require_once" any of our class files.
 * Note: This function only loads classes as needed, rather than load them all at once.
 * @param string $class.
 * The name of the class to autoload.
 */
function __autoload($class) {

    // Define the class directories
    $classes = generateClassManifest();
    
    // Loop through our dirs
    foreach ($classes as $file)
    {
    
		// ensure file exists
		if (file_exists($file))
		{
			
			// Generate the pattern
			$pattern = '/.*\/'.$class.'.php/';
		
			if (preg_match($pattern, $file)) {
		
				// require the file
				require_once($file);
					
				// Don't iterate any further
				return;
				
			}	
		}
	
    }
	
}

/*
 * Gets the files for the class manifest
 * @return Array of classes
 */
function generateClassManifest() {
	
	// Create the files array
	$files = array();
	
	// List the paths to generate the manifest from.
	$autoloadPaths = array(
		FRAMEWORK_PATH.'library/',
		FRAMEWORK_PATH.'controllers/',
		FRAMEWORK_PATH.'library/',
		APP_PATH.'controllers/',
		APP_PATH.'library/',
		APP_PATH.'models/'
		);
		
	// Loop through each path
	foreach($autoloadPaths as $path) {
		// Iterate through the directory
		iterateDirectories($path, $files);
	}
	
	// Save the files to the array
	$fh = fopen(getManifestFilename(), 'w');
		
	// if the file handle exists, write the manifest
	if ($fh !== false) {
		fwrite($fh, serialize($files));	
	}
	
	// close the file resource
	fclose($fh);
	
	// return the files
	return $files;
	
}

/*
 * Loads the class manifest file into an array of classes.
 */
function loadClassManifest() {
	
	// Create the files array
	$files = array();
	
	// get the manifest filename
	$manifestFilename = getManifestFilename();
	
	// If the class manifest file does not exists, create it. 
	if (!is_file($manifestFilename)) {
		// generate the manifest and return the array of files.
		return generateClassManifest();
	}
	
	// Open the file for reading
	$fh = fopen($manifestFilename, 'r');
	
	// If the file handle is ok, read the contents back into the $files array
	if ($fh !== false) {
		// read the contents
		$contents = fread($fh, filesize($manifestFilename));
		// unserialise
		$files = unserialize($contents);
	}
	
	// close the file resource
	fclose($fh);
	
	// return the files
	return $files;
	
}

/*
 * Gets the path the class manifest
 */
function getManifestFilename() {
	return APP_PATH.'cache/class-manifest';
}

/*
 * Recursive function to add all .php files into the $files array 
 * @param string $path The path to search
 * @param array $files The files array
 */
function iterateDirectories($path, &$files) {
	
	// Create handle
	$dh = opendir($path);
	
	// Open the directory
	if ($dh) {
			
		// Read all the files in the path
		while(false !== ($file = readdir($dh))) {
			
			// if the file contains a . in the first char, ignore it
			if (substr($file, 0, 1) == ".") {
				continue;
			}
			
			// set the full file path
			$filePath = $path . $file;
			
			if (substr($path, -1) != "/") {
				$filePath = $path . "/" . $file;
			}
			
			// check if the file is actually a directory, and if recursive is true, the perform the recursion
			if (is_dir($filePath)) {
				
				// Do the recursion
				iterateDirectories($filePath, &$files);
				
			} else {
				
				if (substr(strtolower($file), -4) == ".php") {
					// add the file to the array, if the class name is not already within the manifest
					$filename = basename($file, ".php");
					if (!array_key_exists($filename, $files)) {
						$files[$filename] = $filePath;
					}
				}
				
			}
			
		}
		
	}

	// close the directory
	closedir($dh);
	
}



/*
 * This function overrides the default PHP error handler functionalit. 
 * Generally, this function should never really be called, as PHP will call it upon error.
 * @param int $errorCode
 * The error code.
 * @param string $errorMessage
 * The error message.
 * @param string $errorFile
 * The path to the file that caused the error.
 * @param int $errorLine
 * The line on which the error occured in the file.
 * @return boolean true to override default error handler
 */
function evErrorHandler($errorCode, $errorMessage, $errorFile, $errorLine) {
	
	// Create the exception
	$ex = new EV_FrameworkException($errorMessage, $errorCode, $errorFile, $errorLine);
	
	// Handle the exception
	evExceptionHandler($ex);
	
	// Do not execute the php error handler
	return true;
	
}

/*
 * This function overrides the default Exception handlign functionality.
 * This function should not be called, as you should use 'throw new Exception' instead.
 * @param Exception $ex
 * The exception that was thrown
 */
function evExceptionHandler($error) {

	// If the $error is an object, set the getMessage instead.
	$errorMsg = (is_object($error) ? $error->getMessage() : $error);
	
	// Log the message to the log file
	//Debug::logMessage($errorMsg, DEBUG_LEVEL_ERROR);

	// If we don't want to sure complex errors to users, reset to default message.
	//if (!Config::get("SHOW_DETAILED_ERRORS")) {
	
		//$error  = "An application error has occurred, and details of this error have been logged.\n";
		//$error .= "Please notify the website administrator of this message.";
		
	//}

	// Set the session error message
	$_SESSION["APP_ERROR"] = $error;

	// Clear the current output buffer
	//ob_end_clean();
	
	// Show the error page
	include(FRAMEWORK_PATH."sys_pages/error.php");
	
	// flush and end the output buffer
	//ob_end_flush();
	
	$exitCode = (isset($exitCode) ? $exitCode : 1);
	
	// Exit the script
	exit($exitCode);
	
}

/*
 * Used to strip slashes from gpc values
 */
function gpcStripSlashes(&$value) {
	$value = stripslashes($value);
}

/*
 * Disables magic quotes
 */
function disableMagicQuotes() {

	// Check if magic quotes is enabled
	if (get_magic_quotes_gpc()) {

		// remove slashes from GPC values
		array_walk_recursive($_GET, 'gpcStripSlashes');
		array_walk_recursive($_POST, 'gpcStripSlashes');
		array_walk_recursive($_COOKIE, 'gpcStripSlashes');
		array_walk_recursive($_REQUEST, 'gpcStripSlashes');
		
	}
	
}
