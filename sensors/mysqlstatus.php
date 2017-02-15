<?php
namespace monitor\sensors;
use \PDO;

require_once __DIR__ . "/../interfaces/isensor.php";
require_once __DIR__ . "/../include/my_curl.php";

class MySQLStatus implements ISensor{
	const QUERY	= "query_conn";
	const SLEEP	= "sleep_conn";

	private $db_host;
	private $db_user;
	private $db_pass;

	function __construct($db_host = "127.0.0.1", $db_user = "root", $db_pass = ""){
		$this->db_host = $db_host;
		$this->db_user = $db_user;
		$this->db_pass = $db_pass;
	}

	function getAvailableOptions(){
		return [ self::QUERY, self::SLEEP ];
	}

	function getValues(){
		try{
			$pdo = new PDO("mysql:host=" . $this->db_host, $this->db_user, $this->db_pass, [
				PDO::ATTR_PERSISTENT	=>	true		,
				PDO::ATTR_CASE		=>	PDO::CASE_LOWER	,
			]);

			$result = [
				self::QUERY	=> 0,
				self::SLEEP	=> 0,
			];

			foreach($pdo->query("show processlist", PDO::FETCH_ASSOC) as $row) {
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

