<?php

	spl_autoload_register( function($class){

		$put = "C:/Apache24/htdocs/youbito/php/class/";
		require_once($put . $class . ".php");
		}
	);

						
?>
