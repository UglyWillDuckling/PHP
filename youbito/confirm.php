<?php
	

	$root = $_SERVER['DOCUMENT_ROOT'] . "/youbito";
	require_once("{$root}/parts/header.php");


	if(isset($_GET['token']) && isset($_GET['address']))
	{

		$token  = clean($_GET['token']);//spremanje potrebnih podataka u varijable
		$adresa = clean($_GET['address']);


		$db = DB::getInstance();

		$mysql = "SELECT country FROM users WHERE email=? AND activation_code=? AND active=0";

		$upit  = $db->upit("get", $mysql, array($adresa, $token));

		if($upit) //ako query uspije aktiviraj racun
		{

			$activate  = "UPDATE users SET active=1 WHERE email=?";
			$activated = $db->upit("set", $activate, array($adresa));

			if($activated)//ako je račun aktiviran ispisi potvrdnu poruku
			{


				echo "<p class='uspjeh'>Your account has been successfully activated, 
										         you can now login</p>";

				$link = "index.php";
				header("Refresh: 2; url={$link}");

			}
			else {//nesto ne valja

				echo "We apologize but something went wrong, we will try to fix 
										 the problem shortly, try again in a little while.";
			}
		}
		else {

			$link = "index.php";
			echo "<p class='error'>the info you've provided does not match the one that is required</p><br>";
			header("Location: {$link}");
		}

	}
	else {//ako korisnik nema potrebne podatak preusmjeri na početnu stranicu


		$link = "http://" . $_SERVER['HTTP_HOST'] . "/youbito/index.php";
		header("Location: {$link}");
	}
?>