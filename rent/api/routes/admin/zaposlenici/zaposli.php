<?php

	$boot->setGet('admin/zaposlenici/zaposli', 'admin.zaposlenici.zaposli', function($args) use($boot){

		checkZaposlenik($boot);

		$db = $boot->db;
		$id = clean($args['id']);// potrebno ocistiti dani parametar(stavi u Bootstrap)

		$db->setTable('zaposlenici');
		$zaposlenik = $db->where(array([
			'field' => 'id',
			'rule'  => '=',
			'value' => $id

		]))
		->prvi();

		if($zaposlenik && $zaposlenik['active'] < 1){

			$db->set(array([
				'field' => 'active',
				'value' => '1'
			]
			),array([
				'field' => 'id',
				'rule'  => '=',
				'value' => $id
			]
			));

			$boot->flash('zaposlenik primljen natrag na posao');
		}
		else
		{
			$boot->flash('ovaj zaposlenik ne postoji u bazi podataka.');
		}

		$boot->redirect('admin/zaposlenici/all');		
	});