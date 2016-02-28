<?php
				
	$boot->setGet('admin/pages', 'admin.pages',function() use($boot){

		$db = $boot->db;

		$db->setTable('pages');
		try{
			$pages = $db->findAll()->getAll();

			$db->setTable('arguments');

			if(!$pages) $pages = array();
			foreach($pages as &$page)
			{
				$id = $page['id'];

				$db->where(array([
					'field' => 'page_id',
					'rule'  => '=',
					'value' => $id
				]));

				$args = $db->getAll() ?: array();			
				$page['args'] = $args;
			}

			$db->setTable('tables');
			$tablice = $db->findAll()->getAll();


			$db->setTable("fields");
			foreach($tablice as &$tablica)
			{
				
				$db->where(array([
					'field' => 'tableId',
					'rule'	=> '=',
					'value' => $tablica['id']
				]));

				$polja = $db->getAll();

				$tablica['fields'] = $polja;
			}

			$boot->render('admin/pages/pages.php',[
				'pages'  => $pages,
				'tables' => $tablice
			]);
		}
		catch(Exception $e){

			echo $e->getMessage();
		}

	}, 
	array(
		'admin', 
		'createCsrf'
	));


	$boot->setPost('admin/pages', 'post.admin.pages',function() use($boot){

		$page  = $_POST['page'];
		$slika = Request::getFile('slika');
		
		$page = json_decode($page);

		if($page)
		{	
		//varijabla koju saljemo natrag js-u
			$rez = array('success' => '1');


			$v = $boot->validate;
				
			$title	   = $page->title;
			$link  	   = $page->link;
			$argumenti = $page->arguments;


			$v->validate([
				'naslov'	=> [$title, 	'min(5)|required'],
				'link' 		=> [$link,    	'min(4)|required'],
				'slika' 	=> [$slika, 	'image(12)|required'],
				'argumenti' => [$argumenti, 'newArguments'],
			]);
				
			if($v->passes())
			{
				try{

					$pageId = $page->pageId;
				//link za upload slike
					$imgLink = $boot->baseUrl . "/public/resources/slike/pages/" . $slika['name'];
					$imgDestination = INC_FOLDER . "\\public\\resources\\slike\\pages\\" . $slika['name'];

					$db = $boot->db;					
					$db->setTable("pages");
					$db->startTransaction();

					if($pageId){
					//UPDATE(prvo updatamo tablicu sa stranicama a potom unosimo nove argumente)	
					
						$db->set(array([ //trebalo bi pojednostaviti ovu funkciju
							'field' => 'title',
							'value' => $title
						],[ 
							'field' => 'img',
							'value' => $imgLink
						],[ 
							'field' => 'link',
							'value' => $link
						]), array([
							'field' => 'id',
							'rule'  => '=',
							'value' => $pageId
						]));

						$db->query("DELETE FROM arguments WHERE page_id=" . $pageId);
					} 
					else{

					//INSERT				
						$db->add(array(
							'title' => $title,
							'img' 	=> $imgLink,
							'link'  => $link
						));
						$pageId = $db->lastInsertId();
					}

				//dodavanje argumenata
					$db->setTable('arguments');
					foreach($argumenti as $argument){	

						$db->add(array(
							'ime' 	   => $argument->name,				
							'trueName' => $argument->trueName,
							'req' 	   => $argument->req,
							'refTable' => $argument->refTable,
							'refField' => $argument->refField,
							'page_id'  => $pageId
						));
					}
				//ako je sve prošlo kako treba obavljamo uplaod slike na server	
					if( !uploadImage($slika, $imgDestination) )
					{
						throw new Exception("bad image upload!");
					}


					$db->commit();	

					$rez['msg'] = 'uspjeh';
					$rez['img'] = $imgLink;
				}
				catch(Exception $e){	
				 
					$db->rollback();

					$rez['success'] = '2';
					$rez['msg']     = $e->getMessage();
				}
			} else {

				$mistake = $v->errors()->first();			
							
				$rez['success'] = '2';
				$rez['msg']     = $mistake;
			}	

			echo json_encode($rez);
		}//if page		 
	}, 
	array(  	 //grupe kojima pripada ovaj route
		'admin', 
		'csrf'
	));