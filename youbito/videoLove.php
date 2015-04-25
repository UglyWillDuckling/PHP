<?php

	$root_folder = "C:/Apache24/htdocs/youbito";

	require_once("{$root_folder}/php/templates/start.php");

	if($loggedIn)
	{

		if(Input::exists('vid_id', $_GET) && Input::exists('love', $_GET) )
		{
			
				$vidId =(int)$_GET['vid_id'];
				$love  = (int)$_GET['love'];			
				$user  = $_SESSION['user_id'];


				$db = DB::getInstance();

			//varijable za 'COMMIT' promjene i njihov 'ROLLBACK'
				$commit = "COMMIT;";
				$vrati = "ROLLBACK;";


			//pocetak transakcije	
				$start = "BEGIN WORK;";
				if($db->upit("set", $start))
				{
			
					//provjerava se da li je korisnik vec ocijenio video
						$checkForLove = "SELECT opinion FROM love WHERE video=? AND user=?";

						if($db->upit("get", $checkForLove, array($vidId, $user)) )
						{

						//ako je korisnik vec otprije ocijenio varijabla dobiva vrijednost false	
							$rez = $db->getResults();
							print_r($rez);
							$continue = ($rez[0]['opinion'] == $love) ? false : true;


							if($continue)
							{

							//ako korisnik nije na isti nacin vec ocijenio video updejtaj love tablicu
								$kveri2 = "UPDATE love SET opinion=? WHERE video=? AND user=?";
							

							//update love tablice u kojem se mjenja vrijednost opinion kolumne	
								if($db->upit("set", $kveri2, array($love, $vidId, $user)) )
								{

							//varijabli change se zadaje vrijednost na temelju odabira korisnika (like/dislike)
							//mjenjaju se vrijednosti like i dislike tablica za specificni video
									$change = ($love == 1) 
									? 
									"`like` = `like` +1, dislike= dislike -1" 
									:
									"`like` = `like` -1, dislike = dislike +1";
					
							
							//kveri varijabala se zadaje na temelju change varijable
									$kveri = "UPDATE videos SET {$change} WHERE video_id=?";

									if($db->upit("set", $kveri, array($vidId)) )
									{

									//ako je sve proslo u redu daje se commit naredba	
										$db->upit("set", $commit);


										$host = "http://" . $_SERVER['HTTP_HOST'];
										$link = $host . "/youbito/watch.php?vid={$vidId}";	

							  			header("Location: {$link}");
									}
									else {

										$db->upit("set", $vrati);
										echo $db->getErrors();
										

										$host = "http://" . $_SERVER['HTTP_HOST'];
										$link = $host . "/youbito/index.php";	

						  				header("Refresh: 3; url={$link}");
									}
									
								}
								else{
							//korisnik je ocijenio video kao i prosli put, nije u biti moguce
							//rollback učinjenih promjena u slučaju neuspjesnog query-ja i ispis gresaka										
									$db->upit("set", $vrati);

									echo $db->getErrors();

									$host = "http://" . $_SERVER['HTTP_HOST'];
									$link = $host . "/youbito/index.php";	

						  			header("Refresh: 3; url={$link}");
								}
							}
							else{

								$db->upit("set", $vrati);

								$host = "http://" . $_SERVER['HTTP_HOST'];
								$link = $host . "/youbito/index.php";	

						  		header("Refresh: 3; url={$link}");
							}			
						}

					//Ako korisnik jos nije ocijenio video umece se novi red u tablicu		
						else{

							echo "korisnik jos nije ocijenio video <br>";

							$kveri2 = "INSERT INTO love(video, user, opinion) values(?,?,?)";

							if($db->upit("set", $kveri2, array($vidId, $user, $love)) )
							{
								

								$change = ($love == 1) ? "`like` = `like` +1" : "dislike = dislike +1";
								echo $change . "<br>";
								$kveri = "UPDATE videos SET {$change} WHERE video_id=?";

								if($db->upit("set", $kveri, array($vidId)) )
								{

								//ako je sve proslo kako treba daje se commit naredba		
									$db->upit("set", $commit);

									$host = "http://" . $_SERVER['HTTP_HOST'];
									$link = $host . "/youbito/watch.php?vid={$vidId}";	

							  		header("Location: {$link}");
								}
								else {

									$db->upit("set", $vrati);

									echo $db->getErrors();


									$host = "http://" . $_SERVER['HTTP_HOST'];
									$link = $host . "/youbito/index.php";	

							  		header("Refresh: 3; url={$link}");
								}
							}	
							else{

							//ako nije uspjelo umetanje u love tablicu daje se naredba 'rollback' i ispisuju greske	
								$db->upit("set", $vrati);

								echo $db->getErrors();

								$host = "http://" . $_SERVER['HTTP_HOST'];
								$link = $host . "/youbito/index.php";	

							  	header("Refresh: 3; url={$link}");
							}
						}
			  }
			  else {

			  	echo $db->getErrors();

			  	$host = "http://" . $_SERVER['HTTP_HOST'];
				$link = $host . "/youbito/index.php";	

			  	header("Refresh: 3; url={$link}");
			  }

		}
		else{

			$host = "http://" . $_SERVER['HTTP_HOST'];
			$link = $host . "/youbito/index.php";

			header("Location: {$link}");
			exit();
		}
	}
	else {

		echo "not logged in";
		$host = "http://" . $_SERVER['HTTP_HOST'];
		$link = $host . "/youbito/index.php";

		header("Location: {$link}");
		exit();
	}

?>
