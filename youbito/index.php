<?php 
/******* Dobavljanje headera stranice ******/
	
	$title = "youbito";

	$root_folder = "C:/Apache24/htdocs/youbito";
//header stranice, poziva start.php koji provjerava da li je korisnik logiran
	require_once("{$root_folder}/parts/header.php");

//funkcija za sliku korisnika
	require_once("{$root_folder}/php/functions/getUserImage.php");	

//objekt za dobavljanje sadrzaja potrebnog na ovoj stranici
	$inhalt = Content::getInstance();	

	$inhalt->getVideos();
	$allVids = $inhalt->getResults();
	

	$showVids = false;
	if(!empty($allVids)) 
	{	
		$showVids = true;
	}

?>
	
	<main class="content">
			<div id='videoTop'>
				<h3 >Videos</h3>
				<div id="search">
					<form action="" method="POST">
						<input type="text" id="trazi" size="22" />
						<button type="submit" name="submit" id="submit" value="fsdfdsf">
							☠
						</button>
					</form>
				</div>
			</div>
		<hr id="vidHR">
			<div id="vids">
				<ul class="content-list">

				<?php 

				if($showVids) {

					for($x=0; $x<12; $x++)
					{
						foreach($allVids as $video)
						{
						
							$host = $_SERVER['HTTP_HOST'];
							$urlVid = $host ."/youbito/watch.php?vid=";	
							$vidLink = "http://" . $urlVid . $video['video_id'];

						//link za stranicu korisnika vlasnika videa
							$host = $_SERVER['HTTP_HOST'] . "/youbito/userPage.php?user_id=";
							$userLink = "http://" . $host . $video['user_id'];


						//dobavljanje slike korisnika preko funkcije
							if(!$user_image_url = getUserImage($video['user_id']))
							{

							//ako slike nema, odnosno korisnik je obrisan, prikazuje se default slika	
								$user_image_url = "php/pics/profile_images/default.jpg";

							}

						//link za thumb videa
							$thumb_source = $video['thumb'];	
				?>	

				<li class="list-item">
					<span class="autor">
						<img class="user_pic" alt='user_pic'  src='<?php echo $user_image_url; ?>' width="25" height="25" />
						<a href='<?php echo $userLink; ?>'>
							<?php echo $video['nick']; ?>
						</a>
					</span>
					<div class="vid-wrap">
							<div class="left-main">
								<img src="<?php echo $thumb_source; ?>" alt="thumbnail" class="vid-image" width="190" height="110"  />		
							</div>

							<div class="right-main">
								<span class="side-info side-title">
									<a class="vidLinks" href='<?php echo clean($vidLink); ?>'>
										<?php echo clean($video['name']); ?>		
									</a>	
								</span>
								<span class="side-info">by <?php echo clean($video['nick'])?>
								</span>
								<span class="side-info"><?php echo clean($video['made']); ?> ago .

									views <?php echo clean($video['views']); ?> .  
									<?php echo clean($video['like']); ?>				
										likes
										<?php echo clean($video['dislike']); ?>
										dislikes	
								</span>
								<span class="side-info mirage"><strong>Description: </strong>
									<?php echo clean($video['descript']); ?>	
								</span>


								
							</div>
							<span class="side-info2">
								<strong>Description</strong>: Some cool
								<?php echo clean($video['descript']); ?>
							</span>					
						</div>	
							
					</li>

					<li class="divide">
						<hr>
					</li>
				<?php

						}
					}
				}
				else {
					
					echo "no videos available";
				}

				?>							
	</ul>
	</div>
	</main>
	
	<aside class="side-container">
		<h5 class="title">Recomendations</h5>
		<ul class="side-list">
			<li>
				<div class="stvar-container">
				<img src="images/free.jpg" alt="picturepicture" width="34" height="42">
					<span class="titl">
						<a>
							<h3>							
							 	Best channel in th...						
							</h3>
						</a>
					</span>
				<a href="#">
				<span class="thingy">Subscribe</span>
				</a>
				</div>
			</li>	
			<li>
				<div class="stvar-container">
				<img src="images/goodly.png" alt="picturepicture" width="34" height="42">
				<span class="titl">
					<a>
						<h3>							
						 	Best channel in th...						
						</h3>
					</a>
				</span>
				<a href="#">
				<span class="thingy">Subscribe</span>
				</a>
				</div>
			</li>
			<li>
				<div class="stvar-container">
				<img src="images/goodly.png" alt="picturepicture" width="34" height="42">
				<span class="titl">
					<a href="#">
						<h3>							
						 	Best channel in th...							
						</h3>
					</a>
				</span>
				<a href="#">
				<span class="thingy">Subscribe</span>
				</a>
				</div>
			</li>				
			<li>
				<div class="stvar-container">
				<img src="images/goodly.png" alt="picturepicture" width="34" height="42">
				<span class="titl">
					<a href="#">
						<h3>							
						 	Best channel in th...							
						</h3>
					</a>
				</span>
				<a href="#">
				<span class="thingy">Subscribe</span>
				</a>
				</div>
			</li>
		</ul>
	</aside>
</div>

<?php 
	require("parts/footer.php"); //footer stranice
?>

</body>
</html>