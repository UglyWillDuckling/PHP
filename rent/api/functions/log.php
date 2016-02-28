<?php  

	function logError($err){

		error_log($err . PHP_EOL, 3, ERROR_FILE);
	}	
