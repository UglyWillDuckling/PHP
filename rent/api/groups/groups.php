<?php

	/**
	 * Ovdje postavljamo grupe routova sa njihovim pripadajucim funkcijama
	 */


	/**
	 * funkcija za provjeru valjanosti tokena
	 * @var [function]
	 */
	$checkCsrf = function() use($boot){

		$sessionToken = Request::getSession('csrfToken');
		$postToken    = Request::getPost('csrfToken');

		if(!$sessionToken || !$postToken || $sessionToken != $postToken)
		{	
			$boot->flash(' bugger off Mate!!! ', 'red');
			$boot->redirect('home');
		} else {
			unset($_POST['csrfToken']);
		}
	};
	$boot->setGroup( 'csrf', array($checkCsrf) );


		/***********CREATE CSRF**************/

	$createCsrf = function() use($boot){

	//treba doraditi	
		$rand  = $boot->random;
		$token = hash( 'sha256', $rand->generateString(128) );

		$boot->csrfToken 	   = $token; //ovu varijablu stavljamo u 'form'
		$_SESSION['csrfToken'] = $token;
	};
	$boot->setGroup( 'createCsrf', array($createCsrf) );

//grupa admin
	$adminCheck = function() use($boot){

		if(!$boot->zaposlenik)
			return $boot->redirect('home', 'niste administrator');
	};
	$boot->setGroup( 'admin', array($adminCheck) );


	

