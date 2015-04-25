<?php
	
	$path = "C:/Apache24/htdocs/youbito/php/include";

	require_once($path . "/basic.php");


	if(isset($_POST['submit']))
	{
		
		if( (Input::exists('nick', $_POST)) && (Input::exists('pass', $_POST)) )
		{
	 														
		  /*link za redirect prema pocetnoj stranici */		    
			$host   = $_SERVER['HTTP_HOST'];
			$extra  = "/youbito/index.php";							
			$link   = $host . $extra;		
		  /*										 */

			$nickname = $_POST['nick'];
			$newPass  = $_POST['pass'];


			$base = DB::getInstance();

			$kveri =   "SELECT 
						user_id,
						usernick,
						name, 
					    email, 
						gender, 
						secLevel,
						password, 
						country,
						active
					    FROM users
					    WHERE usernick=?";


		//$pass = password_hash($pass, 10);
			if($base->upit("get", $kveri, array($nickname)))
			{

				$rez = $base->getResults();
				list($user_id,
					 $nick, 
					 $name, 
					 $email, 
					 $gender, 
					 $secLevel, 
					 $password, 
					 $country, 
					 $active)  = $rez[0]; // smjestanje podataka u varijable
			

				if(password_verify($newPass, $password))
				{

					if($active)
					{


						$_SESSION['user_id']  = $user_id;
						$_SESSION['nick']     = $nick;
						$_SESSION['name']     = $name;	
						$_SESSION['email']    = $email;
						$_SESSION['gender']   = $gender;
						$_SESSION['secLevel'] = $secLevel;
						$_SESSION['country']  = $country;


					//u slučaju da je korisnik oznacio rememberme radi se login preko cookie-ja
						if(isset($_POST['remember']) && ($_POST['remember'] == "on"))
						{

							$chance = base64_encode(openssl_random_pseudo_bytes(32)); 
							$series = $chance;
							$nadimak = 	$nick;

							$kveri = "INSERT INTO kookie(series, chance, nickname) VALUES(?, ?, ?)";

							if(!$base->upit("set", $kveri, array($series, $chance, $nadimak)) )
							{
								echo "bad cookie upload to database";
							}
							else{

								setcookie('series' , $series ,  time() + 33200);
								setcookie('chance'  , $chance  , time() + 33200);
								setcookie('nickname', $nickname, time() + 33200);
							}
						}
						else{
						
						}


					//u slučaju uspjesnog logina preusmjeri prema pocetnoj stranici	
						echo "You have successfully logged in, good job you<br>";			
						header("Refresh: 2; url=http://{$link}");
					}
					else {

						echo "You still haven't activated your account, please use the email that was sent to you 
								to activate your account. After that you will be able to login";
						header("Location: 'index.php'");	
					}			
				}
				else {

				//lozinka ne valja	
					echo "neispravan unos, kombinacija lozinka/nick netocna";
					header("Refresh: 2; url=http://{$link}");
				}
			}
			else{

		//!!! ako korisnik ne postoji, treba doraditi	
			echo "<p class='error'>ovaj korisnik ne postoji</p>";	
			header("Refresh: 2; url=http://{$link}");	
			}
		}
		else{

		//!!! ako nisu uneseni nick ili password
			echo "<p class='error'>morate unijeti i nick i password</p>";
			header("Location: ");

		}
	}
	else {

	//!!! ako submit nije stisnut
		echo "<p class='error'>nesto ne valja</p>";
		header("Location: ");
	}
?>