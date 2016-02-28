<?php

$boot->setGet('korisnik/rezervacije', 'korisnik.rezervacije', function() use($boot){

			//checkMember();

			$db = $boot->db;

			$rezervacije = getRezervacije($db, $boot->member['id']);

			print_r($rezervacije);
			$boot->render('korisnik/rezervacije.php',[
				'rezervacije' => $rezervacije
			]);	
	});
