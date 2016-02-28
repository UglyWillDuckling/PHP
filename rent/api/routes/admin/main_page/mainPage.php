<?php

	$boot->setGet('admin/mainPage', 'admin.mainPage', function() use($boot){
	
		$msg = array();
		$db = $boot->db;

		$db->setTable('ponude');
		$offers = $db->query(
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


		$db->setTable('pages');
		$pages_sql = 
		    "SELECT p.id as pageId, p.title as pageTitle, link as pageLink
			 FROM pages as p
		";

		$pages = $db->query($pages_sql);
		$pages = $pages->fetchAll(PDO::FETCH_ASSOC);


		$db->setTable('arg_values');
		for($i=0; $i < sizeof($offers); $i++){

			$offerId = $offers[$i]['id'];

			$args = $db->query(
				'SELECT 
					argV.value 		as value,
					args.ime   	    as argName,
					args.req   	    as req,
					args.trueName   as trueName,	
					args.refTable   as refTable, 
					args.refField   as refField,
					args.id 		as argId
				 FROM arg_values argV
				 INNER JOIN arguments as args
				 ON argV.argumentId = args.id 
				 WHERE offerId = ' . $offerId
			); 

			$args = $args->fetchAll(PDO::FETCH_ASSOC);

			if($args)
				$offers[$i]['arguments'] = $args;
			else
				$offers[$i]['arguments'] = array();

			foreach($offers[$i]['arguments'] as &$argument)
			{

				$sql = 'SELECT 
						  id, ' 
						. $argument['refField'] . ' as refField'
						. ' FROM ' 
						. $argument['refTable'] 
				;

				$argData = $db->query($sql);

				if($argData)
					$argument['argData'] = $argData->fetchAll(PDO::FETCH_ASSOC);
			}
		}

		$boot->render('admin/mainPage/mainPage.php', [
			'offers' => $offers,
			'pages'  => $pages
		]);
	}, array('admin', 'createCsrf'));



	$boot->setPost('admin/mainPage', 'post.admin.mainPage', function() use($boot){

		$offers = array();
		foreach($_POST as $key => $offer){

			$offer = json_decode($offer);
	
			$offers[] = $offer;		
		}

		$i=0;
		while( $i < sizeof($offers) )
		{

			$slika = Request::getFile('slika' . $i);
			$offer = $offers[$i];
			
			
			$offer->slika = $slika;
		//	preDisplay($offer);

			$i++;
		}

	//VALIDACIJA
		$v = $boot->validate;

		$vRules = array();
		$i = 0;
		foreach($offers as $offer){

			$title 	  = 'title' + $i;
			$slikaIme = 'slika' . $i;
			$page 	  = 'page'  . $i;
			$args	  = 'arguments'	. $i;


			$argumenti = array();
			foreach($offer->argumenti as $argument)
			{
				$argumenti['arg' . $i] = [
					'name'  => $argument[0],
					'value' => $argument[1],
					'req'   => $argument[2]
				];
			}				

			$vRules[$title]    = [$offer->title, 'min(5)|required|max(50)'];
			$vRules[$slikaIme] = [$offer->slika, 'required|image(10)'];
			$vRules[$page] 	   = [$offer->page , 'required|number'];
			$vRules[$args] 	   = [$argumenti   , 'argumenti'];

			$i++;
		}

		$v->validate($vRules);

		if($v->passes()){

			$db = $boot->db;

			$db->setTable('ponude');
			$db->startTransaction();

			$db->query('delete from ponude');
			$db->query('delete from arg_values');
			
			try{		
				foreach ($offers as $offer) 
				{
					
					$slika   = $offer->slika;
					$pic 	 = "public/resources/slike/main_page/" . $slika['name']; 
					
					$db->setTable('ponude');
					$ponuda = [
						'title' => $offer->title,
						'pic'	=> $pic,
						'page'  => $offer->page,
					];				
					$db->add($ponuda);
					
					if( !empty($offer->argumenti) ){

						$db->setTable('arg_values');
						$offerId = $db->lastInsertId();
						
						foreach($offer->argumenti as $argument){

							$arg = [
								'offerId'    => $offerId,
								'value'      => $argument[1],
								'argumentId' => $argument[3]
							];

							$db->add($arg);
						}								
					}
				//upload slike						
					$u = uploadImage($slika, $pic);
				
					if(!$u)
					{
						throw new Exception("pogreska prilikom uploada datoteke.");
					}
				}

				$db->commit();

				$msg = [
					'uspjeh' => '1',
					'poruka' => 'uspjesan update'
				];
			} 
			catch( Exception $e ){ 

				$db->rollback();

				foreach($offers as $offer){

					$slika = $offer->slika;
					$pic = "public/resources/slike/main_page/" . $slika['name']; 

					unlink($pic);				
				}

				$msg = [
					'uspjeh' => '2',
					'poruka' => $e->getMessage()
				];
			}
		}//if valid 
		else {

			$msg = [
					'uspjeh' => '2',
					'poruka' => $v->errors()->first()
			];
		}

		echo json_encode($msg);	
	},array(
		'admin',
		'csrf'
	));

