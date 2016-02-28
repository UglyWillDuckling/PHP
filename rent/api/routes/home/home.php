<?php
	
	$boot->setGet('home','home', function() use($boot){
		
			$db = $boot->db;	
			
			$klase 	   = getCarClasses($db);
			$novi_auti = getNewCars($db);

			$db->setTable('ponude');

			$offers =[
				["title" => "novo peugeot 9999434343",
				 "link"  => "vozila/single.id_10",
				 "pic"   => "public/resources/slike/auti/Opel_Corsa.jpg"
				],
			];
			/* $db->query(
			   'SELECT 
				 ponude.pic,
				 ponude.page, 
				 ponude.id, 
				 ponude.title, 
				 pages.link as link
				FROM ponude
				INNER JOIN pages 
				ON ponude.page = pages.id			
			')
			->fetchAll(PDO::FETCH_ASSOC);
			*/

			$boot->render('home.php', [
				'title'   => 'home',
				'klase'   => $klase,
				'newCars' => $novi_auti,
				'offers'  => $offers
			]);
	}, array('ebolaTestGroup'));		
