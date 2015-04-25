<?php
	
	$path = "C:/Apache24/htdocs/youbito/php/include";

	require_once($path . "/basic.php");


	if(isset($_POST['submit']))
	{
		
		if( (Input::exists('nick', $_POST)) && (Input::exists('pass', $_POST)) )
		{

			$nickname = $_POST['nick'];
			$pass     = $_POST['pass'];

			

			echo $nickname;
			echo $pass;
			$base  = DB::getInstance();
			$kveri =   "SELECT user_id, usernick, name, picture, email, gender, secLevel
					    FROM users
					    WHERE usernick=? AND password=?";


		//link za redirect prema pocetnoj stranici			    
				$host = $_SERVER['HTTP_HOST'];
				$uri = rtrim($_SERVER['PHP_SELF'], "/\\");
				$extra = "/youbito/index.php";							
				$link = $host . $extra;		

		//$pass = password_hash($pass, 10);
			$base->upit("get", $kveri, array($nickname, $pass));
			if($base->_count)
			{

				$rez = $base->getResults();
				list($user_id, $nick, $name, $picture, $email, $gender, $secLevel) = $rez[0];

				$_SESSION['user_id'] = $user_id;
				$_SESSION['nick'] = $nick;
				$_SESSION['name'] = $name;
				$_SESSION['picture'] = $picture;
				$_SESSION['email'] = $email;
				$_SESSION['gender'] = $gender;
				$_SESSION['secLevel'] = $secLevel;

				/*setcookie("nickname", "", time() - 3222);
				setcookie("chance", "", time() - 3222);
				setcookie("series", "", time() - 3222);

				*/

				/******* Check provjera za remember me, treba jos doradit *****/

				if(isset($_POST['remember']) && ($_POST['remember'] == "on"))
				{
					echo "<br>cookie On";

					$chance = base64_encode(openssl_random_pseudo_bytes(32)); 
					$series = $chance;
					$nadimak = 	$nick;

					$kveri = "INSERT INTO kookie(series, chance, nickname) VALUES(?, ?, ?)";

					if(!$base->upit("set", $kveri, array($series, $chance, $nadimak)) )
					{
						echo "bad cookie upload";
					}
					else{
						echo "<br>setting cookies";
						setcookie("series" , $series ,  time() + 33200);
						setcookie("chance"  , $chance  , time() + 33200);
						setcookie("nickname", $nickname, time() + 33200);
						echo $_COOKIE['nickname'];

					}

				}
				else{
					echo "<br>cookie Off";
				}

				echo "You have successfully logged in, good job you<br>";			

				

				//header("Refresh: 8; url=http://{$link}");

			}
			else {

				echo "neispravan unos, kombinacija lozinka/nick netocna";
				//header("Refresh: 2; url=http://{$link}");
			}
		}
		else{
				echo "<p class='error'>nick ili password nisu uneseni</p>";
				
		}
	}
	else{
		echo "<p class='error'>nesto ne valja</p>";
		header("Location: ");

	}
?>