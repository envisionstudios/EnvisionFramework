<?php

abstract class EV_Cache extends EV_Object
{
	
	/**
	 * Writes the cache data to the filesystem, based on the $cahceKey value
	 * @param string $cacheKey
	 * The unique key for this cache item. Used to generate the filename that will store the cache data.
	 * @param string $data
	 * The cache data to be written to the filesystem. This will either be page output, or serialized data.
	 */
	protected static function writeCache($cacheKey, $data) {
		
		// Get the cache filename
		$filename = self::getFilename($cacheKey);
		
		try {
			
			// Write the cache to the file.
			EV_File::writeFile($filename, $data);

			// generate the expiry of the cache.
			$expiry = time() + EV_Config::get('cache', 'expiry');

			// set the filetime of the file. This is used to check expiry
			touch($filename, $expiry);
			
		} catch (exception $ex) {
			
			// Set the error message
			trigger_error(sprintf("Error writing to cache. Error: %s", $ex), E_USER_ERROR);
			
		}
		
	}
	
	/**
	 * Reads the cached information from the filesystem.
	 * @param string $cacheKey
	 * The unique key for the cache item.
	 * @return string. Returns the contents of the cached file.
	 */
	protected static function readCache($cacheKey) {
		
		// Generate the filename of the cacheFile
		$filename = self::getFilename($cacheKey);
		
		// Ensure cache for item exists
		if (self::isCached($cacheKey)) {
		
			try {	
		
				// Read the cache from file
				$cacheContents = file_get_contents($filename);
				
				// return cache
				return $cacheContents;
				
			} catch (exception $ex) {
			
				// TODO: Add log file message
				// Some sort of error occured, just return null
				return NULL;
					
			}
		
		} else {
		
			// Not cached, return null
			return NULL;
			
		}
		
	}
	
	/**
	 * Determined if the $cacheKey contains a cached object or not.
	 * @param string $cacheKey
	 * The unique cacheKet for the object/page.
	 * @return boolean. True of the item exists in the cache, and has not expired, false otherwise.
	 */
	public static function isCached($cacheKey) {
	
		// Get the filename to check cache for.
		$filename = self::getFilename($cacheKey);
		
		// Check if file exists and the filetime is > the current time
		if (file_exists($filename) && filemtime($filename) > time()) {
		
			// File exists, and not expired, so return true
			return true;
			
		} else {
			
			// try to delete the file
			try {
				
				// Ensure file exists before deleting
				if (file_exists($filename)) {
				
					// Delete the file
					unlink($filename);
				
				}
				
			} catch (exception $ex) {
				
				// Some error occured (file probably doesnt exist), return false
				return false;
					
			}
			
			// Not cached, return false
			return false;
			
		}
		
	}
	
	/**
	 * Generates the filename of the cache file based on the cache key 
	 * @param string $cacheKey
	 * The unique key for the cached item
	 * @return string. The filename of the cahced item.
	 */
	protected static function getFilename($cacheKey) {
	
		// Return the generated filename
		return sprintf("%s%s%s", EV_Config::get('cache', 'path'), EV_Config::get('cache', 'prefix'), $cacheKey);
		
	}
	
	/**
	 * Clears all items from the cache.
	 */
	public static function clearCache() {
		
		$cachePath = EV_Config::get('cache', 'path');
		
		// Ensure CACHE_DIR is a directory
		if (is_dir($cachePath)) {
			
			try {
			
				// Get a list of files from the cache_dir
				$files = scandir($cachePath);
				
				// Loop through the files in the directory
				foreach ($files as $file) {
					
					// Skip the . and .. directories
					if ($file == "." || $file == "..") {
						continue;	
					}
					
					// Delete the file
					unlink($cachePath.$file);
				}
				
				// return
				return;
				
			
			} catch (Exception $ex) {
				// Log the message
				// TODO: log message
				//Debug::logMessage($ex->getMessage(), DEBUG_LEVEL_ERROR);				

				// Don't need to anything here, return
				return;
				
			}
			
		}
		
	}
	
	/**
	 * Clears a specific item from the cache.
	 * @param string $cacheKey
	 * The item in cache to remove.
	 * @return boolean. True if removed successfully, false if item does not exist or there was an error.
	 */
	public static function remove($cacheKey) {
		
		// ensure cached item exists.
		if (EV_Cache::isCached($cacheKey)) {
			
			try {
			
				// Get the filename to check cache for.
				$filename = self::getFilename($cacheKey);
				
				// Ensure the file exists.
				if (file_exists($filename)) {
				
					// Delete the file.
					unlink($filename);
					
					// return success
					return true;
					
				}
				
			} catch (Exception $ex) {
				
				// Log the message
				// TODO: log message
				//Debug::logMessage($ex->getMessage(), DEBUG_LEVEL_ERROR);				

				// Don't need to anything here, return
				return false;
				
			}
			
		} else {
		
			// Does not exist, return false
			return false;
			
		}
		
	}
	
	/**
	 * Gets the safe cache key from the $pathInfo variable. Removes all / and . (period) characters from the pathInfo
	 * @param string $pathInfo. The pathInfo to make path safe
	 * @return The path safe cacheKey
	 */
	public static function getSafeCacheKey($pathInfo) {
	
		// If the pathInfo is null or empty, return empty
		if (EV_String::isNullOrEmpty($pathInfo)) {
			return "";	
		}
		
		// Replace directory seperators and . (period) chars with the _ characters.
		$pathInfo = preg_replace('/(\.)|(\/)/', "_", $pathInfo);
		
		// return the replaced string
		return $pathInfo;
		
	}
	
}

?>