<?php

	require_once("Content.php");


	$inhalt = Content::getInstance();

	$inhalt->get_video_info(2);

	$resoDumb = $inhalt->getResults();

	echo "<pre>";
	print_r($resoDumb);
	echo "</pre>";


?>