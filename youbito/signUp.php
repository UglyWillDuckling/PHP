<?php

		$title = "register";

		$root_folder = "C:/Apache24/htdocs/youbito";


	//header stranice, poziva start.php koji provjerava da li je korisnik logiran
		require_once("{$root_folder}/parts/header.php");

		$showForm = true;


		if(isset($_POST['submit']) 

			&& Input::exists("token", $_POST) 
			&& $_POST['token'] == $_SESSION['token'] //provjera tokena radi sprijecavanja XSRF
		  ) 
		{


			$provjeri = new Validate();


		//validate klasa prima pravila u obliku arraya
			if($provjeri->check($_POST, array("usernick"=>array("min" => '3',
															    "max" => '30',
															    "unique" => 'users',
															    "required" => 'req'
																),
												"name"=>array("min" => '3',
															  "max" => '30',
															  "required" => 'req'
															  ),
												"email"=>array("required"=> 'req',
																"email" => 'email'
															  ),
												"lastname"=>array("min" =>'3',
																  "max" => '30',
																  "required" => 'req'
																 ),
												"password"=>array("min" => '6',
																  "max" => '40',
															 	  "required" => 'req',
															 	  "reg" => "((?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,40})"
															      ),			
												"repeatPassword"=>array("match" => 'password'),
												"country"=>array("required" => 'req'),
												"gender" =>array("required" => 'req')
												))
										
				)	 
			{

				$host = "http://" . $_SERVER['HTTP_HOST'];
				$url = $host . "/youbito/index.php";


				$nick 			 = $_POST['usernick'];
				$name 			 = $_POST['name'];
				$lastname 		 = $_POST['lastname'];
				$email 			 = $_POST['email'];
				$country 		 = $_POST['country'];
				$gender 		 = $_POST['gender'];
				$password 		 = $_POST['password'];

				$activation_code = time();

		
				$password = password_hash($password, PASSWORD_BCRYPT);


				$userInfo= array($nick, $name, $lastname, $password, $email, $country, $gender, $activation_code);



			//varijable za nadzor procesa registracije s obzirom na upload slike
				$continue = true;
				$slika    = false;
				if(isset($_FILES['image']) && is_file($_FILES['image']['tmp_name']))
				{

					echo "slika spremna za upload";
					$slika = true;

					require_once("{$root_folder}/php/functions/checkImage.php");

				//provjera slike
				//vraca se array sa odredistem za upload slike	
					$ImageInfo = checkImage();


					if((int)$ImageInfo['ok'] != 1)
					{
						$continue = false;
					}
					else{
						$path = $ImageInfo['path'];
					}
				}	

			//ako slika ne valja proces registracije se zaustavlja	
				if($continue)
				{
					$register = true;

					if($slika)
					{

						//ako slika postoji ona se pokusava učitati na server
						//ako upload ne uspije register varijabla se negira
						$upload = move_uploaded_file($_FILES['image']['tmp_name'], $path);

					//ako upload nije uspio 'negiraj' register varijable
						if(!$upload)
						{	
							$register = false;
						}
						else{

							echo "uspjesan upload slike<br>";
						}
					}

					if($register)
					{
						//registriraj korisnika, namjesti varijablu $showForm, preusmjeri
						$checkReg = true;


						$db = DB::getInstance();
					//zapocinjanje transakcije
						$db->upit("set", "BEGIN WORK;");

						$kveri = "INSERT INTO users(usernick, name, lastname, password, email, country, gender, activation_code)
								  VALUES(?, ?, ?, ?, ?, ?, ?, ?)";  

					//insert podataka novog usera u bazu podataka
						if($db->upit("set", $kveri, $userInfo))
						{

						//updejt tablice sa slikama korisnika ako je slika uploadana
							if($slika)		
							{
								$kveri2 ="INSERT INTO user_pics(user, path) 
										  VALUES(?, ?)";

								if(!$db->upit("set", $kveri2, array($nick, $path)))
								{
									$checkReg = false;
									echo "pic insert failed<br>";
								}
							}
							else {

								$picPath = "images/default.jpg";

								$picQuery ="INSERT INTO user_pics(user, path) 
										 	VALUES(?, ?)";

								if(!$db->upit("set", $picQuery, array($nick, $picPath)))
								{
									$checkReg = false;
									echo "default pic insert failed";

								}
								else {

								}
							}
						}
						else 
						{		
							echo $db->getErrors();		
							$checkReg = false;
						}


		/***************************PROVJERA*************************************/


					//ako registracija ne uspije obrisi uploadanu sliku
						if(!$checkReg)
						{

							$db->upit("set", "ROLLBACK;");

						//brisanje uploadane slike (u slučaju da je uploadana)
							if($slika)	
							{
							unlink($path);
							}
						}
						else {


						//ako je sve proslo  u redu posalju email korisniku na unesenu adresu		
							require_once("send_confirmation_email.php");
							

							$activation_successfull = send_confirmation_email($email, $activation_code);

							if($activation_successfull)
							{
								$db->upit("set", "COMMIT;");
								echo "<br><p class='uspjeh'>
											Vas račun je uspješno napravljen te je na vasu email adresu poslan 
											email za aktivaciju vašeg računa
											</p>";


								//u slučaju uspjesne registracije ne prikazuj form					
									//$showForm = false;

								//preusmjeravanje na pocetnu stranicu
									//header("Location {$url}");
							}
							else {

								echo "neuspjesno poslan mail<br>";
								$db->upit("set", "ROLLBACK;");							
							}
								
						}
					}
					else {

					//neuspio upload slike, nemoguce nastaviti sa registracijom
						echo "neuspio upload slike<br>nemoguce nastaviti sa registracijom";
					}
				}
				else {

				//nesto nije u redu ss slikom, ispisi greske dobivene iz funkcije checkImage()
					print_r( $ImageInfo['error']);
				}

			}
			else{

				echo "<div class='error'>" . 
						$provjeri->getErrors() .
					 "<br>
					 </div>";
				$showForm = true;
			}
		}

		if($showForm)
		{


			$token = base64_encode(openssl_random_pseudo_bytes(32) );
			$_SESSION['token'] = $token;
?>

			<div id="content">
			<h3>Register your account</h3>
				<div id="signWrap">
					<formWrap>
						<form enctype="multipart/form-data" action="<?php echo clean($_SERVER['PHP_SELF']); ?>" class="signUpForm" method="POST">
							<input type="hidden" name="token" value="<?php echo $token; ?>">
							<div id="inputWrap">		
								<label class="centerLabel" for="nick">
									Nickname
								</label>
								<div class="entry">
									<input type="text" id="usernick" name="usernick" size="30" maxlength="40" />
									<dd>
										<div class="warning">
											
										</div>
									</dd>
								</div>						
								<label class="centerLabel" for="name">
									Name	
								</label>
								<div class="entry">
								<input type="text" id="name" name="name" size="30" maxlength="40">
									
									<dd>
										<div class="warning">
												
										</div>
									</dd>
								</div>		
								<label class="centerLabel" for="lastname">
									Lastname
								</label>
								<div class="entry">	
								<input type="text" id="lastname" name="lastname" size="30" maxlength="40">
								
									<dd>
										<div class="warning">
												
										</div>
									</dd>
								</div>
								<label class="centerLabel" for="email">
									Email
								</label>
								<div class="entry">	
								<input type="email" id="email" name="email" size="30" maxlength="40">
								
									<dd>
										<div class="warning">
												
										</div>
									</dd>
								</div>	
								<label class="centerLabel" for="pass">
									Password
								</label>
								<div class="entry">				
								<input type="text" id="password" name="password" size="30" maxlength="40">
								
									<dd>
										<div class="warning">
											
										</div>
									</dd>
								</div >				
								<label class="centerLabel" for="repeatPass">
									Repeat Password
								</label>	
								<div class="entry">			
								<input type="password" id="repeatPass" name="repeatPassword" size="30" maxlength="40">
								
									<dd>
										<div class="warning">
												
										</div>
									</dd>	
								</div>
								<label for="country">
									Zemlja
								</label>

									<select id="country" name="country">	

										<?php 

										//dobavljanje zemalja za izbor korisniku
											$db = DB::getInstance();

											$kveri = "SELECT country_id, name FROM countries";
											$db->upit("get",$kveri);

											if($db->_count)
											{	

												$countries = $db->getResults();
												foreach($countries as $country)
												{

													$zemlja = $country['name'];								
													$id 	= $country['country_id'];
										?>
											<option value="<?php echo $id; ?>">
												<?php echo $zemlja; ?>
											</option>
										<?php
												}
											}
										?>
									</select>	

								
								<div class="entry">	
									<label for="sex">Gender</label>
									<input type="radio" name="gender" value="1">male
									<input type="radio" name="gender" value="2">female
									<input type="radio" name="gender" value="3">other
									<dd>
										<div class="warning">
												
										</div>
									</dd>
								</div>
								<div class="entry">
								<label class="centerLabel" for="image">
									UserPic
								</label>	
								<input type="file" name="image" id="image">
									<dd>
										<div class="warning">
												
										</div>
									</dd>
								</div>
								<input id="register" type="submit" value="register" name="submit" />
							</div>									
						</form>
					</formWrap>
				</div>
			</div>		

	<?php
		}
	?>


	</body>
</html>



