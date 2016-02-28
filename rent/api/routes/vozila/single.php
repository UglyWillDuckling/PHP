<?php

	$boot->setGet('vozila/single', 'vozila.single', function($args) use($boot){

			$db = $boot->db;
			$id = $args['id'];

			$where  = ['id' => $id];
			$vozilo = getVozila($db, null, $where)[0];

			if($vozilo && $vozilo['naLageru'] > 0){
				try{
					
					$klase = getKlase($db);
					//probaj inner join
					$cijena = getCijena($db, $vozilo['klasa_id'], $vozilo['razina_id'], $vozilo['klasa_cijena']);
	
					$boot->render('vozila/single.php', [
						'vozilo' => $vozilo,
						'cijena' => $cijena,
						'klase'  => $klase
					]);	
	
				}
				catch(Exception $e){
	
					echo $e->getMessage();
				}
			}
			else{

				$boot->flash('ovo vozilo ne postoji.', 'red');
				$boot->redirect('home');
			}
	});

	$boot->setPost('vozila/single', 'vozila.single.post', function() use($boot){
			checkMember($boot);
			

			$db = $boot->db;

			$id    = Request::getPost('carId');
			$datum = date( 'Y-m-d', strtotime(Request::getPost('datum')) );

	
			$v = $boot->validate;

			$v->validate([
				'id' 	=> [$id, 'required|int'],
				'datum' => [$datum, 'required|date|futureDate']
			]);

			if($v->passes()){	

				$where  = ['id' => $id];
				$vozilo = getVozila($db, null, $where)[0];

				if($vozilo && $vozilo['naLageru'] > 0){
					try{

						$cijena = getCijena($db, $vozilo['klasa_id'], $vozilo['razina_id'], $vozilo['klasa_cijena']);

						$db->startTransaction();
						$db->setTable('rezervacija');
						$db->add(array(
							'user_id' 		=> $boot->member['id'],
							'vozila_id'		=> $id,
							'cijena_dan' 	=> $cijena,
							'datum_rezervacija' => $datum
						));

						$sql = 'UPDATE vozila 
								SET na_lageru_broj = na_lageru_broj - 1
								WHERE id=' . $id
						;
						$db->query($sql);
						$db->commit();


						$boot->flash('rezervacija uspješno ostvarena');
					}
					catch(Exception $e){

						$boot->flash($e->getMessage(), 'red');
						$boot->redirect('home');
					}	
				}
				else
				{
					$boot->flash('ovo vozilo ne postoji.', 'red');		
				}
			}
			else{

				$err = $v->errors();

				flash($err->first());
			}

			$url = 'vozila/single.id_' . $id;
			$boot->redirect($url);
	});