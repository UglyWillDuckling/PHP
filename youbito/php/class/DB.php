	<?php
	/* 'sigleton' klasa DB za komunikaciju s bazom podataka*/
	
		class DB {
			
			public	$_error = array(), $_count=0;
			
			private static $_instance = null;
			private $_PDO, 
					$_query, 				
					$_results = array();
			
		//konstruktor se poziva iz 'getInstance' funkcije											
			private function __construct() 
			{				
				require_once("php/scripts/connect.php");

				try{
					$this->_PDO = new PDO('mysql:host=' . host . ';dbname=' . db, user, pass);
				}
				catch(PDO_Exception $e){

					echo "Connection failed" . $e->getMessage();
				}
			}
			
			public static function getInstance() 
			{				
				if(!isset(self::$_instance)) {
				//ako klasa nije jos niti jednom bila instancirana stvara se novi DB objekt
					self::$_instance = new DB();
				}
				return self::$_instance;
			}
			
			
		
			public function upit($queryType, $kveri, $param = array()) {
										
				$stmt = $this->_PDO->prepare($kveri);
				
				$x  = 1;
				while($x <= sizeof($param))
				{
					$y = $x -1;
					
					$par = &$param[$y];	//za starije verzije PHP-a potrebno koristiti reference			
					$stmt->bindParam($x, $par); 
					$x++;
				}//preko while petlje 'vezu se' se parametri iz $param array-a za stmt. objekt
				
				if(@$stmt->execute())
				{
				//ovisno o vrsti query-ja('get' ili 'set') obavljaju se razliciti zadaci
					if($queryType === "get")
					{			

						$this->_results = array();
						$result = $stmt->fetchAll();
							
						if( empty($result) )
						{
							
							$this->addError("neuspjelo dobavljanje podataka, mozda ne postoje");
							$this->_count = 0;
							return false;
						}
						else {
								
							$this->_count = sizeof($result);					
							
							$this->_results = $result;
							$this->clearErr();
							return true;
						}
					}
					else
					{
						$this->clearErr();
						return true;						
					}
				}
				else {
					
					$this->addError("greska kod query-ja");
					return false;
				}

				
			}// upit funkcija-kraj
					
			private function addError($fauxpas) {
				
				$this->_error[] = "<p class='error'>" . $fauxpas . "</p>";
			}
			
			private function clearErr() {
				
				$this->_error = array();
			}
			public function getResults() 
			{

				return $this->_results;
			}	
		}//kraj DB klase
				
?>



