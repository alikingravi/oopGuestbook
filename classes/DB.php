<?php
class DB{
	private static $_instance = null;	//Store the instance of the database so you dont have to connect to it again and again

	private $_pdo, 				//Store the PDO object here when instantiated
			$_query, 			//Check the last query executed
			$_error = false, 	//Whether query failed or not
			$_results, 			//Store results
			$_count = 0;		//Count of results

	//Constructor uses PDO to set database info
	private function __construct(){
		try{
			$this->_pdo = new PDO('mysql:host=' . Config::get('mysql/host') . ';dbname=' . Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));
		} 
		catch(PDOException $e){
			die($e->getMessage());
		}
	}

	//Gets the instance of a database if it does not already exist
	public static function getInstance(){
		if (!isset(self::$_instance)) {
			self::$_instance = new DB();
		}
		return self::$_instance;
	}

	//A function to use mysql database qureies
	public function query($sql, $params = array()){
		$this->_error = false;		//reset error back to false if any were set

		//Check if query has been prepared successfully
		if ($this->_query = $this->_pdo->prepare($sql)) {
			$x = 1;
			if (count($params)) {
				foreach ($params as $param) {
					$this->_query->bindValue($x, $param);
					$x++;
				}
			}

			if ($this->_query->execute()) {
				$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
				$this->_count = $this->_query->rowCount();
			}else{
				$this->_error = true;
			}
		}
		return $this;
	}

	public function action($action, $table, $where = array()){
		if (count($where) === 3) {
			$operators = array('=', '>', '<', '>=', '<=');

			$field 		= $where[0];
			$operator 	= $where[1];
			$value 		= $where[2];

			if (in_array($operator, $operators)) {
				$sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";

				if (!$this->query($sql, array($value))->error()) {
					return $this;
				}
			}
		}
		return false;
	}

	public function get($table, $where){
		return $this->action('SELECT *', $table, $where);
	}

	public function delete($table, $where){
		return $this->action('DELETE', $table, $where);
	}

	public function insert($table, $fields = array()){
		$keys = array_keys($fields);
		$values = '';
		$x=1;

		foreach ($fields as $field) {
			$values .= '?';
			if ($x < count($fields)) {
				$values .= ', ';
			}
			$x++;
		}

		$sql = "INSERT INTO {$table} (`" . implode('`, `', $keys) . "`) VALUES ({$values})";
		
		if (!$this->query($sql, $fields)->error()) {
			return true;
		}
	
		return false;
	}

	public function update($table, $id, $fields){
		$set = '';
		$x = 1;

		foreach ($fields as $name => $value) {
			$set .= "{$name} = ?";
			if ($x < count($fields)) {
				$set .= ', ';
			}
			$x++;
		}

		$sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";
		
		if (!$this->query($sql, $fields)->error()) {
			return true;
		}
		return false;		
	}

	public function results(){
		return $this->_results;
	}

	public function first(){
		return $this->results()[0];
	}

	public function error(){
		return $this->_error;
	}

	public function count(){
		return $this->_count;
	}

}