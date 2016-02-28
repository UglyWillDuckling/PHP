<?php
	class Rezervacija{

		protected $fields = array(),
				  $db 	  = null;

		public function __construct($db, $id=null){

			$this->db = $db;

			if($id){

				$this->setRezervacija($id);
			}
		}

		public function uTijeku()
		{

			if($this->_getField('u_tijeku') == 1)
				return true;

			return false;
		}

		protected function _getField($field){

			if(isset($this->fields[$field]))
				return $this->fields[$field];

			return false;
		}

		public function setRezervacija($id){

			$this->_setTableReservation();

			$fields = $this->db->where(array([
				'field' => 'id',
				'rule'  => '=',
				'value' => $id
			]))
			->prvi();

			if($fields)
				$this->fields = $fields;
			else
			{
				throw new Exeception('nema takve rezervacije');
			}

		}

		public function iznajmi()
		{
			$this->_setTableReservation();

			if(!empty($this->fields)){
				$fields = $this->fields;

				$today 	     = strtotime("today");
				$todayString = date('Y-m-d', $today);

				$update = $this->db->set(array([
					'field' => 'u_tijeku',
					'value' => '1'
				],[
					'field' => 'datum_pocetak',
					'value' => $todayString
				]),
				array([
					'field' => 'id',
					'rule'  => '=',
					'value' => $fields['id']
				]));

				if($update){

					return true;
				}
				return false;

			//trebalo bi azurirati podatke u $fields array-u		
				
			}
			else{

				throw new Exeception('nije odabrana nijedna rezervacija');
			}		
		}//iznajmi()



		public function ponisti(){
			$this->_setTableReservation();

			if(!empty($this->fields)){
				$fields = $this->fields;

				$this->db->set(array([
					'field' => 'active',
					'value' => '0'
				]),
				array([
					'field' => 'id',
					'rule'  => '=',
					'value' => $fields['id']
				]));
				//trebalo bi azurirati podatke u $fields array-u

				return true;
			}
			
			else
			{
				throw new Exeception('nije odabrana nijedna rezervacija');
			}
		}

		public function zavrsi(){

			$this->_setTableReservation();

			if(!empty($this->fields)){

				$fields = $this->fields;

				$today 		  = strtotime('today');
				$pocetakStamp = strtotime($fields['datum_pocetak']);

				$razlika_u_sekundama = $today-$pocetakStamp;					
				$razlika_u_danima    = floor($razlika_u_sekundama/(3600*24));
				$totalCijena 		 = $razlika_u_danima * $fields['cijena_dan'];

				$datum_zavrsetak = date('Y-m-d', $today);


				$rez = $this->db->set(array([
					'field' => 'active',
					'value' => '0'
				],[
					'field' => 'datum_zavrsetak',
					'value' => $datum_zavrsetak
				],[
					'field' => 'u_tijeku',
					'value' => '0' 
				], [
					'field' => 'zavrseno',
					'value' => '1'
				], [
					'field' =>'cijena_total',
					'value' => $totalCijena
				]),
				array([
					'field' => 'id',
					'rule'  => '=',
					'value' => $fields['id']
				]));

				//trebalo bi azurirati podatke u $fields array-u
				if($rez)
					return true;

				return false;
			}	
			else
			{
				throw new Exeception('nije odabrana nijedna rezervacija');
			}
		}//zavrsi()
		 	
		public function produzi(){

			$this->_setTableReservation();

			if(!empty($this->fields)){
				$fields = $this->fields;

				$maxTrajanje = ( (int)$fields['max_trajanje'] ) + 30;

				$this->db->set(array([
					'field' =>'max_trajanje',
					'value' => $maxTrajanje
				]),
				array([
					'field' => 'id',
					'rule'  => '=',
					'value' => $fields['id']
				]));
			//trebalo bi azurirati podatke u $fields array-u

				return true;
			}	
			else
			{
				throw new Exeception('nije odabrana nijedna rezervacija');
			}
		}//zavrsi()

		protected function _setTableReservation(){

			$this->db->setTable('rezervacija');
		}	

		public function updateFields(){

			//update fields polja u samom objektu
		}	

		protected function _checkFields()
		{
			if(empty($this->fields))
			{
				throw new Exception('nije odabrana niti jedna rezervacija!');
			}
		}//_checkFields()	 
	}//kraj Rezervacija