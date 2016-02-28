
<?php 

	/**
	 * Klasa za dobavljanje vrijednosti iz POST, GET, Session i FILES arrayeva
	 */
	class Request 
	{	
		
		/**
		 * motoda provjera postojanje polja i dužinu stringa u zadanom polju
		 * 
		 * @param  [string] $field ime polja
		 * @return [string, null]  vraca očišćeni 
		 */
		public static function getPost($field){
		
			if(isset($_POST[$field]) && strlen(trim($_POST[$field])))
				return clean($_POST[$field]);
				
			return null;
		}
		public static function getGet($field){
			
			if(isset($_GET[$field])  && strlen(trim($_GET[$field])))
				return clean($_GET[$field]);
		
			return null;
		}
		public static function getSession($field){
			
			if(isset($_SESSION[$field]) && strlen(trim($_SESSION[$field])))
				return clean($_SESSION[$field]);
		
			return null;
		}

		/**
		 * metoda getFile provjerava postojanje zadanog polja u _FILES arrayu 
		 * 	i veličinu datoteke 
		 * @param  [string] $field ime polja u arrayu
		 * @return [array, null] vraca ili array iz $_FILES niza ili null
		 */
		public static function getFile($field){
			
			if(isset($_FILES[$field]) && $_FILES[$field]['size'] > 0)
			{	
				return $_FILES[$field];
			}
		
			return null;
		}		
	}
?>