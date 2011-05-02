<?php

class FrameworkException extends Object
{
	
	/**
	 * @var string message
	 * The error message.
	 */
	private $message;
	
	/**
	 * @var int code
	 * The numerical error code.
	 */
	private $code;
	
	/**
	 * @var string file
	 * The path to the script that caused the error.
	 */
	private $file;
	
	/**
	 * @var int line
	 * The line in the file that caused the error.
	 */
	private $line;
	
	/**
	 * Creates a new BaseException object.
	 * @param string $message
	 * The error message.
	 * @param int $code
	 * The numerical error code.
	 * @param string $file
	 * The path the file that caused the error.
	 * @param int $line
	 * The line in the file that caused the error.
	 */
	public function __construct($message, $code, $file, $line) {
	
		// Set the inital properties of the BaseException
		$this->message = $message;
		$this->code = $code;
		$this->file = $file;
		$this->line = $line;
		
	}
	
	/**
	 * Gets the error message property.
	 * @return string
	 */
	public function getMessage() {
		return $this->message;
	}
	
	/**
	 * Gets the error code property.
	 * @return int
	 */
	public function getCode() {
		return $this->code;
	}
	
	/**
	 * Gets the error file property.
	 * @return string
	 */
	public function getFile() {
		return $this->file;
	}
	
	/**
	 * Gets the error line property.
	 * @return int
	 */
	public function getLine() {
		return $this->line;
	}
	
}

?>