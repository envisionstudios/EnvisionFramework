<?php

/*
 *****************************************
 * Application paths
 *****************************************
 */

/*
 * Define the root path for the framework.
 */
define('ROOT_PATH', dirname(__FILE__));

/*
 * Define the framework path. 
 * -- REQUIRES TRAILING SLASH --
 */
define ('FRAMEWORK_PATH', ROOT_PATH.'/envision/');

/*
 * Define the application path. 
 * -- REQUIRES TRAILING SLASH --
 */
define ('APP_PATH', ROOT_PATH.'/app/');

/*
 *****************************************
 * System pre-check
 *****************************************
 */

/* 
 * Ensure application and framework folders are correct
 */
 
// check framework path
if (!is_dir(FRAMEWORK_PATH)) {
	exit("Your framework directory does not appear to exist.");	
}

// check application path
if (!is_dir(APP_PATH)) {
	exit("Your application directory does not appear to exist.");	
}

/*
 *****************************************
 * Bootstrapper 
 *****************************************
 */
require_once(FRAMEWORK_PATH.'bootstrap.php');