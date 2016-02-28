<?php

	$boot->setGet('korisnik/profil', 'korisnik.profil', function() use($boot){

		checkMember($boot);


		$db   = $boot->db;
		$clan = $boot->member;

		$db->setTable('rezervacija');
		$rezervacije = $db->whereJoin(array([
			'type' 		 => 'inner',
			'table' 	 => 'vozila',
			'joinField'  => 'id',
			'tableField' => 'vozila_id'	
		]), [
	 		'vozila.model as model',
	 		'rezervacija.id as rezId',
	 		'rezervacija.datum_rezervacija',
	 		'rezervacija.cijena_dan',
	 		'rezervacija.max_trajanje',
	 		'rezervacija.u_tijeku'
		],
		array([
			'field' => 'user_id',
			'rule'  => '=',
			'value' => $clan['id']
		],[
			'field' => 'active',
			'rule'  => '=',
			'value' => '1'
		]))
		->getAll();


		$klase 	  = getKlase($db);
		$zupanije = getZupanije($db);

		$boot->render('korisnik/profil.php', [
			'clan' 		  => $clan,
			'rezervacije' => $rezervacije,
			'klase'		  => $klase,
			'zupanije' 	  => $zupanije
		]);
	});


	$boot->setPost('korisnik/profil', 'korisnik.profil.post', function() use($boot){
		checkMember($boot);

		$v  = $boot->validate;
		$db = $boot->db;
			
		$username = Request::getPost('username');
		$ime 	  = Request::getPost('ime');
		$prezime  = Request::getPost('prezime');
		$oib 	  = Request::getPost('oib');
		$email 	  = Request::getPost('email');
		$zupanija = Request::getPost('zupanija');
		$telefon  = Request::getPost('telefon');	

	
		$v->validate([
			'username|korisničko ime' => [$username, 'min(3)|max(40)|uniqueUser(update)'],
			'ime'	   => [$ime, 'min(2)|max(40)'],
			'prezime'  => [$prezime, 'min(2)|max(40)'],
			'oib' 	   => [$oib, 'min(11)|max(11)|number'],
			'zupanija' => [$zupanija, 'min(1)'],
			'telefon'  => [$telefon, 'min(5)|max(50)|number'],
			'email'    => [$email, 'email']
		]);
		
		if($v->passes())
		{	

			try{


				$db->setTable('users');
				$db->set(array([ //trebalo bi pojednostaviti ovu funkciju
					'field' => 'username',
					'value' => $username
				], [
					'field' => 'ime',
					'value' => $ime
				],[
					'field' => 'prezime',
					'value' => $prezime
				],[
					'field' => 'oib',
					'value' => $oib
				],[
					'field' => 'zupanija_id',
					'value' => $zupanija
				],[
					'field' => 'tel_broj',
					'value' => $telefon
				],[
					'field' => 'email',
					'value' => $email
				]),
				array([
					'field' => 'id',
					'rule'  => '=',
					'value' => $boot->member['id']
				]));

				$boot->flash('update profila uspješan');
				$boot->redirect('korisnik/profil');	
			}
			catch(Exception $e){

				echo $e->getMessage();

				$boot->logError($e->getMessage());
			}	
		}
		else{

		//u slučaju da dani podaci nisu ispravni ponovo prikazujemo stranicu profila	
			$formErrors = ($v->errors());

			$klase 	  = getCarClasses($db);
			$zupanije = getZupanije($db);
			
			$boot->render('korisnik/profil.php',[
				'klase'	   => $klase,
				'zupanije' => $zupanije,
				'errors'   => $formErrors
			]);
		}
	});


	$boot->setGet('korisnik/ponistiRezervaciju', 'korisnik.ponistiRezervaciju', function($args) use($boot){

		checkMember($boot);

		$rezId = $args['id'];

		$db = $boot->db;

		$db->setTable('rezervacija');
		$db->where(array([
			'field' => 'id',
			'rule'  => '=',
			'value' => $rezId
		], [
			'field' => 'user_id',
			'rule'  => '=',
			'value' => $boot->member['id']
		]));
		$rezervacija = $db->prvi();

		if($rezervacija){

			try{
				$db->set(array([
					'field' => 'active',
					'value' => '0'
				]), 
				array([
					'field' => 'id',
					'rule'  => '=',
					'value' => $rezId
				]));


				$boot->flash('rezervacija poništena.');
				$boot->redirect('korisnik/profil');
			}
			catch(Exception $e){

				echo $e->getMessage();
			}
		}
		else{
			$boot->redirect('home', 'marš!');
		}
	});