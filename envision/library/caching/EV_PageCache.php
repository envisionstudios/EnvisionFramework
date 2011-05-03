<?php

class EV_PageCache extends EV_Cache
{
	
	/**
	 * Adds the contents of the current framebuffer to the cache.
	 * @param string $cacheKey
	 * The cache key of the page being cached.
	 * @param boolean. $endFlush [optional]
	 * If $endFlush is true, then the output buffer is ended, and the output buffer is emptied.
	 */
	public static function add($cacheKey, $endFlush = false) {
		
		// If the item is already cached, don't do anything
		if (self::isCached($cacheKey)) {
			return;
		}
		
		// Gets the data from the output buffer
		$data = ob_get_contents();
		
		// If we want to flush and end
		if ($endFlush) {
		
			// End output buffering and flush
			ob_end_flush();
			
		}
		
		// write the cache
		self::writeCache($cacheKey, $data);
		
	}
	
	/**
	 * Gets the page from the cache, based on the $cacheKey.
	 * @param string $cacheKey
	 * The cache key of the page.
	 * @return string. The cached page markup.
	 */
	public static function get($cacheKey) {
		
		// Ensure item is cached
		if (self::isCached($cacheKey)) {
		
			// Get the data
			$data = self::readCache($cacheKey);
			
			// Ensure not null before outputting
			if ($data != null) {
			
				// Echo the data to the output buffer
				return $data;
				
			}
			
		}
		
	}
	
}

?>