<?php

		$title = "delete Account";

		$root_folder = "C:/Apache24/htdocs/youbito";

	//header stranice, poziva start.php koji provjerava da li je korisnik logiran
		require_once("{$root_folder}/parts/header.php");

		$host = "http://" .  $_SERVER['HTTP_HOST'] . "/youbito";

		$showForm = false;
		if($loggedIn) {

			


				

			$showForm = true;

			if(isset($_POST['submit']) &&
				 Input::exists("token", $_POST) &&
				 $_POST['token'] == $_SESSION['delete_token'] )
			{



					
					$secure = new Validate();

					$rules = array("password" =>       array("required" => "req")
								    ,
								   "repeatPassword" => array("required" => "req",
													   "match" => "password")
									);


					if($secure->check($_POST, $rules))
					{

						$given_pass = $_POST['password'];
						$user = $_SESSION['user_id'];

						$db = DB::getInstance();

						$kveri = "SELECT password FROM users WHERE user_id=?";

						$db->upit("get", $kveri, array($user));

						if($db->_count)
						{

							$rez = $db->getResults();
							$password = $rez[0]['password'];


							$verify = password_verify($given_pass, $password);

							if($verify)
							{
								

								$kveri = "DELETE FROM users WHERE user_id =?";
		
								if($db->upit("set", $kveri, array($user)))
								{

									echo "<p class='uspjeh'>Račun uspjesno obrisan</p>";
									$showForm = false;

									$link = $host . "/Getout.php";
									header("Refresh: 2; url={$link}");

								}
								else {

									echo "dogodila se greska, pokusajte opet<br>";
								}
		
							}else {

								echo "wrong password";
							}
						}
						else {

							echo "doslo je do greske, molimo pokusajte ponovno";
						}

					}
					else {

						echo $secure->getErrors();
					}
			}
			else {
			}

		}
		else {

			header("Location {$link}");
			exit();
		}


		if($showForm)
		{
			$token = base64_encode(openssl_random_pseudo_bytes(32) );
			$_SESSION['delete_token'] = $token;

?>

		<div id="mail">
			<h3>Delete your account</h3>
			<form action="<?php echo clean($_SERVER['PHP_SELF']); ?>" method="POST">
				<input type="hidden" name="token" values="<?php echo $token; ?>" />
				<label for="password">password</label>
				<br>
				<input type="password" id="password" size="20" maxlength="40" name="password">
				<br>
				<label for="repeatPassword">repeat password</label>
				<br>
				<input type="password" id="repeatPassword" size="20" maxlength="40" name="repeatPassword">
				<br>
				<input type="submit" id="logInButton" value="delete account" name="submit" />
			</form>
		
		</div>

<?php
	}

?>
	  </div>
	</body>
</html>

