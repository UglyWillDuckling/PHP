<?php
	
	$boot->setGet('auth/register','auth.prijava', function() use($boot){
		
		if(!$boot->member){
			
			$db = $boot->db;
			
			$klase 	  = getCarClasses($db);		
			$zupanije = getZupanije($db);

			$boot->render('auth/register.php',[
				'klase'	   	   => $klase,
				'zupanije' 	   => $zupanije,
				'title'   	   => 'register'
			]);
		}
		else{
			
			$boot->redirect('home', 'vec ste prijavljeni');
		}
	}, array('createCsrf'));	
	


	$boot->setPost('auth/register','auth.register.post', function() use($boot){
		
		if(!$boot->member){
			
			$v  = $boot->validate;
			$db = $boot->db;
			
			$username = Request::getPost('username');
			$password = Request::getPost('password');
			$password_confirm = Request::getPost('password_confirm');
			$ime 	  = Request::getPost('ime');
			$prezime  = Request::getPost('prezime');
			$oib 	  = Request::getPost('oib');
			$email 	  = Request::getPost('email');
			$zupanija = Request::getPost('zupanija');
			$telefon  = Request::getPost('telefon');						
			
			
			$v->validate([
				'username|korisničko ime' => [$username, 'required|min(3)|max(40)|uniqueUser(create)'],
				'ime'	   => [$ime, 'min(2)|required|max(40)'],
				'prezime'  => [$prezime, 'required|min(2)|max(40)'],
				'password|lozinka' => [$password, 'required|min(6)'],
				'password_confirm' => [$password_confirm, 'required|matches(password)'],
				'oib' 	   => [$oib, 'required|min(11)|max(11)|number'],
				'zupanija' => [$zupanija, 'required|min(1)'],
				'telefon'  => [$telefon, 'min(5)|max(50)|number'],
				'email'    => [$email, 'email|required']
			]);
			
			if($v->passes())
			{			
			//unos korisnika u bazu podataka			
				$password_hash = Hash::password($password, $boot->config['password']['algo']);
				
				$db->setTable('users');
				$db->add(array(
					'username' => $username,
					'ime'	   => $ime,
					'prezime'  => $prezime,
					'password' => $password_hash,
					'oib' 	   => $oib,
					'zupanija_id' => $zupanija,
					'tel_broj' => $telefon,
					'email'	   => $email
				));

				$boot->redirect(
					'home', 
					'uspješno ste se registrirali, čestitamo. Sada se mozete prijaviti'
				);				
			}
			else{
				
			//u slučaju da dani podaci nisu ispravni renderiramo stranicu za registraciju	
				$formErrors = ($v->errors());
	
				$klase 	  = getCarClasses($db);
				$zupanije = getZupanije($db);
				
				$boot->render('auth/register.php',[
					'klase'	   => $klase,
					'zupanije' => $zupanije,
					'errors'   => $formErrors
				]);
			}
		}
		else
		{			
			$boot->redirect('home', 'zabranjen pristup');
		}			
	}, array('csrf', 'createCsrf'));
