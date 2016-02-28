<?php

//IMENICI
	use Twig\Twig;
	use RandomLib\Factory as RandomLib;

//include stvari poput composer klasa, konfiguracijskih datoteka, funkcija i sl.
	require "vendor/autoload.php"; // za autoload koristimo 'class_map' preko Composera
	require "init/init.php";
	require "config/config.php";
	require "api/functions/funkyLoader.php";
	
	
 //instanciranje objekta Bootstrap klase koji je zaslužan za funkcioniranje čitavog sajta			
	$boot = new Bootstrap('api/routes/', $config);//$config dobivamo kod require-a, 'config/config.php'

	/**
	 * inicijalizacija twig objekta i postavljanje istoga kao 'view' u $boot objektu 
	 * U aplikaciji ga koristimo pri renderiranju sadržaja
	 * sadrzaja(view)
	 */
	$boot->setView( INC_FOLDER . "/api/views", function($view_folder) {
		
		$loader   = new Twig_Loader_Filesystem($view_folder);	
		$twig     = new Twig_Environment($loader);
		
		$twig->addFilter(new Twig_SimpleFilter('rtrim', function($str, $visak){
			
			return rtrim($str, $visak);
		}));
		
		return $twig;
	}); // postavljanje objekta 'Twig' klase za view
	
 //postavljanje osnovnog(baznog) url-a sajta
	$boot->baseUrl = BASE_URL;
	
 //random generator stringova  //za sad se nigdje ne koristi
	$factory = new RandomLib;
	$boot->random = $factory->getMediumStrengthGenerator();
	
 //postavljanje objekta za validaciju (klasa 'Validator' produzuje klasu 'Violin')
	$boot->validate = new Validator($boot);	

 //postavljanje nuznih funkcija za ispravan rad sajta, session_start, provjera prijave i dr.
	require "api/Middleware/beforeCalls.php";


//grupe u koje cemo smjestiti odredene routove
	//require "groups/groups.php";
	require(INC_FOLDER . "/api/groups/groups.php");


 //svi routovi naseg sajta, spremaju se u $boot objekt	
	require "routes/routes.php"; 
