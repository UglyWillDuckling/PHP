<?php

//naziv stranice
	$title = 'watch';
	$root_folder = "C:/Apache24/htdocs/youbito";

//header stranice, poziva start.php koji provjerava da li je korisnik logiran
	require_once("{$root_folder}/parts/header.php");

//provjera postojanja get zahtjeva(broj videa)
	if(Input::exists('vid', $_GET)) {
		
		$vid_id = clean($_GET['vid']);
	}
	else{

		header("Location: index.php");
		exit();
	}

//funkcija za dobavljanje slike korisnika
	require_once("{$root_folder}/php/functions/getUserImage.php");

//funkcija za odredivanje je li i kako je korisnik ocijenio video
	require_once("{$root_folder}/php/functions/love.php");

//funkcija za provjeru ocijene komentara od strane korisnika
	require_once("{$root_folder}/php/functions/commentLove.php");


//content klasa za pribavljanje raznih vrsta podataka
	$cunt = Content::getInstance();


//dobavljanje informacija o videu
	$cunt->get_video_info($vid_id);

	$hardVid    =   $cunt->getResults();
	$videoInfo 	=	$hardVid[0];

//spremanje informacija o videu u varijable
	$showInfo=false;
	if(!empty($videoInfo))
	{
		$videoId 			= $videoInfo['id'];
		$videoName    		= $videoInfo['name'];
		$videoLike    		= $videoInfo['like'];
		$videoDislike		= $videoInfo['dislike'];
		$videoMade    		= date("D jS, Y", strtotime($videoInfo['made']));
		$videoDescription   = $videoInfo['descript'];
		$videoViews   		= $videoInfo['views'];
		$videoOwnerId		= $videoInfo['user'];
		$ownerNick			= $videoInfo['nick'];
		$videoCat			= $videoInfo['cat'];
		$videoCat_id		= $videoInfo['cat_id'];

	//link prema stranici vlasnika videa
		$ownerLink  = "http://" . $_SERVER['HTTP_HOST'] . "/youbito";
		$ownerLink .= "/userPage.php?user_id=" . $videoOwnerId;

	//link prema kategoriji videa
		$catLink  = "http://" . $_SERVER['HTTP_HOST'] . "/youbito";
		$catLink .= "/category.php?cat_id=" . $videoCat_id;
		$showInfo = true;
	}


//slika korisnika vlasnika videa, ako nema slike stavi default
	$url_userPic = getUserImage($videoInfo['user']);
	if(!$url_userPic) $url_userPic = "images/default.jpg";
	
//podaci o ocjeni videa
	if($loggedIn)
	{
	$loved = getLove($_SESSION['user_id'], $videoId);	
	}



//dobavljanje komentara na video
	$showComments = false;
	$cunt->getComments($vid_id);
	$comments = $cunt->getResults();
	
	if($comments)
	{
		$showComments = true;	
	}
	
//varijabla za nadzor ispisa komentara
		$escri = false;
		if(sizeof($comments))
		{
			$escri = true;
		}

	//dobavljanje sličnih videa	
		$cunt->get_similar_videos($videoOwnerId, $videoCat, $videoName);
		$simVids = $cunt->getResults();

	//provjera loggedIn-a i provjera pretplate korisnika na kreatora videa radi ispisa linka 
		$subscribed = false;
		if($loggedIn)
		{
		
			$pretplate = $cunt->getSubs($_SESSION['user_id']);

			$pretplate = $cunt->getResults();
			if(!empty($pretplate))
			{
				foreach($pretplate as $sub)
				{

					if($sub['owner'] == $videoOwnerId)
					{
						$subscribed = true;
					}
				}
			}
		}
		
?>

		<div class="allVid">
			<div class='sadrzaj'>	

				<div id='vidContent'>
					<div class="vidWrap">
						<video id="vid1" width="640" height="480"  controls>
							<source	src='<?php echo $videoInfo['path']; ?>' type="video/mp4">
							Vas browser ne podrzava video tagove(html5)
						</video>			
					</div>	
					
					<div id="main-vid">
						<div id='vid-info'>
							<h2> 
								<?php echo ucwords($videoName); ?> The New Best Thing
							</h2>
							<div class="vidInfoWrapper">
								<div class="videoImage">
									<img alt="userImg" src="<?php echo $url_userPic; ?>" width="80" height="80">

								</div>
								<div class="video-podaci">
									<span>
										<a href="<?php echo $ownerLink; ?>">
											<strong>
												<?php echo $ownerNick; ?>		
											</strong>
										</a>
									</span>											
										<span class="subscribe">

											<?php 
												if(!$subscribed && $loggedIn)
												{
											?>

											<a href='<?php echo $subLink; ?>'>
												subscribe
											</a>

											<?php
												}
												elseif($loggedIn)
												{
											?>

											subscribed

											<?php
												}
												else{
											?>

											subscribe
											<?php
											}
											?>	
				
										</span>																	
									</span>
									<span class="likeMe">
											<div class="pogledi">											
												<?php echo $videoViews; ?> views
											</div>
											<div class="love_hate">
												<?php 

													
													if($loggedIn) {

														$korisnikId = $_SESSION['user_id'];
														$loveLink = "http://" . $_SERVER['HTTP_HOST'];
														$loveLink.= "/youbito/videoLove.php?vid_id={$videoId}&love=";

														if($loved)
														{
															
														//ako je korisnik "volio" video nudi mu se opcija da to ponisti
															if($loved == 1)
															{
														
																$loveLink.= "2";
																$love = "☝	{$videoLike}
																		 <a class='likan' href='{$loveLink}'>
																		 	☟ {$videoDislike}
																		 </a>";			
															}	
															else {
			
																$loveLink.= "1";
																$love = "<a class='likan' href='{$loveLink}'>
																		 	☝ {$videoLike}
																		 </a>
																		    ☟ {$videoDislike}
																		 ";
															}								
														}
														else{

													//u slučaju da korisnik nije jos ocijenio video ima mogucnost kliknuti na oba linka
															$loveLink1 = $loveLink . "1";	
															$love ="<a class='likan' href='{$loveLink1}'>
																	  ☝ {$videoLike}
																	</a>
																	";
															$loveLink2 = $loveLink . "2";				
															$love .= "<a class='likan' href='$loveLink2'>
																	  ☟ {$videoDislike}
																	 </a>
																	";
														}

														echo $love;
													}	
													else {
		
												?>

													☝ <?php echo $videoLike; ?>
													☟ <?php echo $videoDislike; ?>
													
												<?php 
														}
												?>
											</div>
									</span>	
								</div>
								<hr>
								<div class="video-bottom">
									<span class="out">
										<strong>Published on <?php echo $videoMade; ?></strong>
									</span>
									<span class="opis">
										<?php echo $videoDescription; ?>
									</span>
									<div class="category">
										Categories 
										<a href='<?php echo $catLink; ?>'>
											<?php echo $videoCat; ?>
										</a>
									</div>
								</div>

							</div>
						</div>


		<!--**********KOMENTARI************************-->	




						<ul class="comments">
							<li id="allComments">
								<span>
									All Comments (<?php echo sizeof($comments); ?>)
								</span>
							</li>
							<li>
								<hr>
							</li>
					<?php

						if($loggedIn)
						{
							$submitLink  = "http://" . $_SERVER['HTTP_HOST'] . "/youbito";
							$submitLink .= "/submitComment.php?vid={$videoId}&level=1";

							$userImage = getUserImage($_SESSION['user_id']);
					?>


						<div id="commentImage">
							<img alt="userImage" src="<?php echo $userImage; ?>">
						</div> 		
						<li>
							<form id="commentForm" action="<?php echo clean("$submitLink"); ?>" method="POST">	
									<textarea id="commentField" name="comment" rows="4" cols="60">
										type something in, pleeeeease
									</textarea>	
									<br>
									<input type="submit" name="submit" value="comment" />
								</fieldset>
							</form>	
						</li>

					<?php

						}
						if($showComments)
						{
							for($x=0; $x < 5; $x++)
							{	
							foreach($comments as $komentar)
							{

						//ako je korisnik ulogiran daje mu se mogucnost da ocijeni pojedini komentar	
								if($loggedIn)
								{ 
									$korisnikId = $_SESSION['user_id'];
									$voli = getCommentLove($korisnikId, $komentar['comment_id'], "top");

									$evalCommentLink = "http://" . $_SERVER['HTTP_HOST'] . "/youbito";
									$evalCommentLink 
									.= "/evalComment.php?vid={$videoId}&level=1&comment={$komentar['comment_id']}&love=";

								}	

							//link prema slici korisnika koji je objavio komentar
								$imgUrl    = getUserImage($komentar['user']);	
								$userLink  = $_SERVER['HTTP_HOST'] . "/yubito";
								$userLink .= "/userPage.php?user_id=" . $komentar['user']; 

					?>	

								<li>
									<div class="tableu">
										<img 
											src="<?php echo $imgUrl; ?>" alt="commentPic" height="50" width="50"
										 />		
									</div>
									<div class="comment">	
										<span class="shoutName">
											<a href='<?php echo $userLink; ?>'>
												<strong><?php echo $komentar['nick']; ?></strong>
											</a>
											<div class="sub_to_user">
												<div class="upper_sub">
													
												</div>
												<div class="lower_sub">									
													<div class="sub_user_name">
													<a href="<?php echo $userLink; ?>">
														<?php echo $komentar['nick']; ?>
													</a>
													</div>
													<div class="sub_sub">
														<a href="#">
															<?php echo "subscribe"; ?>	
														</a>	
													</div>
												</div>	
											</div>
										</span>
										<span class="shoutContent">
											<?php echo $komentar['content']; ?>
										</span>
										<span class="loveHate">

											<?php
												if($loggedIn)
												{
													
													$amour = "";
													if($voli == 1)
													{
														$amour = "☝ " . $komentar['likes'] . " 
																 <a href='{$evalCommentLink}2'>
																	☟ {$komentar['dislikes']}
																 </a>";
													}	
													elseif($voli == 2)
													{
														$amour = "<a href='{$evalCommentLink}1'>
																	 ☝ {$komentar['likes']}  	
														  		  </a>	  
																  ☟ {$komentar['dislikes']}
																 ";

													}
													else{
														$amour = "<a href='{$evalCommentLink}1'>
																	 ☝ {$komentar['likes']}  	
														  		  </a>
														  		  <a href='{$evalCommentLink}2'>
																	☟ {$komentar['dislikes']}
																 </a>";
													}

													echo $amour;	
												}
												else{

											?>		

											☝ <?php echo $komentar['likes']; ?>
											☟ <?php echo $komentar['dislikes']; ?>

											<?php
												}
											?>

										</span>
									</div>


									<?php

										if(!empty($komentar['sec'])):
											foreach($komentar['sec'] as $subKomentar)
											{
												$userSubImage = getUserImage($subKomentar['user']);
												$subUserLink = "http://" . $_SERVER['HTTP_HOST'] . "/youbito";
												$subUserLink .= "/userPage.php?user_id=";
												$subUserLink .= $subKomentar['user'];

												$aime = false;
												if($loggedIn)
												{
													$aime = getCommentLove($korisnikId, $subKomentar['comment_id'], "sub");
												
												}	
									?>

											<div class="subWrap">
												<div class="subTableu">
													<img 
														 alt="subImage" 
														 src="<?php echo $userSubImage; ?>"
														 width="40" height="40" />
												</div>		
												<div class="subComment">
													<span class="shoutName">
														<a href="<?php echo $subUserLink; ?>">
														<strong>				
															<?php echo $subKomentar['nick']; ?>
														</strong>
														</a>
													</span>		
													<span class="shoutContent">
														<?php echo $subKomentar['content']; ?>	
													</span>
													<div class="loveHate">
													
													<?php

														if($loggedIn)
														{
														//link prema skripti za ocijenjivanje komentara
															$subEvalLink = "http://" . $_SERVER['HTTP_HOST'];
															$subEvalLink 
															.= 
															"/youbito/evalComment.php?vid={$videoId}&level=2&comment={$subKomentar['comment_id']}&love=";	


															$subLove = "";
						
														//ako se korisniku svidio video
															if($aime == 1)
															{
																
																$subLove = "☝ {$subKomentar['likes']}
																			<a href='{$subEvalLink}2'>
																			 	☟ {$subKomentar['dislikes']}
																			</a>";

															}
															elseif($aime == 2)
															{
																

																$subLove = "<a href='{$subEvalLink}1'>
																				☝ {$subKomentar['likes']}
																			</a>
																			☟ {$subKomentar['dislikes']}";

															}
															else
															{

																$subLove = "<a href='{$subEvalLink}1'>
																				☝ {$subKomentar['likes']}
																			</a>
																			<a href='{$subEvalLink}2'>
																				☟ {$subKomentar['dislikes']}
																			</a>";	
															}

															echo $subLove;

														}
														else {

													?>

														☝ <?php echo $subKomentar['likes']; ?>
														☟ <?php echo $subKomentar['dislikes']; ?>

													<?php
														
													}
													?>

													</div>
												</div>		
											</div>

									<?php
											}
										endif;
									?>


								</li>

						<?php
							}
						}
						}
						?>
							
						</ul>
					</div>
				</div>


				<div id="vidRight" class="side-container">
					<h3 class="title">similar videos</h3>
					<hr>
					<ul class="side-list">

					<?php


					if(!empty($simVids))
					{
						for($x=0; $x <1; $x++)
						{
							foreach($simVids as $vid)
							{

							//vrijednosti pojedinacnog videa se (obraduju) spremaju u varijable 			
								$thumb    = $vid['thumb'];
								$vidName  = $vid['name'];
								$vidOwner = $vid['user'];
								if(empty($vidOwner)) $vidOwner = "noName";
								$views 	  = $vid['views'];
								$simId 	  = $vid['id'];

								$ownerId  = $vid['user_id'];

								$userLink = "http://" . $_SERVER['HTTP_HOST'] . "/youbito";
								$userLink .= "/userPage.php?user_id=" . $ownerId;

					?>		

								<li class="vid-list">
									<div class="stvar-container side-container-video">
										<div class="rec-info">
											<span class="titl">	
												<h3>							
													<a title="<?php echo $vidName; ?>" href='<?php echo clean($_SERVER['PHP_SELF']) . "?vid={$simId}"; ?>'>
														<?php echo substr($vidName, 0, 35); ?>
													</a>					
												</h3>
											</span>
											<span class="vid-thing">
												by 
												<a href='<?php echo $userLink; ?>'>
													 <?php echo $vidOwner; ?>
												</a>
											</span>
											<span class="vid-thing">
												<?php echo $views; ?> views 
											</span>

										</div>
										
									</div>
									<div class="thumb-container">
										<img src="<?php echo $thumb; ?>" alt="picturepicture" width="34" height="42">						
									</div>
								</li>
						<?php
								}
							}
						}
						else {		
						?>
							<li>
								<span>no similar videos</span>
							</li>
						<?php } ?>	

					</ul>
			</div>
			<?php
				require("parts/footer.php"); //footer stranice
			?>	
			</div>
		</div>
	</div>
	<script language="javascript" type="text/javascript">

		var change = true;

		function changeSize() { 

			var myvid        = document.getElementById("vid1"); 
			var sidebar      = document.getElementById("vidRight"); 
			var vidContainer = document.getElementsByClassName("vidWrap"); 

			if(change)
			{
				
				myvid.style.width = "854px";
				myvid.style.height = "534px";
				myvid.style.background = "#fff";
		
				sidebar.style.margin = "400px 80px 0px 0px";

				vidContainer[0].style.margin = "0px 200px 0px -125px";
				vidContainer[0].style.padding = "0px 165px";
				vidContainer[0].style.background = "#fff";
				vidContainer[0].style.width = "100vw";


				change = false;
			}
			else{

				myvid.style.width= "100%";
		
				sidebar.style.margin = "10px 80px 0px 0px";
						
				vidContainer[0].style.margin = "10px 0px 0px 0px";
				vidContainer[0].style.padding = "0px";
				vidContainer[0].style.background = "#222";

				change = true;
			}
		}

	</script>
</body>
</html>


