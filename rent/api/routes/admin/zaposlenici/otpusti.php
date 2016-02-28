<?php

	$boot->setGet('admin/zaposlenici/otpusti', 'admin.zaposlenici.otpusti', function($args) use($boot){

		checkZaposlenik($boot);

		$db = $boot->db;
		$id = clean($args['id']);// potrebno ocistiti dani parametar

		$db->setTable('zaposlenici');
		$zaposlenik = $db->where(array([
			'field' => 'id',
			'rule'  => '=',
			'value' => $id

		]))
		->prvi();

		if($zaposlenik && $zaposlenik['admin_level'] < 2){

			$db->set(array([
				'field' => 'active',
				'value' => '0'
			]
			),array([
				'field' => 'id',
				'rule'  => '=',
				'value' => $id
			]
			));

			$boot->flash('zaposlenik otpusten.');
		}
		else{
			$boot->flash('nemate pravo otpustiti ovog zaposlenika.');
		}

		$boot->redirect('admin/zaposlenici/all');
		
	});