<?php
	/******* include 'start.php' skripte koja ce provjeriti login korisnika ******/
	$main_folder = "C:/Apache24/htdocs/youbito";

	require_once("{$main_folder}/php/templates/start.php");	

//Content objekt za dobavljanje svih podataka
	$cunt = Content::getInstance();

//dobavljanje svih kategorija
	$cunt->getCategories();
	$allCats = $cunt->getResults();


	$allSubs = array();
	if($loggedIn)
	{
		
		$cunt->getSubs($_SESSION['user_id']);
		$allSubs = $cunt->getResults();
	}

	$host = "http://" . $_SERVER['HTTP_HOST'] . "/youbito";
	
?>

<!DOCTYPE html>
<html lang="en-US">

<head>
	<meta charset="UTF-8">
	<title><?php echo $title; ?></title>
	<link rel="stylesheet" type="text/css" href="css/global.css"  />
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>

	<body>
	<div id="all">
		<nav class="nav-main">	
			<div class="logo">
				<a href='index.php'>Youbito</a>
			</div>
			<input type="checkbox" id="automaton" name="automaton">

		<ul id="lista">
			<li>					
				<a href="#" class="nav-item">Categories</a>
				<div class="nav-content">
					<div class="nav-sub">
						<ul>
							<?php 

							//izrada linka prema Cat.php skripti, update linka u petlji za odredenu kategoriju
								$catLink = $host . "/Cat.php?cat=";
								foreach($allCats as $cat) {
									$link = $catLink . $cat['cat_id'];
									$catName = $cat['name'];	
							?>			
									<li>
										<a href='<?php echo clean($link); ?>'>
											<?php echo clean($catName); ?>
										</a>
									</li>	

							<?php

								}
							?>
						</ul>
					</div>
				</div>
			</li>
		
			
			<li>
				<a href="#" class="nav-item">Pretplate</a>
				<div class="nav-content">
					<div class="nav-sub">
						<ul>
							<li>
								<a href="subscriptions.php?sub=0">all subscriptions</a>
							</li>
							<?php

								if($loggedIn && !empty($allSubs))
								{
									$userUrl    = $host . "/userPage.php?user_id=";
									$channelUrl = $host . "/channel.php?channel_id=";

									foreach($allSubs as $sub)
									{

										$subName = $sub['sub_name'];
										$subId   = $sub['sub_id'];



										if($sub['type'] == 'user')
										{
										$subLink = $userUrl . $subId;
										}
										else 
										{
										$subLink = $channelUrl . $subId;
										}
									
							?>	
						
								<li>
									<a href='<?php echo $subLink; ?>'><?php echo $subName; ?></a>
								</li>
							
							<?php
								}
							}
							?>

						</ul>
					</div>
				</div>
			</li>
		</ul>

		<ul id="logInList">
			<li>
			<?php 
		//ako korisnik nije logiran 			
			if(!$loggedIn):
			?>
			    <input type="checkbox" id="logBox" />	
				<label for="logBox">			
					<div  class="nav-item">Login</div>	
				</label>
				<label for="logBox" id="trickyLabel"></label>
				
				<div class="nav-content" id="logInArea">
					<div class="nav-sub">		
						<form action="login.php" id="logForm" method="POST">
							<label for="nick">nick</label><br>
							<input type="text" id="nickname" name="nick" />
							<br>
							<label for="lozinka">password</label>
							<br>
							<input type="password" id="lozinka" name="pass" />
							<input type="hidden" value="213435rwr1dw" />
							<br>
							<div id="divCheck">
								<input type="checkbox" id="checkRemember" name="remember">
								<label for="checkRemember" id="remember">remember me </label>
								<br>
							</div>	
							<input type="submit" name="submit" id="logInButton" value="logIn" />		
						</form>							
					</div>
				</div>				
			</li>
			<li>
				<a class="nav-item" id="signUp" href="signUp.php">SignUp</a>
			</li>

				<?php
					endif;

					if($loggedIn):
				?>
					<li>
						<a class="nav-item" id="signUp" href="Upload.php">Upload</a>
					</li>
				
					<li>
						<a class="nav-item" id="signUp" href="Profile.php">Profil</a>
					</li>
					<li>
						<a class="nav-item" id="signUp" href="getOut.php">Logout</a>
					</li>
				<?php
					endif;
				?>
			</ul>
			<label id="switch" for="automaton"><strong>≈</strong></label>	
		</nav>
