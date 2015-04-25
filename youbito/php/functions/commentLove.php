<?php


		function getCommentLove($user, $comment, $level) {

			$tablica = "";

			switch($level)
			{
				case "top":
					$tablica = "comment_love";
					break;

				case "sub":
					$tablica = "sub_comment_love";	
					break;

				default:
					$tablica = "comment_love";
					break;
			}

			$db = DB::getInstance();
			$kveri = "SELECT love FROM {$tablica} WHERE comment=? AND user=? ORDER BY love_id DESC";

			$db->upit("get", $kveri, array($comment, $user));	

			$kiss = $db->getResults();

			if(!empty($kiss))
			{

				$opinion = $kiss[0]['love'];
				return $opinion;
			}

			return false;

		}

?>



