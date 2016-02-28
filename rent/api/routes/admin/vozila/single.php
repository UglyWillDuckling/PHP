<?php

	$boot->setGet('admin/vozila/single', 'admin.vozila.single', function($args) use($boot){
		checkZaposlenik($boot);
	
		$id = $args['id'];
		$db = $boot->db;

		$where  = ['id' => $id];
		$vozilo = getVozila($db, null, $where)[0];


		$marke = getMarke($db);
		$klase = getKlase($db);
		$boje  = getBoje($db);
		
		$boot->render('admin/vozila/single.php',[
			'vozilo' => $vozilo,
			'title'  => 'vozilo',
			'marke'  => $marke,
			'klase'  => $klase,
			'boje'   => $boje
		]);
	});

//POST
	$boot->setPost('admin/vozila/single', 'admin.vozila.single.post', function() use($boot){
		checkZaposlenik($boot);
	
		$db = $boot->db;

		$model 	   = Request::getPost('model');
		$klasa     = Request::getPost('klasa');
		$marka 	   = Request::getPost('marka');
		$boja 	   = Request::getPost('boja');
		$snaga 	   = Request::getPost('snaga');
		$obujam    = Request::getPost('obujam');
		$klima     = Request::getPost('klima');
		$brojVrata = Request::getPost('vrata');
		$stanje    = Request::getPost('stanje');
		$na_lageru = Request::getPost('na_lageru');

		$id    = Request::getPost('id');
		$slika = Request::getFile('slika');


		$v = $boot->validate;

		$v->validate([
				'model' 	=> [$model, 'min(2)|required|max(40)'],
				'id'		=> [$id,  'required'],
				'klasa'		=> [$klasa, 'required|number'],
				'marka'		=> [$marka, 'required|number'],
				'snaga'		=> [$snaga, 'required|number'],
				'boja' 		=> [$boja, 'required|number'],
				'obujam'	=> [$obujam, 'required|number'],
				'klima'	 	=> [$klima, 'number|between(0, 1)'],
				'brojVrata|broj vrata' => [$brojVrata, 'required|number|between(2, 10)'],
				'stanje' 	=> [$stanje, 'number|between(0, 1)'],
				'na_lageru|broj vozila na lageru' => [$na_lageru, 'required|number|between(1, 100)'],
				'slika'		=> [$slika, 'image(1)']
		]);
		if($v->passes()){

			try{	

				$db->startTransaction();
				$db->setTable('vozila');
				$db->set(array([

					'field' => 'model',
					'value' => $model
				],[

					'field' => 'klasa_id',
					'value' => $klasa
				],[

					'field' => 'marka_id',
					'value' => $marka
				],[

					'field' => 'boja_id',
					'value' => $boja
				],[

					'field' => 'obujam_motora',
					'value' => $obujam
				],[

					'field' => 'snaga_motor',
					'value' => $snaga
				],[

					'field' => 'klima_uredaj',
					'value' => $klima
				],[

					'field' => 'broj_vrata',
					'value' => $brojVrata
				],[

					'field' => 'na_lageru_broj',
					'value' => $na_lageru
				],[

					'field' => 'stanje',
					'value' => $stanje
				]
				),array([
					'field' => 'id',
					'rule'  => '=',
					'value' => $id
				]));


				if($slika){

					$dest 	    = '/public/resources/slike/auti/' . $slika['name'];
					$slikaPath  = INC_FOLDER . $dest;
					$slikaUrl	= $dest;
				 		
					if(uploadImage($slika, $slikaPath)){

						$db->set(array([

							'field' => 'slika',
							'value' => $slikaUrl

						]),array([
							'field' => 'id',
							'rule'  => '=',
							'value' => $id
						]));				
					}
					else{
					//ako upload slike nije uspio poništavamo sve učinjene promijene
						$db->rollback();
						$boot->flash('greška prilikom promijene slike.');
						$boot->redirect("admin/vozila/single.id_{$id}");										
					}
				}//if slika
				$db->commit();	

				$boot->flash('podaci o vozilu uspješno promijenjeni.');
				$boot->redirect("admin/vozila/single.id_{$id}");
			}
			catch(Exception $e){

				echo $e->getMessage();
			}
		}
		else{

			$err = $v->errors();

			$boot->flash('Neispravno uneseni podaci.', 'danger');

			$marke  = getMarke($db);
			$klase  = getKlase($db);
			$boje   = getBoje($db);
			$vozilo = getVozila($db, null, $id)[0];
			
			$boot->render('admin/vozila/single.php',[
				'vozilo' => $vozilo,
				'title'  => 'vozilo',
				'marke'  => $marke,
				'klase'  => $klase,
				'boje'   => $boje,
				'errors' => $err
			]);
		}		
	});
