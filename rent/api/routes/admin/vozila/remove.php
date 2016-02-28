<?php


	$boot->setGet('admin/vozila/remove', 'admin.vozila.remove', function($args) use($boot){

		checkZaposlenik($boot);

		$id = $args['id'];

		$db = $boot->db;

		try{
			removeVozilo($db, $id);
			$boot->flash('automobil maknut iz prodaje.');
		}
		catch(Exception $e){

			$boot->flash($e->getMessage());
		}	

		$boot->redirect('admin/vozila/all');

	});