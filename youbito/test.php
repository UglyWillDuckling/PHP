<?php

	


	

?>
<html>
	<body>

	<div>
		<form id="form" method="POST" action="testUpload.php" enctype="multipart/form-data">
			<input type="file" id="thumb" name="thumb">
			<br>
		</form>
		<button id="button" >clickMe</button>
	</div>

	<div id="msg">


	</div>

	</body>
	<script type="text/javascript">

		var button 	 = document.getElementById("button");
		
		var formData = new FormData;
		var client   = new XMLHttpRequest();
		var msg 	 = document.getElementById("msg");
		var form 	 = document.getElementById("form");




		button.onclick = function(){
			
			var file = document.getElementById("thumb").files[0];			
			formData.append("thumb", file);
			console.log(file);


			client.open("POST", "testUpload.php");
			client.send(formData);
			

			client.onload = function() {

				msg.innerHTML = this.responseText;
			}

		}








	</script>
</html>