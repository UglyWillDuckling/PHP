<?php	

	$boot->setGet('admin/vozila/add', 'admin.vozila.add', function() use($boot){

		checkZaposlenik($boot);

		$db     = $boot->db;

		$marke  = getMarke($db);
		$klase  = getKlase($db);
		$boje   = getBoje($db);
		$razine = getRazine($db);


		$boot->render('admin/vozila/add.php',[
			'title'  => 'dodaj vozilo',
			'marke'  => $marke,
			'klase'  => $klase,
			'boje'   => $boje,
			'razine' => $razine
		]);
	});


	$boot->setPost('admin/vozila/add', 'admin.vozila.add.post', function() use($boot){
		checkZaposlenik($boot);

		//treba provjera za unique model	
		$db = $boot->db;

		$model 	   = Request::getPost('model');
		$klasa     = Request::getPost('klasa');
		$marka 	   = Request::getPost('marka');
		$razina	   = Request::getPost('razina');
		$boja 	   = Request::getPost('boja');
		$snaga 	   = Request::getPost('snaga');
		$obujam    = Request::getPost('obujam');
		$klima     = Request::getPost('klima');
		$brojVrata = Request::getPost('vrata');
		$stanje    = Request::getPost('stanje');
		$na_lageru = Request::getPost('na_lageru');

		$slika = Request::getFile('slika');

		$v = $boot->validate;

		$v->validate([
				'model' 	=> [$model, 'min(2)|required|max(40)'],
				'klasa'		=> [$klasa, 'required|number'],
				'marka'		=> [$marka, 'required|number'],
				'snaga'		=> [$snaga, 'required|number'],
				'razina'	=> [$razina, 'required|number'],
				'boja' 		=> [$boja, 'required|number'],
				'obujam'	=> [$obujam, 'required|number'],
				'klima'	 	=> [$klima, 'number|between(0, 1)'],
	 'brojVrata|broj vrata' => [$brojVrata, 'required|number|between(2, 10)'],
				'stanje' 	=> [$stanje, 'number|between(0, 1)'],
				'na_lageru' => [$na_lageru, 'required|number|between(1, 100)'],
				'slika'		=> [$slika, 'required|image(1)']
		]);
		if($v->passes()){

			try{	

				$ext  = pathinfo($slika['name'], PATHINFO_EXTENSION);
				$dest = '/public/resources/slike/auti/' . $model . "." . $ext;							
				$dest = clean( str_replace(' ', '', $dest) );

				$slikaUrl	= $dest;

				$db->startTransaction();
				$db->setTable('vozila');
				$db->add(array(
					'model' 		=> $model,
					'klasa_id' 		=> $klasa,
					'marka_id' 		=> $marka,
					'razina_id'		=> $razina,
					'boja_id' 		=> $boja,
					'obujam_motora' => $obujam,
					'snaga_motor' 	=> $snaga,
					'klima_uredaj'	=> $klima,
					'broj_vrata' 	=> $brojVrata,
					'na_lageru_broj'=> $na_lageru,
					'stanje' 		=> $stanje,
					'slika'  		=> $slikaUrl,
					'datum_upis'	=> date('Y-d-m', strtotime('today'))
				));

				$slikaPath  = INC_FOLDER . $dest;	 		
				if( uploadImage($slika, $slikaPath) )
				{
					$db->commit();

					$boot->flash('vozilo uspješno dodano.', 'success');
					$boot->redirect('admin/vozila/all');
					die;
				}
				else{
				//ako upload slike nije uspio poništavamo sve učinjene promijene
					$db->rollback();
					$boot->flash('došlo je do greške prilikom uploada slike.', 'red');
				}
			}
			catch(Exception $e){

				$boot->flash($e->getMessage(), 'red');		
			}

			$boot->redirect('admin/vozila/add');
		}
		else{

			$err = $v->errors();

			$marke  = getMarke($db);
			$klase  = getKlase($db);
			$boje   = getBoje($db);
			$razine = getRazine($db);

			$boot->render('admin/vozila/add.php',[
				'title'  => 'dodaj vozilo',
				'marke'  => $marke,
				'klase'  => $klase,
				'boje'   => $boje,
				'errors' => $err,
				'razine' => $razine
			]);
		}	
	});