<?php

	$start_session = function() use($boot){
		
		session_start();
		//session_cache_limiter(false);
	};
	