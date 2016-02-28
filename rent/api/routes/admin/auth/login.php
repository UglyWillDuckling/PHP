<?php

	$boot->setGet('admin/auth/login', 'admin.auth.login', function() use($boot)
	{

		if(!$boot->zaposlenik)
		{
			$boot->render('admin/auth/login.php');	
		}
		else{

		//ako je korisnik vec prijavljen kao zaposlenik, preusmjeri ga na admin stranicu
			$boot->redirect(
			 $boot->urlZa('admin.home'),
			 'vec ste prijavljeni kao zaposlenik'
			);
		}	
	});	


	$boot->setPost('admin/auth/login', 'admin.auth.login.post', function() use($boot)
	{

		if(!$boot->zaposlenik)
		{

			$username = Request::getPost('username');
			$password = Request::getPost('password');


			$db = $boot->db;
			$db->setTable('zaposlenici');
			
			$zaposlenik = $db->where(array([
				'field' => 'username',
				'rule'  => '=',
				'value' => $username
			]))
			->prvi();

			if($zaposlenik && password_verify($password, $zaposlenik['password']))
			{

				$_SESSION['adminId'] = $zaposlenik['id'];	

				$boot->redirect('admin/home', 'prijavljeni ste kao administrator.');
			}
			else{

				$boot->redirect('admin/auth/login', 'korisničko ime i lozinka se ne podudaraju.');
			}
		}
		else
		{
			$boot->redirect($boot->urlZa('admin.home'), 'vec ste prijavljeni kao zaposlenik');
		}

	});