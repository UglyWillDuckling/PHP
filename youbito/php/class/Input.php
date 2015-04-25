<?php	
	
	class Input {

			public static function exists($objekt, $source = "") 
			{

				if($source == "")
				{
					$source = $_POST;
				}
				
				if(isset( $source[$objekt]) && !empty(trim($source[$objekt])) )
				{
				return true;
				}
				
			 return false;
			}								
		}
		
?>