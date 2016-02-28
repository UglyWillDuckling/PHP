<?php
	

//array sa podacima o konfiguraciji sajta(sprema se u Bootstrap)
	$config = array(
	
		"database" => [
			"user" 	   => "root",
			"password" => "",
			"db" 	   => "rent_a_car",
			"host" 	   => "127.0.0.1",
			"type"	   => "mysql"
		],
		'password' => [		
			'algo' => PASSWORD_BCRYPT		
		],
		'image' => [
			'size'   => 50000,
			'format' => ['jpg', 'png', 'gif']
		]	
	);			
?>