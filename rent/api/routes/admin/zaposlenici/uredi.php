<?php

	$boot->setGet('admin/zaposlenici/uredi', 'admin.zaposlenici.uredi', function($args) use($boot){

		checkZaposlenik($boot);

		$db = $boot->db;
		$id = clean($args['id']);

		$db->setTable('zaposlenici');
		$zaposlenik = $db->where(array([
			'field' => 'id',
			'rule' => '=',
			'value' => $id
		]))
		->prvi();


		$mjesta = getMjesta($db);
		if($zaposlenik){

			$boot->render('admin/zaposlenici/uredi.php', [
				'zaposlenik' => $zaposlenik,
				'mjesta'	 => $mjesta	
			]);
		}

	});

	$boot->setPost('admin/zaposlenici/uredi', 'admin.zaposlenici.uredi.post', function($args) use($boot){

		$db = $boot->db;
		$db->setTable('zaposlenici');

		$id = clean($args['id']);	

		$ime 	   = Request::getPost('ime');
		$prezime   = Request::getPost('prezime');
		$oib 	   = Request::getPost('oib');
		$email 	   = Request::getPost('email');
		$mjesto_id = Request::getPost('mjesto');
		$telefon   = Request::getPost('telefon');
		$adresa    = Request::getPost('adresa');


		$v = $boot->validate;
		$v->validate([
				'ime'	    => [$ime, 'min(2)|required|max(40)'],
				'prezime'   => [$prezime, 'required|min(2)|max(40)'],			
				'oib' 	    => [$oib, 'required|min(11)|max(11)|number'],
				'mjesto_id' => [$mjesto_id, 'required'],
				'telefon'   => [$telefon, 'min(5)|max(50)|number'],
				'adresa'	=>	[$adresa, 'min(10)'],
				'email'     => [$email, 'email|required']
		]);
	
		if($v->passes()){

			$db->set(array([
				'field' 		=> 'ime',
				'value' 		=> $ime
			],[
				'field' 		=> 'prezime',
				'value' 		=> $prezime
			],[
				'field' 		=> 'oib',
				'value' 		=> $oib
			],[
				'field' 		=> 'mjesto_id',
				'value' 		=> $mjesto_id
			],[
				'field' 		=> 'telefon',
				'value' 		=> $telefon
			],[

				'field' 		=> 'email',
				'value' 		=> $email
			]),array([
			'field' => 'id',
			'rule'  => '=',
			'value' => $id
			]));

			$boot->flash('korisnički podaci promijenjeni.');
			$boot->redirect('admin/zaposlenici/uredi.id_' . $id);
		}
		else{

			echo "failed";
		}


	});