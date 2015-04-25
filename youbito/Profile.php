<?php
	
		$title = "profil";

		$root_folder = "C:/Apache24/htdocs/youbito";

	//header stranice, poziva start.php koji provjerava da li je korisnik logiran
		require_once("{$root_folder}/parts/header.php");



		$showForm = false;
		if($loggedIn) 
		{

	/**********varijable za pomoc pri radu skripte****************/

		//DB objekt koji se koristi kroz čitavu skriptu	
					$db = DB::getInstance();

		//id korisnika, koristi se za update informacija te njihov ispis
			$korisnikId = $_SESSION['user_id'];

		//varijabla za pomoc pri preusmjeravanju korisnika
			$host = "http://" . $_SERVER['HTTP_HOST'] . "/youbito";

	
		//objekt za provjeru uploada
			$check = new Validate();

/**********************************************************************************/			
		

			$showForm = true;
			if(isset($_POST['submit']))
			{

			//id korisnika 
				$korisnik = $_SESSION['nick'];

	//varijabla za kontrolu uspjeha operacije, ako je na kraju false cijela operacija se ponistava sa ROLLABACK
				$uspjeh = true;

			//array za greske
				$errors = array();
			
	/***********************************************************************************/

				if($check->check($_POST, array("usernick"=>array("min" => '3',
															    "max" => '30', 
															    "required" => 'req'
																),
												"name"=>array("min" => '3',
															  "max" => '30',
															  "required" => 'req'
															  ),
												
												"lastname"=>array("min" =>'4',
																  "max" => '30',
																  "required" => 'req'
																 ),					
												"country"=>array("required" => 'req'),
												"gender" =>array("required" => 'req')
												))
				)
				{
					require_once("php/functions/getUserImage.php");

				
				//pocetak transakcije
					if($db->upit("set", "BEGIN WORK;"))
					{

					//staro ime korisnika, sluzi za dobivanje puta prema staroj slici korisnika
						$oldNick		 = $_SESSION['nick'];

						$newNick		 = $_POST['usernick'];
						$name 	 		 = $_POST['name'];
						$lastname 	 	 = $_POST['lastname'];
						$country 		 = $_POST['country'];
						$gender 		 = $_POST['gender'];

					//put prema staroj slici korisnika
						$old_image_path = getUserImage($korisnikId);

					//varijabla za nadzor brisanja slike sa server	
						$replaceImage = false;
						
					//varijabla koja pokazuje da li treba stvoriti novi red u use_pics tablici
						$newImage = false;

					//nadzor ispravnosti nadimka(unique)
						$uniqueNick = true;
						if($newNick != $_SESSION['nick'])
						{
							$newImage = true;
						//ako je nadimak novi provjera se njegovo postojanje u bazi podataka	
							$uniqueQuery = "SELECT * FROM users WHERE usernick=?";

							if($db->upit("get", $uniqueQuery, array($newNick)))
								$uniqueNick = false;
						}

						if($uniqueNick)
						{

							$kveri = "UPDATE users
									  SET 
									  usernick=?,
									  name=?,
									  lastname=?,
									  country=?,
									  gender=?
									  WHERE
									  user_id ={$korisnikId}";


							if($db->upit("set", $kveri, array($newNick, $name, $lastname, $country, $gender)))
							{
								if(isset($_FILES['image']) && is_file($_FILES['image']['tmp_name']))
								{

									require_once("{$root_folder}/php/functions/checkImage.php");

								//provjera slike
								//vraca se array sa odredistem za upload slike	
									$ImageInfo = checkImage();


									if((int)$ImageInfo['ok'] === 1)
									{

										$path = $ImageInfo['path'];
										$type = $ImageInfo['type'];

										$randName = uniqid('', true);
										$last = strripos($path, "/");


									//novi put prema slici, poslije posljednjeg "/" umece se random string(uniqid)
										$new_pic_path  = substr_replace($path, $randName, $last+1, 0);


										if($newImage)
										{

									// u slučaju da je usernick nepromjenjen potrebno prvo izbrisati red iz pics tablice
											$updateImage = "DELETE FROM user_pics WHERE user=?";

											if($db->upit("set", $updateImage, array($newNick)))
											{

												$updateImage = "INSERT INTO 
																user_pics(user, path)
																VALUES(?, ?)";


												
												if($db->upit("set", $updateImage, array($newNick, $new_pic_path)))
												{
													$replaceImage = true;
												}
												else {

													$uspjeh = false;
												}
												
											}
											else {

												echo "brisanje slike iz neuspjelo";
												$uspjeh = false;
											}

										}
										else {

											$imageQuery = "UPDATE user_pics SET path=? WHERE user='{$newNick}'";

											if($db->upit("set", $imageQuery, array($new_pic_path)))
											{
													$replaceImage = true;
											}

											else {

												$uspjeh = false;
												echo "update pics tablice neuspio<br>";
											}
										}


										if($uspjeh)
										{

											$upload = move_uploaded_file($_FILES['image']['tmp_name'], $new_pic_path);

											if(!$upload)
											{

											//ako upload slike ne uspije signaliziraj gresku
												$uspjeh = false;
												echo "upload slike na server neuspio<br>";

											}

						/***********Uklanjanje stare slike**********************/					
											else
											{
											//ako slika nije default image, obrisi ju
												if($old_image_path != "images/default.jpg")
														@unlink($old_image_path);	
											 }
						/*******************************************************/
										 }
										 else { }
									}
									else {

										echo "slika neispravna";
										print_r($ImageInfo);
										$uspjeh = false;
									}
								}
								else {}	
							}
							else {

								echo "update user podataka neuspio";
								$uspjeh = false;
							}

						}
					//ako nadimak vec postoji
						else {

							echo "nadimak vec postoji";
							$uspjeh = false;
						}


		/***************ZAVRSNA PROVJERA USPJESNOSTI*******************************/			


					if($uspjeh)
					{

					//potvrda promjene u bazi podataka COMMIT
						$db->upit("set", "COMMIT;");
						echo "<p class='uspjeh'>*uspjesan update podataka</p><br>";
					}
					else {

					//ako je nesto poslo po zlu ponisti promjene	
						$db->upit("set", "ROLLBACK;");
						echo "neuspio update korisnika";

					//ispis gresaka iz errors arraya
						echo "<pre>";
						print_r($errors);
						echo "</pre>";
					}		

				}
				else {

					echo "pocetak posla neuspio";
					$uspjeh = false;
				}
				



	/****************************KRAJ_POSLA*************************************************/
			}
			else {

				echo "podaci neispravni<br>";
				echo "<p class='error'>" . $check->getErrors() . "</p>";
			}
		}			
		}
		else {

			echo "you are not logged In";

			$showForm = false;
		//	header("Location {$link}");
		//	exit();
		}

	
		if($showForm)
		{


			$query = "SELECT users.usernick,
							 users.name,
							 users.lastname,
							 users.gender,
							 user_pics.path as image,
							 countries.name as drzava,
							 countries.country_id as country_id
							 FROM users
							 INNER JOIN user_pics
							 ON
							 users.usernick = user_pics.user
							 INNER JOIN countries
							 ON 
							 users.country = countries.country_id
							 WHERE users.user_id =?
							 ";
			
			
			if($db->upit("get", $query, array($korisnikId)))
			{

			//dobavljanje informacija o korisniku
				$info = $db->getResults();

				$userInfo = $info[0];

				$nick             = $_SESSION['nick']     = $userInfo['usernick'];	
				$name             = $_SESSION['name']     = $userInfo['name'];				
				$drzava           = $_SESSION['drzava']   = $userInfo['drzava'];	
				$drzavaId         = $_SESSION['country']  = $userInfo['country_id'];	
				$gender           = $_SESSION['gender']   = $userInfo['gender'];
				$lastname         = $userInfo['lastname'];	
				$userPic 		  = $userInfo['image'];	
			}	

			else{

				$showForm = false;
				echo "nema informacija o korisniku";
			}	
		}

		if($showForm)
		{		  
	//ako sve prode u redu pokazi form
?>


		
		<div id="content">
			<h3>Update your Profile</h3>		
				<div id="signWrap">
					<formWrap>
						<form enctype="multipart/form-data" action="<?php echo clean($_SERVER['PHP_SELF']); ?>" class="signUpForm" method="POST">
							<input type="hidden" name="token" value="<?php echo $token; ?>">
							<div id="inputWrap">
							<div id="leftInfo">*mora biti uneseno</div>		
								<label class="centerLabel" for="nick">
									Nickname
								</label>
								<div class="entry">
								<input type="text" value="<?php echo $nick; ?>" id="usernick" name="usernick" size="30" maxlength="40" />
									  <span class="star" >
									  	*
									  </span>
									<dd>
										<div class="warning">
											najmanje 3 slova	
										</div>
									</dd>
								</div>						
								<label class="centerLabel" for="name">
									Name	
								</label>
								<div class="entry">
									<input type="text" id="name" name="name" value="<?php echo $name; ?>" size="30" maxlength="40" />								
									 <span class="star">
									  	*
									  </span>
									<dd>
										<div class="warning">
												najmanje 3 slova
										</div>
									</dd>
								</div>		
								<label class="centerLabel" for="lastname">
									Lastname
								</label>
								<div class="entry">	
									<input type="text" id="lastname" name="lastname" value="<?php echo $lastname; ?>" size="30" maxlength="40" />
									 <span class="star">
									  	*
									  </span>
									<dd>
										<div class="warning">
												najmanje 3 slova
										</div>
									</dd>
								</div>
								<label for="country">
									Zemlja
								</label>
									<select id="country" name="country">	
										<option value="<?php echo $drzavaId; ?>">
											<?php echo $drzava; ?>
										</option>

										<?php 

										//dobavljanje zemalja za izbor korisniku
											$db = DB::getInstance();

											$kveri = "SELECT country_id, name FROM countries";
											$db->upit("get",$kveri);

										//ako zemlje postoje u DB-u ispisuju se kao select elementi
											if($db->_count)
											{	

												$countries = $db->getResults();
												foreach($countries as $country)
												{

													$zemlja = $country['name'];								
													$id 	= $country['country_id'];

													if($zemlja != $drzava)
													{
										?>
														<option value="<?php echo $id; ?>">
															<?php echo $zemlja; ?>
														</option>
										<?php
													}
												}
											}
										?>
									</select>	
				
								<div class="entry">	
									<label for="sex">Gender</label>
									<input type="radio" <?php if($gender == 1) echo "checked='checked'"; ?> name="gender" value="1">
									male
									<input type="radio" <?php if($gender == 2) echo "checked='checked'"; ?> name="gender" value="2">
									female
									<input type="radio" <?php if($gender == 3) echo "checked='checked'"; ?> name="gender" value="3">
									other
									<dd>
										<div class="warning">
												
										</div>
									</dd>
								</div>
								<div class="entry">
									<label class="centerLabel" for="image">
										UserPic
									</label>
									<img src="<?php echo $userPic; ?>" alt="userImage" width="151" height="112" />	
									<br>
									<input class="button" type="file" name="image" id="image">
								</div>
								<input id="register" type="submit" value="update" name="submit" />
								<br>
								<div id="important">
									<div id="buttons">
										<button>
											<a href="changePassword.php">change password</a>
										</button>
										<button>
											<a href="Delete.php">delete account</a>
										</button>
										<button>
											<a href="changeEmail.php">change email</a>
										</button>
									</div>
								</div>
							</div>

						</form>
					</formWrap>
				</div>
			</div>		

<?php

	}

	//require_once("parts/footer.php");
?>
	

	</body>
</html>

