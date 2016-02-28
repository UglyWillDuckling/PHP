<?php

	$auth = function() use($boot){
			
		if(isset($_SESSION['userId'])){

			$userId = $_SESSION['userId'];
			$db     = $boot->db;
			$db->setTable('users');
			
			$dbConfig = $boot->config['database'];		
			$user = new DB(
				$dbConfig['type'], 
				$dbConfig['db'],
				$dbConfig['host'], 
				$dbConfig['user'], 
				$dbConfig['password']
			);			
			
			$boot->member = $db->where(array([
				'field' => 'id',
				 'rule' => "=", 
				'value' => $userId
			]))
			->prvi();
		}
		elseif(isset($_COOKIE['rememberToken'])){

			$db = $boot->db;
			
			$db->setTable('users');
			$user = $db->where(array([
				'field' => 'rememberToken',
				'rule'	=> '=',
				'value' => $_COOKIE['rememberToken']
			]))
			->prvi();	
			
			$_SESSION['userId'] = $user['id'];					
		}
		else
		{			
			$boot->member = false;
		}
	};
	
	