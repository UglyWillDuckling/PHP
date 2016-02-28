<?php 
	
	class DB extends PDO
	{	
		protected $table;
		protected $result = array();
		
		public function __construct($db_type, $db, $host, $user, $pass){
			
			$dns = "$db_type:dbname=$db; host=$host";			
			parent::__construct($dns, $user, $pass);		
		}
	
		public function findAll($rules = array()){
			
			$sql  = "SELECT * FROM " . $this->table . " ";
			
			foreach($rules as $rule => $value){
				
				$sql .= $rule . " " . $value . " "; 
			}

			$stmt = $this->query($sql, PDO::FETCH_ASSOC);
			$rez  = $stmt->fetchAll();

		// ako je rez definiran njegova vrijednost se zadaje polju result
			$this->result = $rez ?: array();
			return $this;		
		}

		public function whereJoin($joins, $fields, $rules, $conds = array()){

			$where = " WHERE ";		
			foreach($rules as $rule){
			
				$cond   = 
					$this->table 
					. "."
					 . $rule['field'] 
					. " " 
					. $rule['rule'] 
					. ":" 
					. $rule['field']
				;

				$where .= $cond . " AND ";
			}	
			$where = trim($where, "AND ");
			
		/// DODAVANJE UVJETA ZA QUERY /////	
			$condition = "";			
			foreach($conds as $name => $value)
			{				
				$condition .= $name . " " . $value . " ";
			}

		//// DODAVANJE PRAVILA ZA JOIN-ove /////	
			$joinText = "";
			foreach($joins as $join)
			{

				$joinText .= 
					$join['type']  . " JOIN " . $join['table'] 
					. " ON " 
					. $join['table'] . "." . $join['joinField']			
					. "=" 
					. $this->table   . "." . $join['tableField'] 
					. " "				
				;
			}


			$fieldsWanted = "";
			foreach($fields as $polje){

				$fieldsWanted .= $polje . ", ";
			}
			$fieldsWanted = trim($fieldsWanted, ", ");

			$sql = 
				"SELECT "  . $fieldsWanted 
				. " FROM " . $this->table 			
				. " " . $joinText 
				. " " . $where 
				. " " . $condition
			;
			$sth = $this->prepare($sql);

			foreach($rules as $rule)
			{			
				$sth->bindValue(":" . $rule['field'], $rule['value']);
			}			
			$sth->execute();
			$result = $sth->fetchAll(PDO::FETCH_ASSOC);

			if($result) $this->result = $result;
			else 		$this->result = array();
			
			return $this;
		}//whereJoin()
		 

		public function where($rules, $conds = array()){
			
			$where = " WHERE ";		
			foreach($rules as $rule){

				$cond   = $rule['field'] . $rule['rule'] . ":" . $rule['field'];
				$where .= $cond . " AND ";
			}	
			$where = trim($where, "AND ");
			
		////// DODAVANJE UVJETA ZA QUERY /////	
			$condition = "";			
			foreach($conds as $name => $value)
			{				
				$condition .= $name . " " . $value . " ";
			}
			
			$sql = "SELECT * FROM " . $this->table . " " . $where . " " . $condition;			
			$sth = $this->prepare($sql);

			foreach($rules as $rule)
			{			
				$sth->bindParam(":" . $rule['field'], $rule['value']);
			}			
			$sth->execute();
			$result = $sth->fetchAll(PDO::FETCH_ASSOC);
			
			if($result) $this->result = $result;
			else 		$this->result = array();
			
			return $this;
		}//where
		
		public function setTable($table){
			
			$this->table = $table;
		}//setTable
	
		public function getAll(){
			if(!empty($this->result))
				return $this->result;
			
			return false;
		}//all
		
		
		/*
		*dodavanje reda u tablicu
		*/	
		public function add($row){
			
			//uzimamo imena kolumni koje zelimo staviti u query
			$columns = array_keys($row);
			
			$stupci = "(";
			$values = "(";		
			foreach($columns as $column){
				
				$stupci .= $column . ", ";
				$values .= ":" . $column . ", ";
			}
			$stupci  = trim($stupci, ", ");
			$stupci .= ")";
			$values  = trim($values, ", ");
			$values .= ")";
				
			$sql = "INSERT INTO " . $this->table . $stupci . " VALUES " . $values;					
			$sth = $this->prepare($sql);
			
			foreach($row as $stupac => $vrijednost){
				
				$sth->bindValue( ":{$stupac}", $vrijednost);
			}
			
			if(!$sth->execute())
			{		
				throw new Exception(
					"nevaljan insert, pokusajte opet.<br>". print_r($sth->errorinfo())
				);
			}		
		
			return $this;
		}//add
		
		public function set($setData, $whereField = array()){			
		//imena su jako losa, treba popraviti
			
			$set = "SET ";
			foreach($setData as $field)
			{		
				$set .= $field['field'] . "=:" . $field['field'] . ", ";
			}
			$set = trim($set, ", ");
			
			$where = "WHERE ";
			foreach($whereField as $cond)
			{			
				$where .= $cond['field'] . $cond['rule'] . ":" . $cond['field'];			
			}
			
			$sql = "UPDATE " . $this->table . " " . $set . " " . $where;		
			$stmt = $this->prepare($sql);

			foreach($setData as $data)
			{			
				$stmt->bindValue(":".$data['field'], $data['value']);
			}
			foreach($whereField as $where)
			{		
				$stmt->bindValue(":".$where['field'], $where['value']);
			}
			
			if(!$stmt->execute())
			{
				throw new Exception('los update.<br>' . print_r($stmt->errorinfo()));
			}	

			return true;		
	}
		
		public function delete($rules){

			$where = " WHERE ";		
			foreach($rules as $rule){
				
				$cond   = $rule['field'] . $rule['rule'] . ":" . $rule['field'];
				$where .= $cond . " AND ";
			}	

			$where = trim($where, "AND ");
			
			$sql = "DELETE FROM " . $this->table . " " . $where;
			$sth = $this->prepare($sql);	

			foreach($rules as $rule){
				
				$sth->bindParam(":" . $rule['field'], $rule['value']);
			}	
			if(!$sth->execute())
			{
				throw new Exception("delete nije uspio.");
			}					
		}//delete()
		
		public function startTransaction(){

			$this->query("start transaction;");
		}

		public function commit(){

			$this->query('commit;');
		}

		public function rollback(){

			$this->query('rollback;');
		}
		 		
		public function prvi(){
			
			if(!empty($this->result)){
				
				return $this->result[0];
			}			
			return false;
		} //prvi()
		  
	}//DB class
?>