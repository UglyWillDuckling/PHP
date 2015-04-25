<?php

	/******* 
			*include basic.php skripte u kojoj dodaju osnovne 
			 php skripte potrebne za rad stranice
	 *****/
		$php_folder = "C:/Apache24/htdocs/youbito/php";

		require_once("{$php_folder}/include/basic.php");

/****DB:getInstance() sada prima path prema connect skripti, Zebra_sess vec pokrenut *****/		
		$loggedIn = false;

		if(Input::exists('nick', $_SESSION) &&  Input::exists('user_id', $_SESSION))
		{
		$loggedIn = true;	
		}			
	
//cookie login - ako session varijable nisu namjestene, provjeravaju se cookie-ji
		elseif(Input::exists('nickname', $_COOKIE) && Input::exists('chance', $_COOKIE) 
			   && Input::exists('series', $_COOKIE) )	
		{

			$nick   = $_COOKIE['nickname'];
			$series = $_COOKIE['series'];
			$chance = $_COOKIE['chance'];


			$db = DB::getInstance($root);

			$query = "SELECT * FROM kookie WHERE nickname=? AND series=? AND chance=?";
			$db->upit("get", $query, array($nick, $series, $chance));

			if($db->_count)
			{

				$new_chance = base64_encode(openssl_random_pseudo_bytes(32) );

				$addCookie ="UPDATE kookie  
								SET chance =?  
							  WHERE series =? 
							";

				if($db->upit("set", $addCookie, array($new_chance, $series)) )
				{
					setcookie("chance", $new_chance, time() + 4000);
					
					$kveri = "SELECT
							  user_id,
							  usernick,
							  name, 
							  picture,							             
							  email, 
							  gender, 
							  secLevel
							  FROM users WHERE usernick=?";	

					$db->upit("get", $kveri, array($nick));

					if($db->_count)
					{

						$userData = $db->getResults();
						list($user_id, $nickname, $name, 
							 $picture, $email, $gender, $secLevel) 
						    =$userData[0];	
		 						 	
						$_SESSION['user_id']  = $user_id;
						$_SESSION['nick']	  = $nickname;	 	
						$_SESSION['name']     = $name;	 		 		 		 		 	
						$_SESSION['email']    = $email;
						$_SESSION['picture']  = $picture;
						$_SESSION['gender']   = $gender;	 	
						$_SESSION['secLevel'] = $secLevel;	 	

						$loggedIn = true;
					}
					else {

						echo "unable to update session data";
						echo $db->getErrors();
					}
				}
			//getErrors() koristi implode
				else {
						echo "unable to update the cookies";
						echo $db->getErrors();
				}
			}
			else 
			{

				$kveri = "SELECT * FROM kookie WHERE nickname=? AND series=?";
				$db->upit("get", $kveri, array($nick, $series));

			// u slučaju neispravnosti cookie-ja provjerava se da li cookie odgovara nekom o proslih cookie-ja
			// u tom slučaju trenutno aktivni cookie za ovog korisnika se brise
				if($db->_count)
				{
					echo "<p class='error'>Posibble account theft, 
						   the stored cookie will be deleted,
						    please login with your credentials
						  </p><br>";

					$deleteCookie = "DELETE FROM kookie WHERE nickname=? AND series=?";

					if(!$db->upit("set", $deleteCookie, array($nick, $series) ))
					{
						
							echo "<p class='error'> unable to update the cookie in the 
									database, please contact the administrator of the website
								 </p>";
							echo "<br>";
							echo $db->getErrors() . "<br>";
					}
					else {

						echo "<br>
							  cookie successfully deleted from the database, please verify 
							  the security of your network";

						$home = $_SERVER['HTTP_HOST'] . "/youbito/index.php";

						header("Refresh: 8; url=http://{$home}");				  
					}

				}
				else {
					echo "cookie invalid";

					setcookie("series"  , "", time() - 50000);
					setcookie("chance"  , "", time() - 50000);
					setcookie('nickname', "", time() - 50000);


				}

			}

		}	

?>