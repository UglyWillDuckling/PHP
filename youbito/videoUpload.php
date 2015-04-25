<?php

	$root = "C:/Apache24/htdocs/youbito";
	require_once("{$root}/php/templates/start.php");//potrebno zbog $loggedIn-a



	if($loggedIn)
	{

		$owner = $_SESSION['user_id'];


		if(!empty($_FILES['video']['name']) && !empty($_FILES['thumb']['name']))
		{

			require_once("php/class/getid3/getid3.php");//objekt za obradu videa
			$get3D = new getID3;	//klasa za dobivanje informacija o videu

			
			$upload      = true;

			$ime         = $_FILES['video']['name'];
			if(strlen($ime) > 99) $ime = substr($ime, 0, 99);//ako je ime predugacko, skrati ga

			$tmp_name    = $_FILES['video']['tmp_name'];
			$thumb_name	 = $_FILES['thumb']['name'];
			$thumb_tmp   = $_FILES['thumb']['tmp_name'];

			$vid_info	 = $get3D->analyze($tmp_name);//info o videu preko get3D objekta


			$category    = $_POST['cat'];			
			$description = $_POST['description'];
			$private     = 0;

		//odredivanje duzine videa
			$vrijeme	 = $vid_info['playtime_string']; //string u kojem se nalazi trajanje videa
			$time        = explode(":", $vrijeme);	

			$min 	  = $time[0] * 60; //minute
			$sec 	  = $time[1];	   //sekunde

			$duration = (int)($min + $sec); //total
			//echo " trajanje " . $duration;

						
			$file_ext  = pathinfo($ime, PATHINFO_EXTENSION);//provjera tipa datoteke
			$thumb_ext = pathinfo($thumb_name, PATHINFO_EXTENSION);//ekstenzija slike


		//array za pretvorbu u json objekt i slanje nazad u izvornu skriput
			$send     = array();	
			
			$send['msg']     = "";
			$send['success'] = 0;
			
			
			$newName      = uniqid($ime, true);//stvaranje jedinstvenog imena datoteke
			$newVidName   = $newName . "." . $file_ext;//dodavanje odgovarajucih ekstenzija
			$newThumbName = $newName . "." . $thumb_ext;
			

			$output_path = "php/videos/"      . $newVidName;
			$thumb_path  = "php/pics/thumbs/" . $newThumbName;

		//provjera tipa datoteke
			if($file_ext != "avi"  &&
			   $file_ext != "mp4"  &&
			   $file_ext != "mov" )
			{

				$upload = false;
			}

			if( $thumb_ext != "jpg" &&
				$thumb_ext != "png" &&
				$thumb_ext != "gif" &&
				$thumb_ext != "jpeg")
			{
				$upload = false;
			}


			if($upload)
			{

				$db= DB::getInstance();

				$query = "SELECT MAX(thumb_id) as high FROM thumbs";
				$db->upit("get", $query);

				$rez   = $db->getResults();

				$thumb_id = $rez[0]['high'] +1;//novi thumb id, mora biti veci od prijasnjih

				$db->upit("set", "BEGIN WORK;");

				$query = "INSERT INTO thumbs(thumb_id, path) 
						  VALUES(?, ?)";
				
				if($db->upit("set", $query, array($thumb_id, $thumb_path)) )
				{

					if(move_uploaded_file($thumb_tmp, $thumb_path))
					{


						$mysql ="INSERT INTO videos(name, owner, category, 
													thumb, duration, private, descript, path, made) 
								 VALUES(?, ?, ?, ?, ?, ?, ?, ?, now())";

						$ime = substr($ime, 0, strpos($ime, ".")-1);

						if($db->upit("set", $mysql, array($ime, 
													      $owner, 
														  $category,
														  $thumb_id,
														  $duration,
														  $private, 
														  $description,
														  $output_path 
														  )) )
						{

							if(move_uploaded_file($tmp_name, $output_path))
							{	

								$send['msg'] = "file has been successfully uploaded";
								$send['success'] = 1;
								$db->upit("set", "COMMIT;");
							}
							else {

								$db->upit("set", "ROLLBACK;");//ponistavanje inserta
								$send['msg'] = " failed to upload the video ";
								unlink($thumb_path);//brisanje uploadanog thumba
							}
						}
						else {

							$db->upit("set", "ROLLBACK;");
							$send['msg'] = " failed to insert the video into the database ";
							unlink($thumb_path);
						}
					}
					else {

						$send['msg'] = " failed to upload the thumb ";
						$db->upit("set", "ROLLBACK;");
									
					}

				}
				else {

					$db->upit("set", "ROLLBACK;");
					$send['msg'] = " failed to insert into thumbs ";
									
				}
			}
			else {

				$send['msg'] =" has wrong file type";
			}


			echo JSON_encode($send);//json objekt za ispis poruke u izvornoj skripti

		}//provjera postojanja file-a
		else {

			//header("Location: Upload.php");
		}
	}
	else {

		//header("Location: index.php");
	}

?>
