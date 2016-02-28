<?php 
namespace Bubica\User;	
	
	/**
	 * User klasa se ne koristi u projektu
	 */
	class User extends DB
	{	
		protected $table = "users";	
		public $userType;
		
		public function isAdmin(){
			
			if(
			isset($this->result['user_level']) &&
			$this->result['user_level'] > 1
			) 
			 return true;
						
			return false;
		}
		
		/**
		 * funkcija za provjeru razine admina
		 * @return [string] vraca admin ili superadmin string
		 */
		public function adminLevel(){
			
			
			if( $this->isAdmin() )
			{			
				return ($this->result['user_level'] == 1) ? 'admin' : 'superAdmin';
			}
		}
	}
?>