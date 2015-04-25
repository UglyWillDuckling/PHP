<?php

		$main_php = "C:/Apache24/htdocs/youbito/php";
		require_once($main_php . "/include/basic.php");

		if(Input::exists('user_id', $_SESSION) &&  Input::exists('nick', $_SESSION))
		{

			$seska->stop();	

			if(Input::exists('nickname', $_COOKIE) && Input::exists('series', $_COOKIE))
			{

				$nick = $_COOKIE['nickname'];
				//brisanje cookie-ja
				setcookie('nickname', "", time() - 22222);
				setcookie('series'  , "", time() - 22222);
				setcookie('chance'  , "", time() - 22232);

			//uklanjanje cookie-ja iz baze podataka
				$db = DB::getInstance();
				if(!$db->upit("set", "DELETE FROM kookie WHERE nickname='{$nick}'"))
				{
					echo "<p class='error'>unable to delete cookies from database</p><br>";
					echo $db->getErrors();
					sleep(3);
				}				
			}

			echo "You have been logged out";
			$link = $_SERVER['HTTP_HOST'] . "/youbito/index.php";
			header("Refresh: 3; url=http://{$link}");
			exit();
		}
		else{
			$link = $_SERVER['HTTP_HOST'] . "/youbito/index.php";
			header("Location: http://{$link}");
			exit();
		}
?>