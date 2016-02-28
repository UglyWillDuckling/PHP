<?php


	$boot->setPost('admin/ajax/deletePage', 'post.admin.ajax.deletePage', function() use($boot){


		$pageId = Request::getPost('pageId');

		if($pageId && ctype_digit($pageId)){
			
			$db = $boot->db;
			
			$db->startTransaction();

			$delPage = $db->query("DELETE FROM pages WHERE id=" . $pageId);
			if($delPage){
				$delArgs = $db->query("DELETE FROM arguments WHERE page_id=" . $pageId);

				if($delArgs){

					echo "1";
					$db->commit();
				}
				else{

					echo "2";
					$db->rollback();
				}
			}
			else{

				echo "3";
			}		
		}	
	}, array('admin', 'csrf'));
		
