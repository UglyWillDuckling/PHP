<?php

	/**
	 * inicijalizacija polja zaposlenik, u slučaju da postoji 'adminId' u sessionu
	 *  dobavljamo informacije o zadanom zaposleniku i spremamo ih u
	 *  Bootstrapovo polje 'zaposlenik'
	 * @var [function]
	 */
	$adminAuth = function() use($boot){

		if($adminId = Request::getSession('adminId')){

			$db = $boot->db;

			$db->setTable('zaposlenici');
			$zaposlenik = $db->where(array([
				'field' => 'id',
				'rule'  => '=',
				'value' => $adminId
			]))
			->prvi();

			$boot->zaposlenik = $zaposlenik;
		}
		else{

			$boot->zaposlenik = null;
		}
	};