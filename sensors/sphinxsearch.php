<?php
namespace monitor\sensors;
use \PDO;

require_once __DIR__ . "/../interfaces/isensor.php";
require_once __DIR__ . "/../include/my_curl.php";

class SphinxSearch implements ISensor{
	const DOCS		= "documents";
	const DISK_BYTES	= "disk_bytes";
	const RAM_BYTES		= "ram_bytes";
	const CHUNKS		= "chunks";

	private $db_host;
	private $db_user;
	private $db_pass;
	private $db_index;

	function __construct($db_index, $db_host = "127.0.0.1", $db_user = "root", $db_pass = ""){
		$this->db_host  = $db_host;
		$this->db_user  = $db_user;
		$this->db_pass  = $db_pass;
		$this->db_index = $db_index;
	}

	function getAvailableOptions(){
		return [ self::DOCS, self::DISK_BYTES, self::RAM_BYTES, self::CHUNKS ];
	}

	function getValues(){
		try{
			$pdo = new PDO("mysql:host=" . $this->db_host, $this->db_user, $this->db_pass, [
				PDO::ATTR_PERSISTENT	=>	true			,
				PDO::ATTR_CASE		=>	PDO::CASE_LOWER	,
			]);

			$result = [];

			foreach($pdo->query("show index {$this->db_index} status", PDO::FETCH_ASSOC) as $row) {
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

