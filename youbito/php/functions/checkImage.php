<?php

	function checkImage($pic = "image") {

		$vrati = array("ok" => 1, "error" => array());

		$target_dir = "php/pics/profile_images/";

	//mjesto gdje bi trebalo spremit sliku
		$target_file = $target_dir . basename($_FILES["image"]["name"]);

		$ImageType = pathinfo($target_file, PATHINFO_EXTENSION);
	
		//provjera velicine slike
		if($_FILES['image']['size'] > 50008)
		{
			array_push($vrati["error"], "slika je prevelika");
			$vrati["ok"] = 2;
		
		}

	//provjera tipa slike
		if($ImageType != "jpg"  &&
		   $ImageType != "png"  &&
		   $ImageType != "gif"  &&
		   $ImageType != "jpeg" &&
		   $ImageType != "WEBP"
		)
		{

			array_push($vrati["error"], "tip slike nije podrzan, jpg, png ili gif");
			$vrati["ok"] = 2;
		}

	//path za upload slike se sprema u array	
		$vrati['path'] = $target_file;	
		$vrati['type'] = $ImageType;


		return $vrati;
	}

?>