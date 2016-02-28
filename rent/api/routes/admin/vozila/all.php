<?php

	$boot->setGet('admin/vozila/all', 'admin.vozila.all', function($args=null) use($boot){

		checkZaposlenik($boot);


		$order = $args['order'] ?: null;
		if($order == 'naLageru')
		{
			$order = 'na_lageru_broj';
		}
		

		$poredak = (isset($args['poredak'])) ? $args['poredak'] : null;

		if(isset($args['order']) && $poredak == $args['order'])
		{
			$order  .= " DESC";
		}
		

		$db 	= $boot->db;
		$vozila = getVozila($db, $order);

		if($poredak == $args['order']){

			$args['order'] = null;
		}


		$boot->render('admin/vozila/all.php',[
			'vozila'  => $vozila,
			'order'   => $args['order']
		]);

	});

