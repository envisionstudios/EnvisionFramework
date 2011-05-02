<?php

/*
 *****************************************
 * URI Routes
 *****************************************
 *
 * You can define your application specific routes here
 * 
 * Important Notes: 
 * 
 * Routes are used in the order that they are found. Once a route has been found,
 * the remaining routes are essentially ignored. 
 * 
 * It is advised to always keep the default class/method route as a 
 * fallback route.
 *
 */

// Create the default controller/action (class/method) route.
$_config['routes']['(:any)\/(:any)'] = "$1/$2";
$_config['routes']['(:any)'] = "$1";

/*
 * While the default route above should remain, the reserved routes below MUST remain.
 * The values of the routes however can change to suit your application requirements.
 * 
 * If you wish, you can specify a _default_action action, however this is not required.
 * If the _default_action is not specified, Index() will be used.
 * 
 */
$_config['routes']["_default_controller"] = "home";
$_config['routes']["_404_error"] = "";
$_config['routes']["_devel/(:any)"] = "devel/$1";