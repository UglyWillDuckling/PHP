	<?php
	/* 'sigleton' klasa za komunikaciju s bazom podataka*/
	
		class DB {
			
			public	$_error = array(), $_count= 0;
			
			private static $_instance = null;
			private $_PDO, 
					$_query, 				
					$_results = array();
			
		//konstruktor se poziva iz 'getInstance' funkcije											
			private function __construct($root) 
			{			

				require_once("{$root}/scripts/connect.php");
				$dsn = "mysql:host=" . host . ";dbname=" . db;	

				$this->_PDO = new PDO($dsn, user, pass);
			}
			
			public static function getInstance($root = "C:/Apache24/htdocs/youbito/php") 
			{				
				if( !isset(self::$_instance) ) {					
					self::$_instance = new DB( $root );
				}
				return self::$_instance;
			}
			
			
		
			public function upit($queryType, $kveri, $param = array()) {


										
				$stmt = $this->_PDO->prepare($kveri);
				
				$x  = 1;
				while($x <= sizeof($param))
				{
					$y = $x -1;
					
					$par = &$param[$y];
					
					$stmt->bindParam($x, $par); 
					$x++;
				}
				
				if(@$stmt->execute())
				{
				//ovisno o vrsti query-ja('get' ili 'set') obavljaju se razliciti zadaci
					if($queryType === "get")
					{			

						$this->_results = array();
						$result = $stmt->fetchAll();

						if(empty($result))
						{
							
							$this->addError("neuspjelo dobavljanje podataka, mozda ne postoje");
							$this->_count = 0;
							return false;
						}
						else {	

						/*****
							 * dobivene vrijednosti se spremaju u $_results array same klase
							 *_count varijabla se azurira 
							 *_error array se resetira
						****/ 

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
					
					$this->addError("neuspjeli query BP-a");
					$greske = $stmt->errorInfo();

				//spremanje gresaka iz stmt objekta i resetiranje _count i results polja
					foreach($greske as $mistake)
						$this->addError($mistake);	
					
					$this->_count = 0;
					$this->_results = array();

					return false;
				}

				
			}// upit funkcija-kraj

			public function getResults() {

				return $this->_results;
			}
					
			private function addError($fauxpas) {
				
				$this->_error[] = "<p class='error'>" . $fauxpas . "</p>";
			}
			
			private function clearErr() {
				
				$this->_error = array();
			}

			public function getErrors($divide=", ") {
			//pretvaranje _error array-a u string	
				$greske = implode($this->_error, $divide);
				return $greske;			
		}

 //kraj klase
}
				
?>



