<?php

class EV_Router extends EV_Object {

	/**
	 * Dispatches the current request to the relevant controller.
	 */
	public function Dispatch() {

		$routes = EV_Config::get('routes');
		
		// If the routes is null or not an array, throw exception
		if ($routes == null || !is_array($routes)) {
			trigger_error('Cannot find any valid routes within the configuration.', E_USER_ERROR);
		}
		
		// Get the orignal URL
		$originalUrl = (isset($_GET["url"]) ? $_GET["url"] : "/");
		
		// Create the variables to store the controller and action
		$controller = $action = null;
		
		// Create the query string array
		$queryString = array();
		
		// If the url is not set or empty or /, use the default controller
		if (!isset($originalUrl) || $originalUrl == "" || $originalUrl == "/") {

			// use the default controller.
			$controller = $routes["_default_controller"];
			$action = (array_key_exists("_default_action", $routes) ? $routes["_default_action"] : "index");
			
		} else {

			// Generate the routed URL
			$routedUrl = $originalUrl;
			
			if (preg_match('/^devel(.*)$/i', $routedUrl) === false) {
				
				$routedUrl = self::generateUrlRoute($originalUrl);
				
				// If there was no route found, then we need to set the 404
				if ($routedUrl == null) {
					$controller = $routes["_404_error"];
					$action = "index";
				}
			
			}
			
			// Explode the url array
			$urlArray = array();
			$urlArray = explode('/', $routedUrl);
			
			// Get the controller
			$controller = $urlArray[0];
			
			// remove controller
			array_shift($urlArray);
			
			// If there is an element at 0, this is the action
			if (isset($urlArray[0]) && strlen($urlArray[0]) > 0) {
				// set the action
				$action = $urlArray[0];
				
				// remove the action from the array
				array_shift($urlArray);
			} else {
				// Set the default action to index
				$action	= "index";
			}
			
			// Set any remaining url parts as the query string (parameters to be passed.
			$queryString = $urlArray;
			
		}
		
		// Create the controller class name
		$className = ucwords($controller).'Controller';
		$methodName = $action;
		
		// If the method exists, dispatch the route.
		if (method_exists($className, $methodName)) {
			
			// Create the dispatch object
			$dispatch = new $className($controller, $action);
			
			// Call the appropriate action methods
			call_user_func_array(array($dispatch, "OnBeforeAction"), $queryString);
			call_user_func_array(array($dispatch, $methodName), $queryString);
			call_user_func_array(array($dispatch, "OnAfterAction"), $queryString);
			
			// return our dispatch object
			return $dispatch;
			
		} else {
			
			// Show the 404 error
			// TODO: Dispatch to 404
			include(FRAMEWORK_PATH."sys_pages/404.php");
			exit();
			
			//exit("Cannot route URL. Controller does not exist");	
		}
		
		
	}
	
	/**
	 * Generates the route for the specified URL.
	 * @param string $url
	 */
	private static function generateUrlRoute($url) {

		// use the global routes
		$routes = EV_Config::get('routes');
		
		// If the routes is null or not an array, throw exception
		if ($routes == null || !is_array($routes)) {
			trigger_error('Cannot find any valid routes within the configuration.', E_USER_ERROR);
		}
		
		// Loop through the routes, skipping our "reserved" routes
		foreach($routes as $pattern => $route) {

			// Skip reserved routes
			if ($pattern == "_default_controller" || $pattern == "_default_action" || $pattern == "_404_error") {
				continue;	
			}
			
			// parse the pattern
			$pattern = self::parseUrlPattern($pattern);
						
			// If there is a match, set the replacements and return
			if (preg_match($pattern, $url)) {
				return preg_replace($pattern, $route, $url);
			}
			
		}
		
		// if we got to here, there was no route found, so we need to return null.
		return null;
		
	}
	
	/**
	 * Parses a route URL into a valid regex. This allows us to use keywords, and specify a default action if required.
	 * @param string $urlPattern
	 */
	private static function parseUrlPattern($urlPattern) {
		
		// Create the search and replacements
		$search = array('(:num)', '(:any)', '(:all)');
		$replacements = array('(\d+)', '([^\/]+)', '(.*)');
		
		// parse the pattern
		$pattern = '/'.str_replace($search, $replacements, $urlPattern).'/';
		
		// return the parsed pattern.
		return $pattern;		
		
	}
	
}