<?php
namespace monitor\sensors;

require_once __DIR__ . "/../interfaces/isensor.php";

require_once __DIR__ . "/../include/pdoconn.php";

class MySQLStatus implements ISensor{
	const QUERY	= "query_conn";
	const SLEEP	= "sleep_conn";

	private $pdo;

	function __construct($db_host = "127.0.0.1", $db_user = "root", $db_pass = ""){
		$this->pdo = new \PDOConn("mysql:host=" . $db_host, $db_user, $db_pass);
	}

	function getAvailableOptions(){
		return [ self::QUERY, self::SLEEP ];
	}

	function getValues(){
		try{
			$this->pdo->connect();

			$result = [
				self::QUERY	=> 0,
				self::SLEEP	=> 0,
			];

			foreach($this->pdo->query("show processlist") as $row) {
				// $row = array_change_key_case($row);

				switch( strtolower($row["command"]) ){
				case "query"	: $key = self::QUERY; break;
				default		: $key = self::SLEEP; break;
				}

				++$result[$key];
			}

			return $result;
		}catch (PDOException $e){
			return [
				self::QUERY	=> 0,
				self::SLEEP	=> 0,
			];
		}
	}
}

