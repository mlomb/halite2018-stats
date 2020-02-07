<?php
/*

* Usage:

$query = "SELECT 'testrow' FROM 'database_name'.'table'";

DB::get()->query($query);

if(DB::get()->count() != 0){
    foreach(DB::get()->results() as $row){
        $testrow = $row->testrow;
    }
}

* Util:

DB::get()->lastInsertId()
DB::get()->result()

*/

class DB{
	private static $_instance = null;
	private $_pdo,
			$_query,
			$_error = false,
			$_results,
			$_lastid,
			$_count = 0,
            $_queriesCount = 0;

	private function __construct(){
		try {
			$this->_pdo = new PDO('mysql:host=localhost;dbname=halite2018', "<username>", "<password>");
		} catch(PDOException $e){
			die($e->getMessage());  // Enable in case of error
		}
	}

	public static function get(){
		if(!isset(self::$_instance)){
			self::$_instance = new DB();
		}
		return self::$_instance;
	}

	public function query($sql, $params = array()){
        $this->_queriesCount++;
		if($this->_queriesCount == 1) {
			$this->query("SET NAMES utf8;");
		}
		$this->_error = false;
		if($this->_query = $this->_pdo->prepare($sql)){
			$x = 1;
			if(count($params)){
				foreach($params as $param){
					$this->_query->bindValue($x, $param);
					$x++;
				}
			}

			if($this->_query->execute()){
				$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
				$this->_count = $this->_query->rowCount();
                $this->_lastid = $this->_pdo->lastInsertId();
			} else {
				$this->_error = true;
				print_r($this->_query->errorInfo()); // Enable in case of error
			}
		}

		return $this;
	}
	
	public function update($table, $data){
		$params = '';
		$values = '';
		$updEqual = '';
		$valuesArr = array();
		
		foreach ($data as $key => $value) {
			$params .= $key.',';
			$values .= '?,';
			$updEqual .= $key.'=VALUES('.$key.'),';
			array_push($valuesArr, $value);
		}
		
		$params = removeTrailingComma($params);
		$values = removeTrailingComma($values);
		$updEqual = removeTrailingComma($updEqual);
		
		$query = "INSERT INTO ".$table." (".$params.") VALUES (".$values.") ON DUPLICATE KEY UPDATE ".$updEqual;
				
		$this->query($query, $valuesArr);
	}

	public function results(){
		return $this->_results;
	}
    
	public function result(){
		return $this->results()[0];
	}

	public function lastInsertId(){
		return $this->_lastid;
	}

	public function error(){
		return $this->_error;
	}

	public function count(){
		return $this->_count;
	}

	public function queriesCount(){
		return $this->_queriesCount;
	}
}
?>