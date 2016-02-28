<?php


	$boot->setPost('ajax/getArgs', 'post.ajax.getArgs', function() use($boot){


		 $pageId = Request::getPost('id');
		if($pageId && ctype_digit($pageId)){

			$db = $boot->db;			
			$db->setTable('arguments');

			$db->where(array([
				'field' => 'page_id',
				'rule'  => '=',
				'value' => $pageId
			]));

			$args = $db->getAll();

			if($args){

				foreach($args as &$arg)
				{

				$comm = "SELECT naziv FROM tables WHERE id=" . $arg['refTable'];

				$stmt = $db->query($comm);

				$table = $stmt->fetch(PDO::FETCH_ASSOC);

				$tableName = $table['naziv'];


				$sql = "SELECT id, " . $arg['trueName'] . " as refField FROM " . $tableName;

				$argData = $db->query($sql);
				if($argData)
					$arg['argData'] = $argData->fetchAll(PDO::FETCH_ASSOC);
				else
					$arg['argData'] = array();
				}
			} else {

				$args = array();
			}	
					
			echo json_encode($args);
		}
	}, array('admin', 'csrf'));
