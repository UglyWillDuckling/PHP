<?php

	function getUserImage($user) {

		$db = DB::getInstance();


		$userKveri = "SELECT usernick FROM users WHERE user_id =?";
		
		if($db->upit("get", $userKveri, array($user)))
		{
			
			$userArray = $db->getResults();

			$user = $userArray[0]['usernick'];

			$kveri = "SELECT path FROM user_pics WHERE user=?";

			$db->upit("get",$kveri, array($user));

			if($db->_count > 0)
			{

				$imageArray = $db->getResults();

				$image = $imageArray[0];

				$image_url = $image['path'];

				return $image_url;
			}
		}

		return false;
	}
	
?>