
<?php

	
		$title = "password change";

		$root_folder = "C:/Apache24/htdocs/youbito";

	//header stranice, poziva start.php koji provjerava da li je korisnik logiran
		require_once("{$root_folder}/parts/header.php");

	
	if($loggedIn)
	{
		$showForm = true;

		if(isset($_POST['submit']))
		{

				$check = new Validate();

			//array koji sluzi za spremanje pravilo kod evaluacije podataka(ovdje samo password-a)
				$verify_array = array("newPass" => array( "required" => "required",
														  "min" => "5",
														  "max" => "40",
														  "reg" => "((?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,40})")
														 );

		//provjera ispravnosti password-a
			if(Input::exists("oldPass", $_POST) && $check->check($_POST, $verify_array))
			{
				$error = "<p class='error'>";

				$give_old_password = $_POST['oldPass'];
				$newPass 		   = $_POST['newPass'];

				$id = $_SESSION['user_id'];
				$db = DB::getInstance();
				//provjeri stari password

				$kveri = "SELECT password FROM users WHERE user_id=?";

				if($db->upit("get", $kveri, array($id)))
				{

				//provjeri password korisnika 
					$rez = $db->getResults();
					$oldPass = $rez[0]['password'];

		
					
				//provjera jednakosti starog passworda sa onim kojeg nam daje korisnik	
					$verify = password_verify($give_old_password, $oldPass);
					if($verify)
					{

						$newPass = password_hash($newPass, PASSWORD_BCRYPT);
						

						$updateKveri = "UPDATE users SET password=? WHERE user_id={$id}";

						if($db->upit("set", $updateKveri, array($newPass)))
						{
							echo "password changed successfully<br>";

						}
						else {

							echo "something went wrong, can't update the password<br>";
						}

					}
					else {

						$error .= "zadani password ne odgovora starom passwordu</p>";
						echo $error;
						}

				}
				else {

					$link = "http://" . $_SERVER['HTTP_HOST'] . "/youbito/index.php";
					echo "something terrible went wrong, we will try to fix it right away<br>";
					header("Refresh: 3; url={$link}");
				}
			}
			else {

				echo "password mora sadrzavati minimalno 6 znakova, 1 veliko, 1 malo slovo i broj<br>";

			}

		}

	//ako nema greske u vezi s BP-om form ce uvijek biti pokazan
		if($showForm)
		{

?>
		<div id="pass">
			<h3>Change your password</h3>
			<form action="<?php echo clean($_SERVER['PHP_SELF']); ?>" method="POST" />
				<label for="oldPass">Input old password</label>
				<br>
				<input class="password" type ="text" id="oldPass" name="oldPass" size="20" maxlength="40">
				<br>
				<label for="newPass">Input new password</label>
				<br>				
				<input class="password" type ="password" id="newPass" name ="newPass" size="20" maxlength="40">
				<br>
				<input type="submit" value="change" name="submit" />
			</form>


		</div>


<?php	
		}
	}

	else {

		$link = "http://" .  $_SERVER['HTTP_HOST'] . "/youbito/index.php";
		header("Location {$link}");
	}



?>