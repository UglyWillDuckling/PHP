<?php

	$boot->setGet('admin/zaposlenici/all', 'admin.zaposlenici.all', function() use($boot){

		checkZaposlenik($boot);
		
		if($boot->zaposlenik['admin_level'] > 1){

			$db = $boot->db;

			$db->setTable('zaposlenici');
			$radnici = $db->findAll()->getAll();
			
			$mjesta = getMjesta($db);
			$boot->render("admin/zaposlenici/all.php", [
				'zaposlenici' => $radnici,
				'mjesta'	  => $mjesta
			]);
		}
		else
		{
			$boot->redirect('admin/home', 'nemate pristup');
		}
		
	});