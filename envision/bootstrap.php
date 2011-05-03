<?php 

/*
 *****************************************
 * Define application variables
 *****************************************
 */

// Framework version
define('EV_VERSION', '0.1.0');

// Minimum PHP version required
define('MIN_PHP_VERSION', '5.3.0');

/*
 *****************************************
 * Initialise the engine
 *****************************************
 */

// First, we need access to some shared functions
require_once(FRAMEWORK_PATH.'shared.php');

// Next, we need to redirect any errors to envisions on error handling
set_error_handler("evErrorHandler");
set_exception_handler("evExceptionHandler");

// disable magic quotes
disableMagicQuotes();

// Start the session
session_start();

/*
 *****************************************
 * Additional system & environment checks.
 *****************************************
 */

// Verify PHP version
if (strnatcmp(PHP_VERSION, MIN_PHP_VERSION) < 0) {
	trigger_error('Your current PHP version is lower than the required minimum. Please ensure you have PHP version '.MIN_PHP_VERSION.' or later installed.', E_USER_ERROR);
}

generateClassManifest();

/*
 *****************************************
 * Handle the request
 *****************************************
 */

$ev = new EV_Envision();

// Distpatch our request
$dispatch = $ev->Router->Dispatch();

// render our template
$dispatch->Template->Render();

/*
 *****************************************
 * Finalise the request and cleanup
 *****************************************
 */

// Finish our response
ob_end_flush();