<?php


	class Content{

		protected $db = null;

		public function __construct($db){

			$this->db = $db;
		}

		public function getCurrentReservations(){

		}
	}

	function getNewCars($db){
		
		$db->setTable('vozila');

		return $db->where(array([
			'field' => 'active',
			'rule' => '>',
			'value' => '0'
		],[
			'field' => 'na_lageru_broj',
			'rule' => '>',
			'value' => '0'
		]		
		), array(
			'order by' => 'datum_upis',
			'limit'    => '5'
		))
		->getAll();			
	}
	
	function getCarClasses($db){
			
		$db->setTable('klasa');
		return $db->findAll()->getAll();
	}
	
	function clean($dirt)
	{	
		return htmlentities($dirt, ENT_COMPAT, "UTF-8");
	}
	
	function getZupanije($db)
	{	
		$db->setTable('zupanija');
		return $db->findAll()->getAll();
	}	

	function getMjesta($db){
		
		$db->setTable('mjesta');
		return $db->findAll()->getAll();		
	}

	function getZaposlenici($db){
		
		$db->setTable('zaposlenici');
		return $db->findAll()->getAll();		
	}
		
		
	function uploadImage($image, $destinationPath)
	{

		$tmp  = $image['tmp_name'];
		if( move_uploaded_file($tmp, $destinationPath) )
		{
			return true;
		}

		return false;
	}	
	

	function getCurrentReservations($db){

		$db->setTable('rezervacija');
		/**
		 * Prvi array je za join pravila, sadrzi tip joina, ime tablice,
		 *  te polja koja treba usporediti('joinField' označava stupac u tablici
		 *  na koju radimo join, a 'tableField' je stupac u trenutno odabranoj tablici
		 * Drugi array je za polja koja trazimo preko query-ja
		 * Treci array je za pravila kod odabira stavki(where id=3, active>0)
		 * Četvrti array na služi za zadavanje odredenih pravila kod query-ja
		 *  (LIMIT 5 , ORDER BY id DESC)  
		 */
		$rezervacije = 
		  $db->whereJoin(array([
			'type' 		 => 'inner',
			'table' 	 => 'users',
			'joinField'  => 'id',
			'tableField' => 'user_id'
		],[

			'type' 		 => 'inner',
			'table'		 => 'vozila',
			'joinField'  => 'id',
			'tableField' => 'vozila_id'

		]),[
			'users.username',
			'vozila.model',
			'rezervacija.datum_rezervacija',
			'rezervacija.datum_pocetak',
			'rezervacija.datum_zavrsetak',
			'rezervacija.cijena_dan',
			'rezervacija.max_trajanje',
			'rezervacija.id as rezId',
			'rezervacija.u_tijeku'
		],
		array([
			'field' => 'active',
			'rule'  => '>',
			'value' => '0'
		]))
		->getAll();

		if($rezervacije)
			return $rezervacije;

		return array();
	}//getCurrentReservations()
	 
	 function getVozila($db, $order=null, $gdje = array(), $slobodno = null)
	 {
	 	$orders = array();

	 	if($order) 
			$orders['order by'] = $order;
		 else
		 	$orders['order by'] = 'datum_upis';


		$where = array([
			'field' => 'active',
			'rule'  => '=',
			'value' => '1'
		]);

		if(!empty($gdje)){

			foreach($gdje as $field => $value){
				$rule = 
				[
					'field' => $field,
					'rule'  => '=',
					'value' => $value
				];

				$where[] = $rule;
			}
		}

		if($slobodno){

			$where[] = [
				'field' => 'na_lageru_broj',
				'rule'  => '>',
				'value' => '0'
			];
		}
	
	 	$db->setTable('vozila');
	 	$db->whereJoin(array([
			'type' 		 => 'inner',
			'table' 	 => 'boja',
			'joinField'  => 'id',
			'tableField' => 'boja_id'
		],[
			'type' 		 => 'inner',
			'table' 	 => 'marka',
			'joinField'  => 'id',
			'tableField' => 'marka_id'
		], [
			'type' 		 => 'inner',
			'table' 	 => 'klasa',
			'joinField'  => 'id',
			'tableField' => 'klasa_id'
		], [
			'type' 		 => 'inner',
			'table' 	 => 'razina',
			'joinField'  => 'id',
			'tableField' => 'razina_id'
		]), [
	 		'boja.naziv as boja',
	 		'klasa.naziv as klasa',
	 		'klasa.id as klasa_id',
	 		'klasa.cijena as klasa_cijena',
	 		'marka.naziv as marka',
	 		'vozila.model',
	 		'vozila.id as vozilaId',
	 		'vozila.stanje',
	 		'vozila.obujam_motora as obujam',
	 		'vozila.broj_vrata as vrata',
	 		'vozila.klima_uredaj as klima',
	 		'vozila.snaga_motor as snaga',
	 		'vozila.na_lageru_broj as naLageru',
	 		'vozila.slika as slika',
	 		'razina.naziv as razina',
	 		'razina.id as razina_id',
	 		'vozila.tip_motora as tipMotora',
	 		'vozila.id as carId'
		],
		$where,
		$orders	
		);

		$vozila = $db->getAll();		
		return $vozila;
	 }

	 function removeVozilo($db, $id){

	 	$db->setTable('vozila');

	 	$db->set(array([
	 		'field' => 'active',
	 		'value' => '0'
	 	]),array([
	 		'field' => 'id',
	 		'rule'  => '=',
	 		'value' => $id
	 	]));

	 }

	 function getKlase($db){

		$db->setTable('klasa');
	 	$klase = $db->findAll()->getAll();

	 	return $klase;
	 }

	 function getBoje($db){

	 	$db->setTable('boja');
	 	$boje = $db->findAll()->getAll();

	 	return $boje;
	 }

	 function getMarke($db){

	 	$db->setTable('marka');

	 	$marke = $db->findAll()->getAll();

	 	return $marke;
	 }

	 function getRazine($db){

	 	$db->setTable('razina');
	 	$razina = $db->findAll(array(
	 		'order by' => 'id'
	 	))
	 	->getAll();

	 	return $razina;
	 }

	 function getRezervacije($db, $id){

	 	echo $id;
	 	$db->setTable('rezervacija');
	 	$rezervacije = $db->where(array([
	 		'field' => 'id',
	 		'rule'  => '=',
	 		'value' => $id
	 	]))
	 	->getAll();

	 	return $rezervacije;
	 }

	 function getCijena($db, $klasa, $razina, $cijena_klase){


	 	$db->setTable('cijenik');
	 	$db->where(array([
	 		'field' => 'klasa_id',
	 		'rule'	=> '=',
	 		'value' => $klasa
	 	],[
	 		'field' => 'razina_id',
	 		'rule'	=> '=',
	 		'value' => $razina
	 	]));

	 	$cijenikPravila = $db->prvi();

		$cijena = $cijena_klase * $cijenikPravila['modifikator']; 

		if($cijenikPravila['popust'] > 0)
		{

			$popust = $cijenikPravila['popust'];
			$cijena = $cijena * (1 - $popust/100);
		}

		return $cijena;
	 }


