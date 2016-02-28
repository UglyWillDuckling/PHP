<?php

	function checkZaposlenik($boot){

		if(!$boot->zaposlenik)
			$boot->redirect('admin/auth/login', 'za pristup ovom dijelu sajtu morate se prijaviti kao admin.');
	}

	function checkMember($boot){

		if(!$boot->member)
			$boot->redirect('home', 'za iznajmljivanje vozila morate biti registrirani.');
	}