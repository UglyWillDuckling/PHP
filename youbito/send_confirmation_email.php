<?php

	require_once("PHPMailer/PHPMailerAutoload.php");




	function send_confirmation_email($to, $token) {	

		$host ="http://" .  $_SERVER['HTTP_HOST'] . "/youbito";

		$confirm = $host . "/confirm.php?address={$to}&token={$token}";

		$link = "<a href='{$confirm}'>link</a>";


		$body	 = "Welcome to our new website, click on this {$link} link to activate your account";

		$mail  = new PHPMailer(); // defaults to using php "mail()"


		$mail->IsSMTP();    //using smtp server

		$mail->Host       = "smtp.gmail.com"; // SMTP server
		$mail->SMTPDebug  = 0;


		$mail->SMTPAuth   = true;                  // enable SMTP authentication
		$mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
		$mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
		$mail->Port       = 465;                   // set the SMTP port for the GMAIL server
		$mail->Username   = "vladokiller33@gmail.com";  // GMAIL username
		$mail->Password   = "obiyouwan1";   


		$subject = "confirm your account";
		

		$mail->SetFrom('vladokiller33@gmail.com', 'NoReply');
		$mail->Subject    = $subject;


		$mail->MsgHTML($body);  //setting the body of the email

		$address = "sync_99@yahoo.com";

		$mail->AddAddress($to, "new User");
		echo "Here we are<br>";
		$result = $mail -> Send();

		if(!$result) {

			return false;
		} 
		
		return true;
	}

?>