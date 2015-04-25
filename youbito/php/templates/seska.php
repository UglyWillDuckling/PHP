<?php

		$db_conn = mysqli_connect(host, user, pass, db);
		$entropy = "IVe3QRldSoHlMnzPJEopD3wkAMy7yglhRaxV6zj4b4=";


		$seska = new Zebra_Session($db_conn, $entropy);
?>