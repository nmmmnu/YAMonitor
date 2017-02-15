<?php
namespace monitor\sensors;

require_once __DIR__ . "/../interfaces/isensor.php";

require_once __DIR__ . "/../include/my_curl.php";

class NginxStatus implements ISensor{
	const CONN	= "connections";

	const DEFAULT_PATH = "http://127.0.0.1/server-status/1";

	private $path;

	function __construct($path = false){
		$this->path = $path ? $path : self::DEFAULT_PATH;
	}

	function getAvailableOptions(){
		return [ self::CONN ];
	}

	function getValues(){
		return [
			self::CONN	=>	self::getStatus()
		];
	}

	private function getStatus(){
		$CONN     = strtoupper("Active connections: ");
		$CONN_LEN = strlen($CONN);

		$txt = strtoupper(my_curl($this->path));
		$txt = explode("\n", $txt);

		foreach($txt as $s){
			if (substr($s, 0, $CONN_LEN) == $CONN){
				return (int) substr($s, $CONN_LEN);
			}
		}

		return 0;
	}
}

