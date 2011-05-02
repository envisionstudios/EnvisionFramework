<?php

// Set the empty appError variable
$appError = '';

// Show detailed exception
$showException = false;

// If the session variable 
if (isset($_SESSION["APP_ERROR"])) {
	
	if (is_object($appError)) {
		
		// Show the exception.
		$showException = true;	
		
		// Set the appError variable
		$appError = $_SESSION["APP_ERROR"];	
		
	} else {
	
		// Set the appError variable
		$appError = $_SESSION["APP_ERROR"];
		
	}
	// Delete the error message from session
	unset($_SESSION["APP_ERROR"]);
}

?>

<html>
	
	<head>
		
		<title>An application error has occurred.</title>
		
		<style type="text/css">
			<!--
			
			body {margin: 0px; padding: 0px; background-color: #fff;}
			#error-container {background-color: #ffeded; width: 700px; margin: 0px auto; border: 1px solid #b06161;padding: 15px;margin-top: 50px;}
			#error-container h1 {font: bold 16px/18px Arial, Sans-serif; color: #a91f1f; margin: 0px; padding: 0px; text-align:center;}
			#error-container h2 {font: bold 13px/15px Arial, Sans-serif; color: #a91f1f; margin: 0px; padding: 0px; text-align:left;}
			#error-container p {font: normal 11px/13px Consolas, Verdana, monospace, sans-serif; color: #232323;}
			#error-container a {font: normal 11px/13px Consolas, Verdana, monospace, sans-serif; color: #793333;text-decoration: underline;}
			#error-container a:hover {text-decoration: none;}
			
			-->
		</style>
		
	</head>
	
	<body>
		
		<div id="error-container">
			
			<h1>An application error has occurred.</h1>	
			
			<?php
				if ($showException || is_object($appError)) {
			?>
			
				<h2>Error Message:</h2>
				<p>Error Code: <?php echo sprintf("%s - %s", $appError->getCode(), nl2br($appError->getMessage())); ?> </p>
			
				<h2>Stack Trace:</h2>
				<p>
				<?php
					
					if (method_exists($appError, "getPrevious")) {
				
						do {
							
							$stackTrace = sprintf("<b>%s:%d</b> %s (%d)\n", $appError->getFile(), $appError->getLine(), $appError->getMessage(), $appError->getCode());
							echo nl2br($stackTrace);
							
						} while ($appError == $appError->getPrevious());
						
					} else {
						
						$stackTrace = sprintf("<b>%s:%d</b> %s (%d)\n", $appError->getFile(), $appError->getLine(), $appError->getMessage(), $appError->getCode());
						echo nl2br($stackTrace);
						
					}
				?>
				</p>
				
			<?php
				} else {
			?>
			
			<p><?php echo nl2br($appError); ?></p>		
			
			<?php 
				}
			?>
			
			<p><a href="/">Return to homepage</a></p>
			
		</div>
		
	</body>
	
</html>