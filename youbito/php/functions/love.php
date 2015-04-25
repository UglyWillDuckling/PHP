<?php


	require_once("autoload.php");

	function getLove($user_id, $vid_id)
	{

		$db = DB::getInstance();

		$kveri = "SELECT opinion FROM love WHERE video=? AND user=? ORDER BY love_id DESC";

		$db->upit("get", $kveri, array($vid_id, $user_id));

		if($db->_count)
		{
			$rez = $db->getResults();
			$loved = $rez[0]['opinion'];
			
			return $loved;			
		}

		return false;
	}
	
?>