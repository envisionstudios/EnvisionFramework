<?php

/**
 * Object class. This object is the top item in the type inheritance tree. That is, every object defined within ContentEngine extends this base class.
 */
class EV_Object {


	/**
	 * Generates a hashcode for the object.
	 * This the value returned from this method can be used to ensure an object is exactly the same as another. 
	 * @return string. The generated hashcode for the object
	 */
	public function getHashCode() {
	
		// reutrn the md5 of the variable properties
		return md5(var_export($this, TRUE));
		
	}
	
	/**
	 * Returns the contents of the variable in a readable format
	 * @return The variable contents
	 */
	public function dump() {
	
		// Return the variable
		return var_export($this, true);
		
	}
	
	/**
	 * Returns the name of the current class. Late static binding is used to ensure derived classes return the correct name.
	 */
	public static function getName() {
		return get_called_class();
	}
	
}

?>