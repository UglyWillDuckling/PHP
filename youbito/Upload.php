<?php

	$title = "upload Video";

	$root_folder = "C:/Apache24/htdocs/youbito";
//header stranice, poziva start.php koji provjerava da li je korisnik logiran
	require_once("{$root_folder}/parts/header.php");

	if($loggedIn)
	{

?>
	<div id="container">
		<p id="msg_area" class="noClass"> </p>
		<div class="drop" id="drop">

			Drop your videos here		

		</div>
		<!--progress bar html-a 5-->
			<progress id="progress" class="progress-bar" value ="0" max ="100" style="display:block; margin:auto;">
			</progress>
		
		<div id="description">
			<textarea id="opis" cols="40" rows="5">opis ovdje</textarea><br>
			<select id="cat">
				<option value="1">Games</option>
				<option value="2">Music</option>
				<option value="3">Movies</option>
			</select>
		</div>
		<div id="thumb_area" style="text-align: center;">
			<input type="file" id="thumb" name="thumb">
		</div>
	</div>
	<p id="rules">
		The videos have to be in one of the follownig formats: mov, mp4 or avi.<br>
		The size of the videos must not exceed 200MB.
		<br>
		<button type="button" id="button" name = "button" >upload video</button>
	</p>
	

	

</body>

<script type="text/javascript">
	

	(function () {

		var drop        = document.getElementById("drop");
		var predaj      = document.getElementById("button");
		var description = document.getElementById("opis");
		var category    = document.getElementById("cat").selectedIndex;
		var formData    = new FormData;
		var progress    = document.getElementById("progress");
		var thumb		= document.getElementById("thumb");
		var ucitaj = false;



		var upload = function(files) 
		{
			formData.append("video", files[0]);	
		}



		function progressHandler(event) {

			var postotak = (event.loaded / event.total) * 100;

			progress.value = Math.round(postotak);
		}

		var completeHandler = function() {
			ucitaj = false;

		}



		drop.ondrop = function(e) {

			e.preventDefault();
			this.className = "drop dragover";

			this.innerHTML = "";

			var p1 = document.createElement("span");
			var t1 = document.createTextNode("file ready to upload"); 
			p1.appendChild(t1);

			var br = document.createElement("br");
			

			var p2 = document.createElement("span");
			var t2 = document.createTextNode(" Click the button to upload the video"); 
			p2.appendChild(t2);
			

			this.appendChild(p1);//jednostavan ispis poruke da je upload file-a spreman
			
			this.appendChild(p2);

			upload(e.dataTransfer.files);//pozivanje upload funkcije koja sprema file info u formData objekt			
		}

		drop.ondragover = function() {

			this.className = "drop dragover";
			return false;
		}

		drop.ondragleave = function() {

			this.className = "drop";
			return false;
		}



		predaj.onclick	= function () {//funkcija koja prosljeduje podatke php-u

			if(!ucitaj)
			{	
				var opis = description.innerHTML;
				var cat = document.getElementsByTagName("option")[category].value;

				if(opis !=""  &&  cat!="")//potrebne dodatne provjere
				{


					var client   = new XMLHttpRequest();
					var thumb    = document.getElementById("thumb").files[0];//thumb za video	
				
					formData.append("description", opis);
					formData.append("cat", cat);
					formData.append("thumb", thumb);	

					
					client.upload.addEventListener("progress", progressHandler, false);
					client.addEventListener("load", completeHandler, false);

					client.open("POST", "videoUpload.php");
					client.send(formData);

					client.onload = function() {

						var data = JSON.parse(this.responseText);
						var msg_area = document.getElementById("msg_area");

					//s obzirom na uspjesnost operacije ispisu poruku
						if(data['success'] == 1)
						{
							
							msg_area.className = "uspjeh";
							msg_area.innerHTML = "uspjesan upload videa";
						}
						else {

							msg_area.className = "error";
							msg_area.innerHTML = "upload videa neuspio";
						}
				
					}				
				}
				else {

					alert("all fields must be submitted");
				}
			}
			ucitaj = true;
		}
	}());


</script>


<?php

	}
	else {//ako korisnik nije ulogiran preusmjerava se na pocetnu stranicu


		$host = "http://" . $_SERVER['HTTP_HOST'] . "/youbito";
		$link = $host . "/index.php";
		header("Location: {$link}");
	}

?>

</html>



