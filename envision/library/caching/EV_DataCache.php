<?php

class EV_DataCache extends EV_Cache
{
	
	/**
	 * Add a new data cache item to the cache
	 * @param string $cacheKey
	 * The cache key of the item to cache.
	 * @param mixed $data
	 * The data to cache.
	 */
	public static function add($cacheKey, $data) {
		
		// add the data to the cache.
		self::writeCache($cacheKey, serialize($data));
		
	}
	
	/**
	 * Gets a data cache item from the cache, based on the $cacheKey
	 * @param string $cacheKey
	 * The cache key of the cached item
	 * @return mixed. The item that was cached, if it was cached, or NULL if not cached.
	 */
	public static function get($cacheKey) {
		
		// Ensure data is cached
		if (self::isCached($cacheKey)) {
		
			// returned the cache data
			return unserialize(self::readCache($cacheKey));
			
		} else {
			
			// return null, as not cached.
			return NULL;
			
		}
		
	}
	
}

?>