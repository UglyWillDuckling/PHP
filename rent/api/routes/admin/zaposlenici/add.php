<?php


	$boot->setPost('admin/zaposlenici/add', 'admin.zaposlenici.add.post', function() use($boot){

		checkZaposlenik($boot);


		$db = $boot->db;
		$db->setTable('zaposlenici');	

		$ime 	   = Request::getPost('ime');
		$prezime   = Request::getPost('prezime');
		$oib 	   = Request::getPost('oib');
		$email 	   = Request::getPost('email');
		$mjesto_id = Request::getPost('mjesto');
		$telefon   = Request::getPost('telefon');
		$adresa    = Request::getPost('adresa');
		$username  = Request::getPost('username');
		$password  = Request::getPost('password');

		$slika = Request::getFile('slika');

		$v = $boot->validate;
		$v->validate([
				'ime'	    => [$ime, 	   'min(2)|required|max(40)'],
				'prezime'   => [$prezime,  'required|min(2)|max(40)'],
				'password'	=> [$password, 'required|min(5)'],		
				'oib' 	    => [$oib, 	   'required|min(11)|max(11)|number'],
				'mjesto_id' => [$mjesto_id,'required'],
				'telefon'   => [$telefon,  'min(5)|max(50)|number'],
				'adresa'	=> [$adresa,   'min(10)'],
				'email'     => [$email,    'email|required'],
		'username|korisničko ime'  => [$username, 'required|uniqueZaposlenik|min(3)|max(50)'],
				'slika'		=> [$slika,	   'required|image(200000)']
		]);
	
		if($v->passes()){

			$dest = '/public/resources/slike/zaposlenici/' . $slika['name'];

			$slikaPath  = INC_FOLDER . $dest;

		/**** TREBA MAKNUTI BASE_URL ****/	
			$slikaUrl	= BASE_URL   . $dest;

			$password_hash = Hash::password($password, $boot->config['password']['algo']);
		//započinjemo transakciju tako da možemo provjeriti uspješnost uploada slike
			$db->startTransaction();
			$db->add([
				'username' => $username,
				'ime'	   => $ime,
				'prezime'  => $prezime,
				'password' => $password_hash,
				'oib' 	   => $oib,
				'mjesto_id'=> $mjesto_id,
				'telefon'  => $telefon,
				'email'	   => $email,
				'adresa'   => $adresa,
				'slika'    => $slikaUrl	
			]);


	/************ UPLOAD SLIKE preko funkcije uploadImage ***********/
		 		
			if(uploadImage($slika, $slikaPath, $username)){

				$db->commit();

				$boot->flash('novi korisnik je uspjesno dodan');
				$boot->redirect('admin/zaposlenici/all');
			}
			else
			{
			//ako upload slike nije uspio poništavamo sve učinjene promijene
				$db->rollback();
			}		
		}		

		$mjesta 	 = getMjesta($db);
		$zaposlenici = getZaposlenici($db);

		$errors = $v->errors();
		$boot->render('admin/zaposlenici/all.php',[
			'errors'	  => $errors,
			'mjesta' 	  => $mjesta,
			'zaposlenici' => $zaposlenici
		]);	
	});

	
