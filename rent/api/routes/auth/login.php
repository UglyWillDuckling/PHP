<?php
	
	$boot->setPost('auth/login','auth.login', function() use($boot){

		if(!$boot->member && isset($_POST['submit'])){ // u slučaju da korisnik jos nije prijavljen
				
			$username = Request::getPost('username');
			$password = Request::getPost('password');
			$remember = Request::getPost('remember');
			
			$v = new Validator($boot);
		  
			
			$v->validate([		
				'username'   => [$username, 'required'],
				'password'   => [$password, 'required']
			]);
			
			if($v->passes()){
				
				$db = $boot->db;
				
				$db->setTable('users');
				$user = $db->where(array([
					'field' => 'username',
					'rule'  => '=',
					'value' => $username
				]))
				->prvi();
							
				if($user && password_verify($password, $user['password']))
				{
					$_SESSION['userId'] = $user['id'];
					
					if(!$remember)
					{		
						$rememberIdentifier = $boot->random->generateString(128);					
						setcookie('rememberToken', $rememberIdentifier, time() + (86400 * 7), "/");
						
						$db->set(array([
							'field' => 'rememberToken',
							'value' => $rememberIdentifier
						]),
						array([
							'field' => 'id',
							'rule' => '=',
							'value' => $user['id']
						]
						));		
					}	
					
					$boot->flash('uspješno ste se prijavili');
				}else{
					$boot->flash('lozinka i username se ne podudaraju');
				}			
			}else
			{			
				$boot->flash($v->errors());
			}		
		}	
	// u svakom slučaju radimo redirect na početnu stranicu	
		
		$boot->redirect("home");	
	});	
	
