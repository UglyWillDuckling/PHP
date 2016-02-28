<?php
	
	$boot->setGet('auth/logout','auth.logout', function() use($boot){

		if($boot->member){// samo ako je korisnik logiran
			
			$_SESSION = array();
		//uklanjanje session cookie-ja	
			if(ini_get("session.use_cookies")){
				
				$params = session_get_cookie_params();
				setcookie(session_name(), '', time() - 42000,
					$params["path"], $params["domain"],
					$params["secure"], $params["httponly"]
				);
			}
			session_destroy();
			
			if(isset($_COOKIE['rememberToken']))
			{			
				setcookie('rememberToken', "", 1, "/");
				
				$boot->db->setTable('users');
				$boot->db->set(array([
					'field' => 'rememberToken',
					'value' => ""
				]),
				array([
					'field' => 'id',
					'rule'  => '=',
					'value' => $boot->member['id']
				]
				));		
			}
			
			$boot->flash('uspjesno ste odjavljeni');			
			$boot->redirect('home');
		}
	});		
