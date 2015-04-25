
<?php

	
		$title = "email change";

		$root_folder = "C:/Apache24/htdocs/youbito";

	//header stranice, poziva start.php koji provjerava da li je korisnik logiran
		require_once("{$root_folder}/parts/header.php");


	$showForm = true;
	if($loggedIn)
	{

		$verify = new Validate();

		$showForm = true;
		if(isset($_POST['submit']))
		{

			$rules =  array("email" =>array("required" => "req",
										   "email" => 'email'
										   ),
						   "password"=>array("required" => "req",
						   					 "min" => '6',
						   					 "max" => '40',
						   					 "reg" => "((?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,40})"
						   					 )
							);

			if($verify->check($_POST, $rules))
			{

				$db = DB::getInstance();

				$kveri = "SELECT password FROM users WHERE user_id=?";

				$db->upit("get", $kveri, array($_SESSION['user_id']));

				if($db->_count)
				{

					$rez = $db->getResults();
					$truePass = $rez[0]['password'];


					$newEmail = $_POST['email'];
					$pass     = $_POST['password'];

					$continue = true;

					if(password_verify($pass, $truePass))
					{

						$kveri = "UPDATE users SET email=? WHERE user_id={$_SESSION['user_id']}";

						$question = $db->upit("set", $kveri, array($newEmail));

						if($question)
						{
							echo "email uspjesno promijenjen<br>";
						}

						else{

							echo "something went wrong<br>";
							echo $db->getErrors();
						}




					}

					else {

						echo "<p class='error'>wrong password</p><br>";
					}


				}
				else {

					echo $db->getErrors();
					echo "<br>doslo je do greske, pokusajte ponovno<br>";
				}


			}
			else {

				echo "<p class='error'>" . $verify->getErrors() . "<p>";
			}
		}
	}
	else {

		$showForm = false;
		$host = "http://" . $_SERVER['HTTP_HOST'] . "/youbito";
		$link = $host . "/index.php";


		header("Location {$link}");
		exit();
	}

	if($showForm)
	{


?>

	<div id="mail">
		<h3>Change your email address</h3>
		<form action="<?php echo clean($_SERVER['PHP_SELF']); ?>" method="POST" />
			<label for="email">new email address</label>
			<br>
			<input type="text" id="email" name="email" size = "30" maxlength="40" />
			<br>	
			<label for="password">input password</label>
			<br>
			<input type="text" id="password" name="password" size = "30" maxlength="40" />
			<br>
			<input type="submit" name="submit" value="change Email" />
		</form>
		


	</div>

<?php

	}
?>