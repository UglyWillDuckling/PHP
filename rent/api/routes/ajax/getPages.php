<?php


	$boot->setPost('ajax/getPages', 'post.ajax.getPages', function() use($boot){


		$db = $boot->db;

		$db->setTable('pages');

		$pages = $db->findAll()->getAll();

		echo json_encode($pages);
	}, array('admin', 'csrf'));