<?php
namespace monitor\storage;

require_once __DIR__ . "/../interfaces/istorage.php";

require_once __DIR__ . "/../include/pdoconn.php";

class PDOStorage implements IStorage{
	const MKEY_DELIMITER = ".";

	private $prefix;

	private $db_table;

	private $pdo;

	// mysql:host=127.0.0.1;port=3306;dbname=monitor;charset=utf8
	function __construct($prefix, $db_pdo = "mysql:host=127.0.0.1;charset=utf8", $db_user = "root", $db_pass = "", $db_table = "monitor.monitor"){
		$this->prefix	= $prefix;

		$this->db_table	= $db_table;

		$this->pdo = new \PDOConn($db_pdo, $db_user, $db_pass, true);
	}

	function store($timestamp, $value){
		$day	= (int) date("Ymd");
		$hour	= (int) date("H");
		$min	= (int) date("i");
		$sec	= (int) date("s");

		$value	= json_encode($value);

		try{
			$this->pdo->exec("
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

}

/*
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

