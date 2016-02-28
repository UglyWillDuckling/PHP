<?php
	
	require INC_FOLDER . "/api/Middleware/start_session/start_session.php";
	require INC_FOLDER . "/api/Middleware/auth/auth.php";
	require INC_FOLDER . "/api/Middleware/auth/admin.php";

//postavljanje funkcija koje će se pozivati prije pozivanja routa
	$boot->setBeforeCall($start_session);
	$boot->setBeforeCall($auth);
	$boot->setBeforeCall($adminAuth);
	