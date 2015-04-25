<?php
	/******* include svih potrebnih klasa i funkcija nuznih za funkcioniranje stranice ****/
	
	$root = "C:/Apache24/htdocs/youbito/php";
	
//autoload skripta koristi autoload_register() za inicijalizaciju bilo koje klase
	require_once("{$root}/functions/autoload.php");

//varijable za povezivanje s bazom podataka
	require_once("{$root}/scripts/connect.php");

//zebra_session objekt "seska";
	require_once("{$root}/templates/seska.php");

//funkcija za sanitizaciju podataka
	require_once("{$root}/functions/clean.php");
	
?>