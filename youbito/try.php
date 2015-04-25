<?php

	require_once("php/functions/send_confirmation_email.php");

			$now = time();

			$mysql = "UPDATE users SET activation_code ={$now} WHERE user_id=3";

			$db = new mysqli("localhost", "root", "obisqlwan1", "youbito");

			$rez = $db->query($mysql);

			
			echo $db->error;


	?>