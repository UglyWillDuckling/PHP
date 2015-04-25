<?php
	
	$root_folder = "C:/Apache24/htdocs/youbito";

	require_once("{$root_folder}/php/templates/start.php");	


	$host = "http://" . $_SERVER['HTTP_HOST'] . "/youbito";

	if($loggedIn && isset($_POST['submit']))

	{

		if(Input::exists("comment", $_POST) && Input::exists("vid", $_GET) && Input::exists("level", $_GET))
		{

			$level   = (int)clean($_GET['level']);
			$commentsTable = ($level == 1) ? "comments" : "sec_comments";


			$video   = (int)clean($_GET['vid']);
			$comment = $_POST['comment'];
			$user    = $_SESSION['user_id'];

			
			$db = DB::getInstance();
			$kveri = "INSERT INTO {$commentsTable}(user, content, video) values(?, ?, ?)";

			
			if($db->upit("set", $kveri, array($user, $comment, $video)) )
			{

				echo "Comment successfully submitted.";

				$link = $host . "/watch.php?vid={$video}";
				header("Refresh: 1; url={$link}");
			}
			else{

				$link = $host . "/watch.php?vid={$video}";
				echo "commenting failed";
				echo $db->getErrors;

				header("Refresh: 3; url={$link}");			
			}


		}
		else{
			echo "fail";

			$link = $host . "/index.php";
			header("Refresh: 2; url={$link}");
		}
	}
	else{

		$link = $host . "/index.php";
		header("Location: {$link}");
	}

?>