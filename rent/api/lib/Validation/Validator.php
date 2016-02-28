
<?php use Violin\Violin;
	

	/**
	 * Klasa Validator produžuje klasu Violin(author: Alex Garrett)
	 * 'Validator' nam služi za provjeru unesenih vrijednosti od strane korisnika i sl.
	 * vrijednosti se validiraju preko funkcije validate() kojoj zadajemo ime 
	 * vrijednosti koje će  se koristiti u samom objektu, te zadajemo array 
	 * sa varijablom za provjeru i pravila koja treba primjeniti za danu vrijednost
	 * 
	 */
	class Validator extends Violin
	{

		public function __construct($boot){

			$this->addRuleMessage(
				'image', 
				'slika nije u redu.'
			);

			$this->addRuleMessage(
				'uniqueUser', 
				'korisničko ime već postoji'
			);

			$this->addRuleMessage(
				'argumenti', 
				'potrebni argumenti nisu uneseni'
			);

			$this->addRuleMessage(
				'uniqueZaposlenik',
				'{field} vec koristeno.'
			);

			$this->addRuleMessage(
				'required', 
				'morate unijeti {field}'
			);
			$this->addRuleMessage(
				'min', 
				'{field} mora sadrzavati najmanje {input} znakova'
			);

			$this->boot = $boot;
		}
		

		/**
		 * validacija slike
		 * provjeravamo da li uploada slike uopće bilo, ako jest provjeravamo 
		 * veličinu i tip datoteke
		 * 
		 * @param  [type] $slika [description]
		 * @param  [type] $input [description]
		 * @param  [type] $args  [description]
		 * @return [type]        [description]
		 */
		public function validate_image($slika, $input, $args){

		//ako slika nije array znači da uploada slike nije niti bilo
			if(is_array($slika)){

			//slika je array		
				$imageName = $slika['name'];
				$imageTmp  = $slika['tmp_name'];

				$ext = strtolower( pathinfo($slika['name'], PATHINFO_EXTENSION) );


				$sizeLimit = $args[0]*1000*1000;
				$imageSize = $slika['size'];

			//provjera veličine
				if($imageSize < $sizeLimit)
				{				

				//provjera ekstenzije
					$allowedExt = array('jpeg', 'jpg', 'png', 'gif');

					$extOk = false;
					if( in_array($ext, $allowedExt) ){

						$tip = image_type_to_mime_type( exif_imagetype($slika['tmp_name']) );

						foreach($allowedExt as $aEx)
						{
							if( strpos($tip, $aEx) !== FALSE )
							{
								$extOk = true;
								break;
							}
						}
					}
						
					if($extOk)
					{
						return true;
					}
					else
					{
					//ovisno o grešci postavljamo odgovarajuću poruku
						$this->addRuleMessage(
							'image',
							'slika mora biti u jpg, png ili gif formatu.'
						);			
					}		
				}
				else
				{
					$this->addRuleMessage(
						'image', 
						'velicina je : ' . $slika['size'] . '<br>slika ne smije biti veca od ' . $args[0] . 'MB'
					);			
				}

				return false;
			}

			return true;
		}//validate_image
		 
		public function validate_futureDate($date, $input, $args){

			$this->addRuleMessage(
					'date', 
					'datum rezervacije mora biti u budućnosti.'
			);	

			$givenTime = strtotime($date);

			$sutra = strtotime('tomorrow');

			//datum mora biti u budućnosti
			if($sutra <= $givenTime)
				return true;

			return false;
		}

		/**
		 * metoda za provjeru jednistvenosti zadanog username-a
		 * 
		 * @param  [string] $username dani username
		 * @param  [type] $input  
		 * @param  [type] $args     
		 * @return [bool] vraca true, false ovisno da li username postoji u bazi
		 */
		public function validate_uniqueUser($username, $input, $args){

			if($args[0] == 'update')
			{
				if($this->boot->member['username'] == $username)
					return true;
			}

			$db = $this->boot->db;

			$db->setTable('users');
			$db->where(array([
				'field' => 'username',
				'rule'  => '=',
				'value' => $username
			]));

			//pretvaramo vracenu vrijednost u bool i odabiremo suprotno
			//  od dobivenog(false u true, true u false)
			return !$db->prvi();		
		}

		public function validate_argumenti($arguments, $input, $args){
	
			$ok = true;
			if( !empty($arguments) ){
				
				foreach($arguments as $argument)
				{
					if($argument['req'] == 'req' && strlen($argument['value']) < 1)
					{
						$ok = false;
					}
				}	
			}
			
			return $ok;	
		}

		public function validate_newArguments($arguments = array(), $input, $args){
	
			$ok = true;
			for($i=0; $i<sizeof($arguments); $i++)
			{
			
				$argument = $arguments[$i];

				$title 		= $argument->name;
				$trueName 	= $argument->trueName;
				$req 		= $argument->req;
				$refTable 	= $argument->refTable;
				$refField 	= $argument->refField;


				$ok = length(array(
					$title,
					$trueName,
				), 5);

				$refTable = $argument->refTable;
				if(
					!ctype_digit($req) 
						|| 
					!ctype_digit($refTable) 
						|| 
					!ctype_digit($refField) 
				) $ok = false;
					

				if(!$ok){

					$this->addRuleMessage(
						'newArguments',
						'argument ' . $title . ' nije ispravno ispunjen.'
					);	

					return $ok;
				}
			}	
			
			return $ok;	
			
		}//validate_uniqueArguments()
		 
	}//kraj 'Validator' klase
	

	function length($strings, $l){

	 	foreach($strings as $string)
	 	{

	 		if(strlen($string) < $l) return false;
	 	}

	 	return true;
	 }
	 