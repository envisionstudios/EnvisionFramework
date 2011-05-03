<?php

class EV_FrameworkException extends Exception
{
	
	/**
	 * @var string message
	 * The error message.
	 */
	protected $message;
	
	/**
	 * @var int code
	 * The numerical error code.
	 */
	protected $code;
	
	/**
	 * @var string file
	 * The path to the script that caused the error.
	 */
	protected $file;
	
	/**
	 * @var int line
	 * The line in the file that caused the error.
	 */
	protected $line;
	
	/**
	 * Creates a new FrameworkException object.
	 * @param string $message
	 * The error message.
	 * @param int $code
	 * The numerical error code.
	 * @param string $file
	 * The path the file that caused the error.
	 * @param int $line
	 * The line in the file that caused the error.
	 */
	public function __construct($message, $code = null, $file = null, $line = null) {
	
		parent::__construct($message, $code, null);
		
		// Set the inital properties of the BaseException
		$this->message = $message;
		$this->code = $code;
		$this->file = $file;
		$this->line = $line;
		
	}
	
}

?>