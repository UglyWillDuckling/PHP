<?php


	$boot->SetGet('location', 'location', function() use($boot){

		$db = $boot->db;

		$db->setTable('location');
		$db->findAll();
		$lokacije = $db->getAll();

		$boot->render('location.php',[
			'lokacije' => $lokacije
		]);
	});