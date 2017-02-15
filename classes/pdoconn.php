<?php

//use \PDO;

class PDOConn{
	private $pdo;

	private $db_pdo;
	private $db_user;
	private $db_pass;

	// mysql:host=127.0.0.1;port=3306;dbname=monitor;charset=utf8
	function __construct($db_pdo = "mysql:host=127.0.0.1;charset=utf8", $db_user = "root", $db_pass = "", $connect = false){
		$this->db_pdo	= $db_pdo;
		$this->db_user	= $db_user;
		$this->db_pass	= $db_pass;

		if ($connect)
			$this->connect();
	}

	function connect(){
		$this->pdo = new PDO($this->db_pdo, $this->db_user, $this->db_pass, [
			PDO::ATTR_PERSISTENT	=>	true		,
			PDO::ATTR_CASE		=>	PDO::CASE_LOWER	,
		]);
	}

	function query($sql, array $params = []){
		return $this->exec_statement_($sql, $params)
			->fetchAll(PDO::FETCH_ASSOC);
	}

	function exec($sql, array $params = []){
		return $this->exec_statement_($sql, $params)
			->rowCount();
	}

	private function exec_statement_($sql, array $params){
		$db = $this->pdo;

		$statement = $db->prepare($sql);

		$statement->execute($params);

		return $statement;
	}
}

