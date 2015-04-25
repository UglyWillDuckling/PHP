<?php

	
	function showPages($total_rows, $currentPage, $onePage)
	{

		$total_pages = ceil($total_rows/$onePage);

		$strelice = "<p class='arrows'>";
		$numeros = "<p class='pageNumbers'>";


		if($currentPage > 1)
		 {
		 	$prevPage = $currentPage -1;
		 	$address = $_SERVER['PHP_SELF'] . "?curr={$prevPage}";
		 	$link = "<a href='" . $address . "'>&lt; </a>"; 
		 	$strelice .= $link;
		}
		else {

			$strelice .= " &lt; ";
		}

		if($currentPage < $total_pages)
		{
			$nextPage= $currentPage +1;
			$address = $_SERVER['PHP_SELF'] . "?curr={$nextPage}";
			$link = "<a href='" . $address . "'>&gt; </a>";

			$strelice .= $link;
		}	
		else {

			$strelice .= "&gt; ";
		}

		$x = 1;

		while($x <= $total_pages)
		{
			if($x != $currentPage)
			{
				$address = $_SERVER['PHP_SELF'] . "?curr={$x}";
				$linkNum = "<a href='" . $address . "'>{$x} </a>";

				$numeros .= $linkNum;

			}

			else {

				$numeros .= $currentPage . " ";
			}
			$x++;

		}		
		
		$strelice .= "</p>";
		$numeros .= "</p>";
	
		echo $strelice;
		echo $numeros;	
	}	
	
	function pages($table, $current, $perPage) {
		
		if($current > 1)
		{
			$veza = mysqli_connect(host, user, pass, db);		
			
			$retour = mysqli_query($veza, "SELECT * FROM {$table}");
			
			$zahl_rows = $retour->num_rows;
			
			showPages($zahl_rows, $current, $perPage);
		}		
	}
			
?>

