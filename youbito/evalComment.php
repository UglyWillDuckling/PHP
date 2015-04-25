<?php

		$root_folder = "C:/Apache24/htdocs/youbito";

		require_once("{$root_folder}/php/templates/start.php");

	//Varijabla za preusmjeravanje	
		$host = "http://" . $_SERVER['HTTP_HOST']. "/youbito";
		
		if($loggedIn) {

			if(Input::exists('level', $_GET)   && 
			   Input::exists('comment', $_GET) && 
			   Input::exists('love', $_GET)    && 
			   Input::exists('vid', $_GET)) 
			{

		// Varijable iz get globala
				$commentId = (int)$_GET['comment'];
				$love      = (int)$_GET['love'];						
				$videoId   = (int)$_GET['vid'];
				$level	   = (int)$_GET['level'];

			//ID korisnika	
				$userId    = $_SESSION['user_id'];
				

			//odredivanje tablica koje treba mijenjati na temelju $level varijable
			   //$level	      = (int)$_GET['level'];
				$table	      = ($level == 1)	? "comment_love" : "sub_comment_love";
				$commentTable = ($level == 1)	? "comments" : "sec_comments";	


				$db = DB::getInstance();

			//provjera je li korisnik vlasnik komentara iz $commentTable tablice
				$checkOwner = "SELECT * FROM {$commentTable} WHERE user=? AND comment_id =?";
		
				$continue = $db->upit("get", $checkOwner, array($userId, $commentId));

			//ako je korisnik vlasnik komentara ne smije ga ocijenjivati
				if(!$continue)	
				{	
									
					//varijable za 'COMMIT' promjene i njihov 'ROLLBACK' i pocetak 'begin work'
						$commit = "COMMIT;";
						$vrati  = "ROLLBACK;";
						$start  = "BEGIN WORK;";

					//pocetak transakcije	
						if($db->upit("set", $start))
						{

						//provjera je li korisnik vec ocijenio video	
							$checkForLove = "SELECT love FROM {$table} WHERE user=? AND comment=?";
							if($db->upit("get", $checkForLove, array($userId, $commentId)) )
							{
								$rez = $db->getResults();


							//ako je korisnik vec isto ocijenio video samo ga vrati na stranicu, nije stvarno moguce	
								$pastLove = (int)$rez[0]['love'];
								if($pastLove == $love)
								{

									$db->upit("set", $vrati);
									echo "ne mozete dati istu ocijenu";

									$link = $host . "/watch.php?vid={$videoId}";
									header("Location: {$link}");
								}

						//	Ako korisnik nije isto ocijenio video updejtaj "comment" i "comment_love" tablice	
								else { 

									$kveri = "UPDATE {$table} SET love=? WHERE comment=? AND user=?";

								//ako je update uspio nastavi sa updejtom comment tablice	
									if($db->upit("set", $kveri, array($love, $commentId, $userId)) )
									{
									//tercijarni operator za dodjeljivanje vrijednosti change varijabli($love)
										$change = ($love == 1) 
										? 
										"likes = likes +1, dislikes = dislikes -1"
										:
										"likes = likes -1, dislikes = dislikes +1";

									//updejtaj tablicu komentara
										$kveri = "UPDATE {$commentTable} SET {$change} WHERE comment_id=?";

										if($db->upit("set", $kveri, array($commentId)) )
										{
										//potvrda transakcije
											$db->upit("set", $commit);
											echo "uspjesna promjena misljenja";

											$link = $host . "/watch.php?vid={$videoId}";

											header("Location: {$link}");
										}
										else {

										//update comment tablice nije uspio, ponisti transakciju	
											$db->upit("set", $vrati);
											echo "neuspio update komentar tablice nakon update-a {$table} tablice<db>";
											echo $db->getErrors();

											$link = $host . "/watch.php?vid={$videoId}";

											header("Location: {$link}");
										}


									}
									else {
										
										$db->upit("set", $vrati);
										echo "neuspjeli update {$table} tablice prije update-a {$commentTable} tablice<db>";
										echo $db->getErrors();
										
										$link = $host . "/watch.php?vid={$videoId}";
										header("Location: {$link}");
									}
								}
							}
						//korisnik nije jos ocijenio komentar	
							else {

								$kveri = "INSERT INTO 
										  {$table}(comment, user, love) 
										  VALUES(?,?,?)";

								if($db->upit("set",$kveri, array($commentId, $userId, $love) ))
								{

									$change = ($love == 1) ? "likes = likes +1" : "dislikes = dislikes +1";

									$kveri2 = "UPDATE {$commentTable} SET {$change} WHERE comment_id =?";

									if($db->upit("set", $kveri2, array($commentId)))
									{

										$db->upit("set", $commit);

										$link = $host . "/watch.php?vid={$videoId}";
										header("Location: {$link}");
									}

									else {

									//ispis greske i preusmjeravanje nazad	
										$db->upit("set", $vrati);
										echo $db->getErrors();

										$link = $host . "/watch.php?vid={$videoId}";
										header("Location: {$link}");
									}



								}
								else {


									$db->upit("set", $vrati);
									echo $db->getErrors();

									$link = $host . "/watch.php?vid={$videoId}";
									header("Location: {$link}");
								}

							}
						}
						else {

							echo "nemam posla<br>";
							$link = $host . "/watch.php?vid={$videoId}";
							header("Location: {$link}");
						}
				}	
				else {

					echo "<strong>you can't evaluate your own comment</strong>";

					$link = $host . "/watch.php?vid={$videoId}";
				header("Refresh:3;  url={$link}");
				}

			}
			else {

				$link = $host . "/index.php";
				header("Location: {$link}");
			}
		}
		else {

			echo "not logged in";
			$link = $host . "/index.php";
			header("Location: {$link}");
		}

?>