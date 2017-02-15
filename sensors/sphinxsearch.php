<?php
namespace monitor\sensors;

require_once __DIR__ . "/../interfaces/isensor.php";

require_once __DIR__ . "/../include/pdoconn.php";

class SphinxSearch implements ISensor{
	const DOCS		= "documents";
	const DISK_BYTES	= "disk_bytes";
	const RAM_BYTES		= "ram_bytes";
	const CHUNKS		= "chunks";

	private $db_index;

	private $pdo;

	function __construct($db_index, $db_host = "127.0.0.1", $db_user = "root", $db_pass = ""){
		$this->db_index = $db_index;

		$this->pdo = new \PDOConn("mysql:host=" . $db_host, $db_user, $db_pass);
	}

	function getAvailableOptions(){
		return [ self::DOCS, self::DISK_BYTES, self::RAM_BYTES, self::CHUNKS ];
	}

	function getValues(){
		try{
			$this->pdo->connect();

			$result = [];

			foreach($this->pdo->query("show index {$this->db_index} status") as $row) {
				// $row = array_change_key_case($row);

				switch( $row["variable_name"] ){
				case "indexed_documents":	$key = self::DOCS;		break;
				case "disk_bytes":		$key = self::DISK_BYTES;	break;
				case "ram_bytes":		$key = self::RAM_BYTES;		break;
				case "disk_chunks":		$key = self::CHUNKS;		break;
				default:			$key = false;			break;
				}

				if ($key)
					$result[$key] = $row["value"];
			}

			return $result;
		}catch (PDOException $e){
			return [
				self::DOCS	=> 0,
				self::CHUNKS	=> 0,
			];
		}
	}
}

