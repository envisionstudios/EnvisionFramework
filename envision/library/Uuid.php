<?php

class Uuid extends Object {

	/**
	 * Generate a universally unique identifier (UUID) string
	 * @return new UUID
	 */
	public static function generateUuid() {
	
		// Generate the characters
		$chars = md5(uniqid(rand(), TRUE));
		
		// Hopefully this conforms to a standard, and can be used as a Microsoft GUID.
		// To achive this, we need to change come bytes.
		// The flags the UUID as "random type"
		$byte = hexdec(substr($chars, 12, 2));
		$byte = $byte & hexdec("0f");
		$byte = $byte | hexdec("40");
		$chars = substr_replace($chars, strtoupper(dechex($byte)), 12, 2);
		
		// This sets the variant of the UUID
		$byte = hexdec(substr($chars, 16, 2));
		$byte = $byte & hexdec("3f");
		$byte = $byte | hexdec("80");
		$chars = substr_replace($chars, strtoupper(dechex($byte)), 16, 2);
		
		// create UUID string
		$uuid = sprintf("%s-%s-%s-%s-%s", substr($chars, 0, 8), substr($chars, 8, 4), substr($chars, 12, 4), substr($chars, 16, 4), substr($chars, 20, 12)); 
		
		// return the UUID
		return strtoupper($uuid);
		
	}
	
	/**
	 * Generates a new UUID, but only returns the first 8 characters. 
	 * @return First 8 characters of the UUID
	 */
	public static function generateShortUuid() {
	
		return substr(self::generateUuid(), 0, 8);
		
	}

}

?>