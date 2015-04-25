<?php

	class Validate {
				
		public $errors = array();
		public $db = null;
				
		function __construct() 
		{																
			$this->db = DB::getInstance();
		}
			
		public function check($source, $items = array() )
		{
						
			$provjera = true;
										
			foreach($items as $item => $rules)
			{
					
				if(Input::exists($item, $source))
				{										
																				
					foreach($rules as $rule => $check)	
					{
							
						switch($rule) 
						{
											
								case 'min':
								if(strlen($source[$item]) < $check)
								{
												
									$provjera = false;								
									$this->addError(" {$source[$item]} is too short, minimum of " . $check
													. " characters needed in " . $item);					
								}
								break;
											
								case 'max':
								if( strlen($source[$item]) > $check )
								{
									$provjera = false;
									$this->addError(" {$source[$item]} is too long, maximum of " . $check 											. " characters allowed in " . $item);
								}
								break;
											
								case 'match':
								if($source[$item] != $source[$check]) 
								{							
									$provjera = false;
									$this->addError($source[$item] . ' i '  . $source[$check] . ' se ne podudaraju');															
								}
								break;
											
								case 'required': 
									if( empty(trim($source[$item])) )
									{									
										$provjera = false;
										$this->addError(" vrijednost u " . $item . " ne smije biti prazna");									
									}
								break;
								
								case 'reg':
									
									if(!preg_match($check, $source[$item]))
									{
										$provjera = false;
										$this->addError(" {$item} mora sadrzavati 1 broj i malo i veliko slovo");
									}
								break;
								
								case 'email':
									if(!filter_var($source[$item], FILTER_VALIDATE_EMAIL))
									{								
										$provjera = false;
										$this->addError(" email neispravan");										
									}									
								break;
								
								case 'unique':	
								
								//kveri za provjeru postojanja $item-a u zadanoj tablici
									$kveri = "SELECT * FROM " . $check . " WHERE " . $item . " =?";							
									$param = $source[$item];

									$this->db->upit("get", $kveri, array($param));
									if($this->db->_count > 0)
									{
										$provjera = false;
										$this->addError("{$item} vec postoji u bazi podataka");
									}
								break;			
								
								default:
								$this->addError("{$rule} pravilo ne postoji");
								break;


						}
					
					}
				}
				else {
						
					$this->addError("morate unijeti " . $item);
					$provjera = false;
				}
			}
			//provjera uspjesnosti funkcije	
			if($provjera) 
			{						
				return true;
			}
			else 
			{					
				return false;
			}
		}	
		//kraj "check" funkcije
					
		private function addError($greska) 
		{
			$this->errors[] = $greska ;
		}	

		public function getErrors() {

			$greske = implode($this->errors, " ,");
			return clean($greske);
		}		
	}
	/*   kraj klase */
?>				


