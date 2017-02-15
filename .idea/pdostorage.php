<?php
namespace monitor\storage;

use \PDO;

require_once __DIR__ . "/../interfaces/istorage.php";

class PDOStorage implements IStorage{
	const MKEY_DELIMITER = ".";

	private $prefix;

	private $db_pdo;
	private $db_user;
	private $db_pass;
	private $db_table;

	private $pdo;

	// mysql:host=127.0.0.1;port=3306;dbname=monitor;charset=utf8

	function __construct($prefix, $db_pdo = "mysql:host=127.0.0.1;charset=utf8", $db_user = "root", $db_pass = "", $db_table = "monitor.monitor"){
		$this->prefix   = $prefix;

		$this->db_pdo   = $db_pdo;
		$this->db_user  = $db_user;
		$this->db_pass  = $db_pass;
		$this->db_table = $db_table;

		self::db_connect();
	}

	function store2($day, $hour, $min, $sec, $monitor, $key, $value){
		$mkey = $monitor . self::MKEY_DELIMITER . $key;

		try{
			$this->db_exec("
				insert into $this->db_table(
					`host`	,
					`day`	,
					`hour`	,
					`min`	,
					`sec`	,
					`value`
				)values(
					:host	,
					:day	,
					:hour	,
					:min	,
					:sec	,
					:value
				)
			", [
				"host"	=>	$this->prefix	,
				"mkey"	=>	$mkey		,
				"day"	=>	$day		,
				"hour"	=>	$hour		,
				"min"	=>	$min		,
				"sec"	=>	$sec		,
				"value"	=>	$value		,
			]);

		}catch (PDOException $e){
			// error
		}
	}

	private function store_old($day, $hour, $min, $sec, $monitor, $key, $value){

		$mkey = $monitor . self::MKEY_DELIMITER . $key;

		try{
			$this->db_exec("
				insert into $this->db_table(
					`host`	,
					`mkey`	,
					`day`	,
					`hour`	,
					`min`	,
					`sec`	,
					`value`
				)values(
					:host	,
					:mkey	,
					:day	,
					:hour	,
					:min	,
					:sec	,
					:value
				)
			", [
				"host"	=>	$this->prefix	,
				"mkey"	=>	$mkey		,
				"day"	=>	$day		,
				"hour"	=>	$hour		,
				"min"	=>	$min		,
				"sec"	=>	$sec		,
				"value"	=>	$value		,
			]);

		}catch (PDOException $e){
			// error
		}
	}

	private function db_connect(){
		$this->pdo = new PDO($this->db_pdo, $this->db_user, $this->db_pass, [
			PDO::ATTR_PERSISTENT	=>	true		,
			PDO::ATTR_CASE		=>	PDO::CASE_LOWER	,
		]);
	}

	private function db_query($sql, $params = []){
		$db = $this->pdo;

		$statement = $db->prepare($sql);

		$statement->execute($params);

		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}

	private function db_exec($sql, $params = []){
		$db = $this->pdo;

		$statement = $db->prepare($sql);

		$statement->execute($params);

		return $statement->rowCount();
	}
}

/*
CREATE TABLE `monitor` (
  `host` varchar(32) NOT NULL,
  `mkey` varchar(32) NOT NULL,
  `day` int(11) unsigned NOT NULL,
  `hour` tinyint(4) unsigned NOT NULL,
  `min` tinyint(4) unsigned NOT NULL,
  `sec` tinyint(4) unsigned NOT NULL,
  `value` varchar(32) DEFAULT NULL,

  PRIMARY KEY (host, mkey,`day`,`hour`,`min`, sec)
) ENGINE=MyISAM DEFAULT CHARSET=ascii

CREATE TABLE `monitor1` (
  `host` varchar(32) NOT NULL,
  `day` int(11) unsigned NOT NULL,
  `hour` tinyint(4) unsigned NOT NULL,
  `min` tinyint(4) unsigned NOT NULL,
  `sec` tinyint(4) unsigned NOT NULL,
  `value` varchar(4096) DEFAULT NULL,

  PRIMARY KEY (host,`day`,`hour`,`min`, sec)
) ENGINE=MyISAM DEFAULT CHARSET=ascii;
*/

