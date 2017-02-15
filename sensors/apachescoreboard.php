<?php
namespace monitor\sensors;

require_once __DIR__ . "/../interfaces/isensor.php";
require_once __DIR__ . "/../include/my_curl.php";

class ApacheScoreboard implements ISensor{
	const BUSY	= "busy_workers";
	const IDLE	= "idle_workers";
	const OPEN	= "open_slots";
	const TOTAL	= "total_slots";

	const DEFAULT_PATH = "http://127.0.0.1/server-status?auto";

	private $path;

	function __construct($path = false){
		$this->path = $path ? $path : self::DEFAULT_PATH;
	}

	function getAvailableOptions(){
		return [ self::BUSY, self::IDLE, self::OPEN, self::TOTAL ];
	}

	function getValues(){
		$data = [
			self::BUSY	=>	0,
			self::IDLE	=>	0,
			self::OPEN	=>	0,
			self::TOTAL	=>	0
		];

		$scoreboard = $this->getScoreboard_();

		for($i = 0; $i < strlen($scoreboard); ++$i){
			switch($scoreboard[$i]){
			case '_' : $key = self::IDLE; break;
			case '.' : $key = self::OPEN; break;
			default  : $key = self::BUSY; break;
			}

			++$data[$key];
			++$data[self::TOTAL];
		}

		return $data;
	}

	private function getScoreboard_(){
		$SCOREBOARD     = strtoupper("Scoreboard: ");
		$SCOREBOARD_LEN = strlen($SCOREBOARD);

		$txt = strtoupper(my_curl($this->path));
		$txt = explode("\n", $txt);

		foreach($txt as $s){
			if (substr($s, 0, $SCOREBOARD_LEN) == $SCOREBOARD){
				return substr($s, $SCOREBOARD_LEN);
			}
		}

		return false;
	}
}

