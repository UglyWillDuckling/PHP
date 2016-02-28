<?php

	require INC_FOLDER . "/api/routes/home/home.php";

	require INC_FOLDER . "/api/routes/lokacije/location.php";
	
	require INC_FOLDER . "/api/routes/vozila/all.php";
	require INC_FOLDER . "/api/routes/vozila/single.php";

	require INC_FOLDER . "/api/routes/korisnik/profil.php";
	
	require INC_FOLDER . "/api/routes/auth/login.php";
	require INC_FOLDER . "/api/routes/auth/logout.php";
	require INC_FOLDER . "/api/routes/auth/register.php";
	
	
	require INC_FOLDER . "/api/routes/admin/home.php";

	require INC_FOLDER . "/api/routes/admin/auth/login.php";
	require INC_FOLDER . "/api/routes/admin/auth/logout.php";

	require INC_FOLDER . "/api/routes/admin/zaposlenici/all.php";
	require INC_FOLDER . "/api/routes/admin/zaposlenici/otpusti.php";
	require INC_FOLDER . "/api/routes/admin/zaposlenici/zaposli.php";
	require INC_FOLDER . "/api/routes/admin/zaposlenici/uredi.php";
	require INC_FOLDER . "/api/routes/admin/zaposlenici/add.php";

	require INC_FOLDER . "/api/routes/admin/rezervacije/rezervacije.php";

	require INC_FOLDER . "/api/routes/admin/vozila/add.php";
	require INC_FOLDER . "/api/routes/admin/vozila/all.php";
	require INC_FOLDER . "/api/routes/admin/vozila/single.php";
	require INC_FOLDER . "/api/routes/admin/vozila/remove.php";
	
	require INC_FOLDER . "/api/routes/admin/main_page/mainPage.php";
	require INC_FOLDER . "/api/routes/admin/pages/pages.php";


	require INC_FOLDER . "/api/routes/ajax/getArgs.php";
	require INC_FOLDER . "/api/routes/ajax/getPages.php";
	require INC_FOLDER . "/api/routes/ajax/deletePage.php";
	require INC_FOLDER . "/api/routes/ajax/getTables.php";
