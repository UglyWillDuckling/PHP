<?php

	$boot->setPost('admin/ajax/getTables', 'post.admin.ajax.getTables', function() use($boot){

		$db = $boot->db;


		try{

			$db->setTable('tables');

			$db->findAll();
			$tables = $db->getAll();


			foreach($tables as &$table)
			{

				$sql = "SELECT * FROM fields WHERE tableId = " . $table['id'];
				$stmt = $db->query($sql);

				$table['fields'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
			}

			echo json_encode($tables);
		} 
		catch(Exception $e){

			error_log($e->getMessage, 3, ERROR_FILE);
		}
	}, array('admin', 'csrf'));