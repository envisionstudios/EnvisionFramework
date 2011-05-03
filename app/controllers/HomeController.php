<?php

class HomeController extends EV_Controller {

	function Index() {

		CodeSnippet::getAll();
		
	}
	
}