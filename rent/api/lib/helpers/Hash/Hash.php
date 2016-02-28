
<?php 	


	/**
	 * Hash klasa koristi statične funkcije za kreiranje i provjeru passworda
	 */
	class Hash 
	{	
		
		public static function passwordVerify($secHash, $mainHash)
		{		
			 return password_verify($secHash, $mainHash);
		}
		
		public static function password($str , $algo)
		{		
			return password_hash($str, $algo);
		}

	/*OVO NE RADI (ne znam zasto)
		public static function hash($input)
		{

			$token = md5($input);
			return $token;
		}
	
*/
	}
?>