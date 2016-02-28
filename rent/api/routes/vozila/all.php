<?php

	$boot->setGet('vozila/all', 'vozila.all', function($args=null) use($boot){

		$db=$boot->db;

		$gdje = null;

		//$gdje = 'na_lageru_broj';
		if($args)
		{
			$klasa = $args['klasa'];
			$gdje = [
				'klasa_id' => $klasa
			];
		}

		$vozila = getVozila($db, null, $gdje, true);

		$marke = getMarke($db);
		$klase = getKlase($db);

		$boot->render('vozila/all.php',[

			'vozila' => $vozila,
			'klase'  => $klase,
			'marke'	 => $marke,
			'gdje'	 => $gdje
		]);
	});

	$boot->setPost('vozila/all', 'vozila.all.post', function() use($boot){

		$db = $boot->db;
		
		$poredak = array();

		$poredak['klasa_id']   = Request::getPost('klasa');
		$poredak['marka_id']   = Request::getPost('marka');
		$poredak['stanje'] 	   = Request::getPost('stanje');
		$poredak['tip_motora'] = Request::getPost('motor');

		$gdje = array();
		foreach($poredak as $field => $value)
		{
			if($value !== null) $gdje[$field ]=$value;									
		}

		try{

			$vozila = getVozila($db, null, $gdje, true);

			$marke = getMarke($db);
			$klase = getKlase($db);

			$boot->render('vozila/all.php',[

				'vozila' => $vozila,
				'klase'  => $klase,
				'marke'	 => $marke,
				'gdje'	 => $gdje
			]);

		}
		catch(Exception $e){

			echo $e->getMessage();
		}
	});