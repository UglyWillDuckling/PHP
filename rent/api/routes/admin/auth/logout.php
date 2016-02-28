<?php

	$boot->setGet('admin/auth/logout', 'admin.auth.logout', function() use($boot){

		if($boot->zaposlenik)
		{
			unset($_SESSION['adminId']);
			session_destroy();

			$boot->redirect('home');
		}
		else{

			$boot->redirect('home', 'Ma koji sad vrag!');
		}
	});