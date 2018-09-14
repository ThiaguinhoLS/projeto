<?php 
namespace Src\DB;

class Sql {
	const HOSTNAME = "127.0.0.1";
	const USERNAME  = "root";
	const PASSWORD = "";
	const DBNAME = "db_meu_banco";

	private $conn;

	public function __construct() {
		$this->conn = new \PDO("mysql:dbname=".Sql::DBNAME.";host=".Sql::HOSTNAME, Sql::USERNAME, Sql::PASSWORD);
	}

	private function setParams($statement, $params = array()) {
		foreach ($params as $key => $value) {
			$this->bindParam($statement, $key, $value);
		}
	}

	private function bindParam($statement, $key, $value) {
		$statement->bindParam($key, $value);
	}

	public function query($raw, $params = array()) {
		$statement = $this->conn->prepare($raw);
		$this->setParams($statement, $params);
		$statement->execute();	
	} 

	public function select($raw, $params = array()) {
		$statement = $this->conn->prepare($raw);
		$this->setParams($statement, $params);
		$statement->execute();
		return $statement->fetchAll(\PDO::FETCH_ASSOC);
	}
}

 ?>
